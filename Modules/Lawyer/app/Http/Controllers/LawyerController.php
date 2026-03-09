<?php

namespace Modules\Lawyer\app\Http\Controllers;

use App\Enums\RedirectType;
use App\Http\Controllers\Controller;
use App\Traits\GlobalMailTrait;
use App\Traits\RedirectHelperTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Modules\Lawyer\app\Http\Requests\LawyerRequest;
use Modules\Lawyer\app\Models\Department;
use Modules\Lawyer\app\Models\Lawyer;
use Modules\Lawyer\app\Models\Location;
use Modules\Language\app\Enums\TranslationModels;
use Modules\Language\app\Models\Language;
use Modules\Language\app\Traits\GenerateTranslationTrait;

class LawyerController extends Controller {
    use GenerateTranslationTrait, RedirectHelperTrait, GlobalMailTrait;

    public function index(Request $request) {
        checkAdminHasPermissionAndThrowException('lawyer.view');
        $query = Lawyer::query();

        $query->when($request->filled('keyword'), function ($qa) use ($request) {
            $qa->whereHas('translations', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->keyword . '%');
                $q->orWhere('designations', 'like', '%' . $request->keyword . '%');
                $q->orWhere('about', 'like', '%' . $request->keyword . '%');
                $q->orWhere('address', 'like', '%' . $request->keyword . '%');
                $q->orWhere('educations', 'like', '%' . $request->keyword . '%');
                $q->orWhere('experience', 'like', '%' . $request->keyword . '%');
                $q->orWhere('qualifications', 'like', '%' . $request->keyword . '%');
            });
        });

        $query->when($request->filled('show_homepage'), function ($q) use ($request) {
            $q->where('show_homepage', $request->show_homepage);
        });

        $query->when($request->filled('status'), function ($q) use ($request) {
            if ($request->status == 1) {
                $q->where('status', $request->status)->verify();
            } else {
                $q->where('status', $request->status)->orWhere('email_verified_at', null);
            }
        });

        $orderBy = $request->filled('order_by') && $request->order_by == 1 ? 'asc' : 'desc';

        if ($request->filled('par-page')) {
            $lawyers = $request->get('par-page') == 'all' ? $query->orderBy('id', $orderBy)->get() : $query->orderBy('id', $orderBy)->paginate($request->get('par-page'))->withQueryString();
        } else {
            $lawyers = $query->orderBy('id', $orderBy)->paginate()->withQueryString();
        }

        return view('lawyer::lawyer.index', compact('lawyers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        checkAdminHasPermissionAndThrowException('lawyer.create');
        $departments = Department::with('translation')->active()->get();
        $locations = Location::with('translation')->active()->get();

        return view('lawyer::lawyer.create', compact('departments', 'locations'));
    }

    public function store(LawyerRequest $request): RedirectResponse {
        checkAdminHasPermissionAndThrowException('lawyer.store');
        try {
            DB::beginTransaction();
            $lawyer = Lawyer::create($request->validated());

            $lawyer->password = Hash::make($request->password);

            [$subject, $message] = $this->fetchEmailTemplate('lawyer_login', ['lawyer_name' => $lawyer->name, 'email' => $lawyer->email, 'password' => $request->password, 'login_url' => route('login', ["type" => 'lawyer'])]);
            if (app()->isProduction()) {
                $this->sendMail($lawyer->email, $subject, $message);
            }

            if ($lawyer && $request->hasFile('lawyer_image')) {
                $file_name = uploadAndOptimizeImage(
                    file: $request->lawyer_image,
                    resize: [500, 500]
                );
                $lawyer->image = $file_name;
            }
            $lawyer->email_verified_at = now();
            $lawyer->save();

            $this->generateTranslations(
                TranslationModels::Lawyer,
                $lawyer,
                'lawyer_id',
                $request,
            );

            DB::commit();

            return $this->redirectWithMessage(
                RedirectType::CREATE->value,
                'admin.lawyer.edit',
                [
                    'lawyer' => $lawyer->id,
                    'code'   => allLanguages()->first()->code,
                ]
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleMailException($e);
        }
    }

    public function edit($id) {
        checkAdminHasPermissionAndThrowException('lawyer.edit');
        $code = request('code') ?? getSessionLanguage();
        if (!Language::where('code', $code)->exists()) {
            abort(404);
        }
        $lawyer = Lawyer::findOrFail($id);
        $departments = Department::with('translation')->get();
        $locations = Location::with('translation')->get();
        $languages = allLanguages();

        return view('lawyer::lawyer.edit', compact('lawyer', 'code', 'departments', 'locations', 'languages'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LawyerRequest $request, $id) {
        checkAdminHasPermissionAndThrowException('lawyer.update');
        $validatedData = $request->validated();

        $lawyer = Lawyer::findOrFail($id);

        $lawyer->update($validatedData);

        if ($request->has('password')) {
            $lawyer->password = Hash::make($request->password);
        }

        if ($lawyer && $request->hasFile('lawyer_image')) {
            $file_name = uploadAndOptimizeImage(
                file: $request->lawyer_image,
                oldFile: $lawyer->image,
                resize: [500, 500]
            );
            $lawyer->image = $file_name;
        }
        $lawyer->save();

        $this->updateTranslations(
            $lawyer,
            $request,
            $validatedData,
        );

        return $this->redirectWithMessage(
            RedirectType::UPDATE->value,
            'admin.lawyer.edit',
            ['lawyer' => $lawyer->id, 'code' => $request->code]
        );
    }

    public function destroy($id) {
        checkAdminHasPermissionAndThrowException('lawyer.delete');

        $lawyer = Lawyer::findOrFail($id);

        if ($lawyer->appointments()->count() > 0) {
            return redirect()->back()->with(['alert-type' => 'error', 'message' => __('Cannot delete, lawyer has appointments.')]);
        }
        $lawyer->translations()->each(function ($translation) {
            $translation->lawyer()->dissociate();
            $translation->delete();
        });

        if ($lawyer->image) {
            if (File::exists(public_path($lawyer->image))) {
                unlink(public_path($lawyer->image));
            }
        }

        $lawyer->delete();

        return $this->redirectWithMessage(RedirectType::DELETE->value, 'admin.lawyer.index');
    }

    public function statusUpdate($id) {
        checkAdminHasPermissionAndThrowException('lawyer.update');

        $lawyer = Lawyer::find($id);
        $status = $lawyer->status == 1 ? 0 : 1;
        $lawyer->update(['status' => $status]);

        $notification = __('Updated successfully');

        return response()->json([
            'success' => true,
            'message' => $notification,
        ]);
    }

    public function send_verify_request($id) {
        try {
            DB::beginTransaction();
            $lawyer = Lawyer::where('email_verified_at', null)->find($id);
            if ($lawyer) {
                $lawyer->email_verified_token = Str::random(100);
                $lawyer->save();

                [$subject, $message] = $this->fetchEmailTemplate('user_verification', ['user_name' => $lawyer->name]);
                $link = [__('CONFIRM YOUR EMAIL') => route('lawyer.verification', $lawyer->email_verified_token)];
                $this->sendMail($lawyer->email, $subject, $message, $link);

                $notification = __('A verification link has been send to user mail');
                $notification = ['message' => $notification, 'alert-type' => 'success'];
            } else {
                $notification = __('Email already verified');
                $notification = ['message' => $notification, 'alert-type' => 'warning'];
            }
            return redirect()->back()->with($notification);
        } catch (\Exception $e) {
            DB::rollBack();
            info($e->getMessage());
            return $this->handleMailException($e);
        }
    }

    public function send_verify_request_to_all() {
        try {
            DB::beginTransaction();
            $lawyers = Lawyer::where('email_verified_at', null)->get();
            if ($lawyers->count()) {
                foreach ($lawyers as $lawyer) {
                    $lawyer->email_verified_token = Str::random(100);
                    $lawyer->save();

                    [$subject, $message] = $this->fetchEmailTemplate('user_verification', ['user_name' => $lawyer->name]);
                    $link = [__('CONFIRM YOUR EMAIL') => route('lawyer.verification', $lawyer->email_verified_token)];
                    $this->sendMail($lawyer->email, $subject, $message, $link);
                }
                $notification = __('A verification link has been send to user mail');
                $notification = ['message' => $notification, 'alert-type' => 'success'];
            } else {
                $notification = __('Email already verified');
                $notification = ['message' => $notification, 'alert-type' => 'warning'];
            }

            return redirect()->back()->with($notification);
        } catch (\Exception $e) {
            DB::rollBack();
            info($e->getMessage());
            return $this->handleMailException($e);
        }
    }
}
