<?php

namespace Modules\Blog\app\Http\Controllers;

use App\Enums\RedirectType;
use App\Http\Controllers\Controller;
use App\Traits\RedirectHelperTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Modules\Blog\app\Http\Requests\PostRequest;
use Modules\Blog\app\Models\Blog;
use Modules\Blog\app\Models\BlogCategory;
use Modules\Language\app\Enums\TranslationModels;
use Modules\Language\app\Models\Language;
use Modules\Language\app\Traits\GenerateTranslationTrait;

class BlogController extends Controller {
    use GenerateTranslationTrait, RedirectHelperTrait;

    public function index(Request $request) {
        checkAdminHasPermissionAndThrowException('blog.view');
        $query = Blog::query();

        $query->when($request->filled('keyword'), function ($qa) use ($request) {
            $qa->whereHas('translations', function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->keyword . '%');
                $q->orWhere('sort_description', 'like', '%' . $request->keyword . '%');
                $q->orWhere('description', 'like', '%' . $request->keyword . '%');
                $q->orWhere('seo_title', 'like', '%' . $request->keyword . '%');
                $q->orWhere('seo_description', 'like', '%' . $request->keyword . '%');
            });
        });

        $query->when($request->filled('is_feature'), function ($q) use ($request) {
            $q->where('is_feature', $request->is_feature);
        });

        $query->when($request->filled('show_homepage'), function ($q) use ($request) {
            $q->where('show_homepage', $request->show_homepage);
        });

        $query->when($request->filled('status'), function ($q) use ($request) {
            $q->where('status', $request->status);
        });

        $orderBy = $request->filled('order_by') && $request->order_by == 1 ? 'asc' : 'desc';

        if ($request->filled('par-page')) {
            $posts = $request->get('par-page') == 'all' ? $query->orderBy('id', $orderBy)->get() : $query->orderBy('id', $orderBy)->paginate($request->get('par-page'))->withQueryString();
        } else {
            $posts = $query->orderBy('id', $orderBy)->paginate()->withQueryString();
        }

        return view('blog::Post.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        checkAdminHasPermissionAndThrowException('blog.create');
        $categories = BlogCategory::with('translation')->active()->get();

        return view('blog::Post.create', ['categories' => $categories]);
    }

    public function store(PostRequest $request): RedirectResponse {
        checkAdminHasPermissionAndThrowException('blog.store');
        if ($request?->is_feature == 1) {
            Blog::select('is_feature')->feature()->update(['is_feature' => 0]);
        }
        $blog = Blog::create(array_merge(['admin_id' => Auth::guard('admin')->user()->id], $request->validated()));

        if ($blog && $request->hasFile('blog_image')) {
            $file_name = uploadAndOptimizeImage(
                file: $request->blog_image,
                resize: [730, 410]
            );
            $blog->image = $file_name;

            $file_name = uploadAndOptimizeImage(
                file: $request->blog_image,
                resize: [348, 220]
            );
            $blog->thumbnail_image = $file_name;

            $blog->save();
        }

        $this->generateTranslations(
            TranslationModels::Blog,
            $blog,
            'blog_id',
            $request,
        );

        return $this->redirectWithMessage(
            RedirectType::CREATE->value,
            'admin.blogs.edit',
            [
                'blog' => $blog->id,
                'code' => allLanguages()->first()->code,
            ]
        );
    }

    public function edit($id) {
        checkAdminHasPermissionAndThrowException('blog.edit');
        $code = request('code') ?? getSessionLanguage();
        if (!Language::where('code', $code)->exists()) {
            abort(404);
        }
        $blog = Blog::findOrFail($id);
        $categories = BlogCategory::with('translation')->get();
        $languages = allLanguages();

        return view('blog::Post.edit', compact('blog', 'code', 'categories', 'languages'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostRequest $request, $id) {
        checkAdminHasPermissionAndThrowException('blog.update');
        $validatedData = $request->validated();
        if ($request?->is_feature == 1) {
            Blog::select('is_feature')->feature()->update(['is_feature' => 0]);
        }

        $blog = Blog::findOrFail($id);

        if ($blog && $request->hasFile('blog_image')) {
            $file_name = uploadAndOptimizeImage(
                file: $request->blog_image,
                oldFile: $blog->image,
                resize: [728, 410]
            );
            $blog->image = $file_name;

            $thumbnail_path = uploadAndOptimizeImage(
                file: $request->blog_image,
                oldFile: $blog->thumbnail_image,
                resize: [348, 220]
            );
            $blog->thumbnail_image = $thumbnail_path;
            $blog->save();
        }
        $blog->update($validatedData);

        $this->updateTranslations(
            $blog,
            $request,
            $validatedData,
        );

        return $this->redirectWithMessage(
            RedirectType::UPDATE->value,
            'admin.blogs.edit',
            ['blog' => $blog->id, 'code' => $request->code]
        );
    }

    public function destroy($id) {
        checkAdminHasPermissionAndThrowException('blog.delete');

        $blog = Blog::findOrFail($id);

        if ($blog->comments()->count() > 0) {
            return redirect()->back()->with(['alert-type' => 'error', 'message' => __('Cannot delete post, it has comments.')]);
        }

        $blog->translations()->each(function ($translation) {
            $translation->post()->dissociate();
            $translation->delete();
        });

        if ($blog->image) {
            if (File::exists(public_path($blog->image))) {
                unlink(public_path($blog->image));
            }
            if (File::exists(public_path($blog->thumbnail_image))) {
                unlink(public_path($blog->thumbnail_image));
            }
        }

        if ($blog?->is_feature == 1) {
            Blog::inRandomOrder()->whereNot('id',$id)->first()->update(['is_feature' => 1]);
        }
        $blog->delete();

        return $this->redirectWithMessage(RedirectType::DELETE->value, 'admin.blogs.index');
    }

    public function statusUpdate($id) {
        checkAdminHasPermissionAndThrowException('blog.update');

        $blog = Blog::find($id);
        $status = $blog->status == 1 ? 0 : 1;
        $blog->update(['status' => $status]);

        $notification = __('Updated successfully');

        return response()->json([
            'success' => true,
            'message' => $notification,
        ]);
    }
}
