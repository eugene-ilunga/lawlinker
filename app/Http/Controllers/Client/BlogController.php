<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Rules\CustomRecaptcha;
use App\Traits\GlobalMailTrait;
use Illuminate\Http\Request;
use Modules\Blog\app\Models\Blog;
use Modules\Blog\app\Models\BlogCategory;
use Modules\GlobalSetting\app\Models\CustomPagination;
use Modules\GlobalSetting\app\Models\Setting;

class BlogController extends Controller {
    use GlobalMailTrait;

    public function blog() {
        $pagination_qty = cache('CustomPagination')?->blog ?? CustomPagination::where('section_name', 'Blog')->value('item_qty');

        $blogs = Blog::select('id','admin_id', 'slug', 'thumbnail_image', 'created_at')->with([
            'admin' => function ($query) {
                $query->select('id', 'name');
            },
            'translation' => function ($query) {
                $query->select('blog_id', 'title', 'sort_description');
            },
        ])->whereHas('category', function ($query) {
            $query->active();
        })->orderBy('id', 'desc')->active()->paginate($pagination_qty);
        return view('client.blog.index', compact('blogs'));
    }
    public function blogCategory($slug) {
        $pagination_qty = cache('CustomPagination')?->blog ?? CustomPagination::where('section_name', 'Blog')->value('item_qty');

        $category = BlogCategory::select('id')->with([
            'translation' => function ($query) {
                $query->select('blog_category_id', 'title');
            },
        ])->whereSlug($slug)->active()->first();
        if (!$category) {
            abort(404);
        }

        $blogs = Blog::select('id','admin_id', 'slug', 'thumbnail_image', 'created_at')->with([
            'admin' => function ($query) {
                $query->select('id', 'name');
            },
            'translation' => function ($query) {
                $query->select('blog_id', 'title', 'sort_description');
            },
        ])->whereHas('category', function ($query) {
            $query->active();
        })->active()->where('blog_category_id', $category->id)->paginate($pagination_qty);

        return view('client.blog.category-blog', compact('category', 'blogs'));
    }

    public function blogDetails($slug) {
        $blog = Blog::select('id','admin_id', 'blog_category_id', 'slug', 'image', 'created_at')->with([
            'admin' => function ($query) {
                $query->select('id', 'name');
            },
            'translation'          => function ($query) {
                $query->select('blog_id', 'title', 'sort_description', 'description', 'seo_title', 'seo_description');
            },
            'category'             => function ($query) {
                $query->select('id');
            },
            'category.translation' => function ($query) {
                $query->select('blog_category_id', 'title');
            },
            'comments'             => function ($query) {
                $query->select('blog_id', 'name', 'email', 'comment', 'created_at')->active();
            },
        ])->whereHas('category', function ($query) {
            $query->active();
        })->whereSlug($slug)->active()->first();
        if (!$blog) {
            abort(404);
        };
        $blogCategories = BlogCategory::select('id', 'slug')->with([
            'translation' => function ($query) {
                $query->select('blog_category_id', 'title');
            },
        ])->active()->orderBy('slug', 'asc')->get();
        $latestBlog = Blog::select('id', 'slug', 'thumbnail_image', 'created_at')->with([
            'translation' => function ($query) {
                $query->select('blog_id', 'title');
            },
        ])->whereHas('category', function ($query) {
            $query->active();
        })->where('id', '!=', $blog->id)->active()->orderby('id', 'desc')->get()->take(5);
        return view('client.blog.show', compact('blog', 'blogCategories', 'latestBlog'));
    }

    public function commentStore(Request $request, $slug) {
        $blog = Blog::whereSlug($slug)->active()->firstOrFail();

        $setting = cache()->get('setting');

        $rules = [
            'name'       => 'required',
            'email'      => 'required|email',
            'comment'              => 'required|string|max:10000',
            'g-recaptcha-response' => $setting->recaptcha_status == 'active' ? ['required', new CustomRecaptcha()] : '',
        ];
        $messages = [
            'name.required'       => __('Name is required'),
            'email.required'      => __('Email is required'),
            'email.email'      => __('Please enter a valid email address.'),
            'comment.required'              => __('Comment is required'),
            'comment.string'                => __('Comment should be string'),
            'comment.max'                   => __('Comment should be less than 10000 characters'),
            'g-recaptcha-response.required' => __('Please complete the recaptcha to submit the form'),
        ];

        $request->validate($rules, $messages);

        $comment = $blog->comments()->create([
            'name'    => $request->name,
            'email'   => $request->email,
            'phone'   => $request->phone,
            'comment' => $request->comment,
        ]);

        $approved_status = $setting?->comments_auto_approved ?? Setting::where('key', 'comments_auto_approved')->select('value')->first()->value;

        if ($approved_status == 'active') {
            $comment->status = true;
            $comment->save();
            $notification = __('Comment Added Successfully');
            $notification = ['message' => $notification, 'alert-type' => 'success'];
        } else {
            try {
                $admin = Admin::superAdmin()->first();
                [$subject, $message] = $this->fetchEmailTemplate('blog_comment', ['admin_name' => $admin?->name, 'user_name' => $comment?->name]);
                $link = [$blog?->title => route('admin.blog-comment.show', $blog?->id)];
                $this->sendMail($admin?->email, $subject, $message, $link);
            } catch (\Exception $e) {
                info($e->getMessage());
            }

            $notification = __('Comment Added, wait for admin approval');
            $notification = ['message' => $notification, 'alert-type' => 'info'];
        }

        return redirect()->back()->with($notification);
    }
}
