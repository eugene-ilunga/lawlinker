<?php

namespace Modules\NewsLetter\app\Http\Controllers\Admin;

use App\Enums\RedirectType;
use App\Http\Controllers\Controller;
use App\Services\MailSenderService;
use App\Traits\RedirectHelperTrait;
use Illuminate\Http\Request;
use Modules\Language\app\Enums\TranslationModels;
use Modules\Language\app\Models\Language;
use Modules\Language\app\Traits\GenerateTranslationTrait;
use Modules\NewsLetter\app\Models\NewsLetter;
use Modules\NewsLetter\app\Models\SubscriberContent;

class NewsLetterController extends Controller {
    use GenerateTranslationTrait, RedirectHelperTrait;
    public function index() {
        checkAdminHasPermissionAndThrowException('newsletter.view');
        $newsletters = NewsLetter::orderBy('id', 'desc')->where('status', 'verified')->get();

        return view('newsletter::index', ['newsletters' => $newsletters]);
    }

    public function create() {
        checkAdminHasPermissionAndThrowException('newsletter.mail');

        return view('newsletter::create');
    }

    public function destroy($id) {
        checkAdminHasPermissionAndThrowException('newsletter.delete');
        $newsletter = NewsLetter::find($id);
        $newsletter->delete();

        $notification = __('Deleted Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    public function store(Request $request) {
        checkAdminHasPermissionAndThrowException('newsletter.mail');
        $request->validate([
            'subject'     => 'required',
            'description' => 'required',
        ], [
            'subject.required'     => __('Subject is required'),
            'description.required' => __('Description is required'),
        ]);

        $newsletterCount = NewsLetter::select('id')->orderBy('id', 'desc')->where('status', 'verified')->count();

        if ($newsletterCount > 0) {
            $email_list = NewsLetter::select('email')->orderBy('id', 'desc')->where('status', 'verified')->get();
            try {
                (new MailSenderService)->SendBulkEmail($email_list, $request->subject, $request->description);
            } catch (\Exception $e) {
                info($e->getMessage());
            }

            $notification = __('Mail Sent Successfully');
            $notification = ['message' => $notification, 'alert-type' => 'success'];
        } else {
            $notification = __('The email cannot be sent because no subscribers were found.');
            $notification = ['message' => $notification, 'alert-type' => 'error'];
        }

        return redirect()->back()->with($notification);
    }

    public function subscriber_content() {
        checkAdminHasPermissionAndThrowException('newsletter.content.view');
        $code = request('code') ?? getSessionLanguage();

        if (!Language::where('code', $code)->exists()) {
            abort(404);
        }

        $subscriber_content = SubscriberContent::with('translation')->first();
        $languages = allLanguages();

        return view('newsletter::content', compact('subscriber_content', 'code', 'languages'));
    }
    public function subscriber_content_update(Request $request) {
        checkAdminHasPermissionAndThrowException('newsletter.content.update');
        $request->validate(
            [
                'code'        => 'required|string|exists:languages,code',
                'image'       => 'nullable|image|max:2048',
                'title'       => 'required|string|max:255',
                'description' => 'required|string|max:1000',
            ],
            [
                'image.image'          => __('The image must be an image.'),
                'image.max'            => __('The image may not be greater than 2048 kilobytes.'),

                'title.required'       => __('The title is required.'),
                'title.string'         => __('The title must be a string.'),
                'title.max'            => __('The title may not be greater than 255 characters.'),

                'description.required' => __('The description is required.'),
                'description.string'   => __('The description must be a string.'),
                'description.max'      => __('The description must not exceed 1000 characters.'),
            ]
        );

        if (!$request->filled('code')) {
            $notification = __('Language not found!');
            $notification = ['message' => $notification, 'alert-type' => 'error'];

            return $this->redirectWithMessage(RedirectType::ERROR->value, 'admin.subscriber-content', ['code' => $request->code], $notification);
        }

        $subscriber_content = SubscriberContent::first();

        if (!$subscriber_content) {
            $subscriber_content = new SubscriberContent();
            $subscriber_content->save();
            $this->generateTranslations(TranslationModels::SubscriberContent, $subscriber_content, 'subscriber_content_id', $request);
        }
        if ($subscriber_content && $request->hasFile('image')) {
            $file_name = uploadAndOptimizeImage(
                file: $request->image,
                oldFile: $subscriber_content->image
            );
            $subscriber_content->image = $file_name;
            $subscriber_content->save();
        }
        $subscriber_content->update($request->except('image'));

        $this->updateTranslations($subscriber_content, $request, $request->except('image'));

        return $this->redirectWithMessage(RedirectType::UPDATE->value, 'admin.subscriber-content', ['code' => $request->code]);
    }
}
