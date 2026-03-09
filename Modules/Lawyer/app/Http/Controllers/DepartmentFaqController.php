<?php

namespace Modules\Lawyer\app\Http\Controllers;

use App\Enums\RedirectType;
use App\Http\Controllers\Controller;
use App\Traits\RedirectHelperTrait;
use Modules\Lawyer\app\Http\Requests\DepartmentFaqRequest;
use Modules\Lawyer\app\Models\Department;
use Modules\Lawyer\app\Models\DepartmentFaq;
use Modules\Language\app\Enums\TranslationModels;
use Modules\Language\app\Traits\GenerateTranslationTrait;

class DepartmentFaqController extends Controller {use GenerateTranslationTrait, RedirectHelperTrait;

    public function index($slug) {
        checkAdminHasPermissionAndThrowException('department.view');

        $department = Department::whereSlug($slug)->first();
        if (!$department) {
            abort(404);
        }

        $faqs = DepartmentFaq::where('department_id', $department->id)->latest()->paginate(15);
        $code = request('code') ?? getSessionLanguage();
        $languages = allLanguages();

        return view('lawyer::department.utilities.faq', compact('faqs', 'department', 'code', 'languages'));
    }

    public function store(DepartmentFaqRequest $request) {
        checkAdminHasPermissionAndThrowException('department.store');

        $faq = DepartmentFaq::create($request->validated());

        $this->generateTranslations(
            TranslationModels::DepartmentFaq,
            $faq,
            'department_faq_id',
            $request,
        );

        return $this->redirectWithMessage(RedirectType::CREATE->value);
    }

    public function update(DepartmentFaqRequest $request, $id) {
        checkAdminHasPermissionAndThrowException('department.update');

        $faq = DepartmentFaq::findOrFail($id);

        $validatedData = $request->validated();

        $faq->update($validatedData);

        $this->updateTranslations(
            $faq,
            $request,
            $validatedData,
        );

        return $this->redirectWithMessage(RedirectType::UPDATE->value);
    }

    public function destroy($id) {
        checkAdminHasPermissionAndThrowException('department.delete');

        $faq = DepartmentFaq::findOrFail($id);

        $faq->translations()->each(function ($translation) {
            $translation->department_faq()->dissociate();
            $translation->delete();
        });

        $faq->delete();

        return $this->redirectWithMessage(RedirectType::DELETE->value);
    }

    public function statusUpdate($id) {
        checkAdminHasPermissionAndThrowException('department.update');

        $faq = DepartmentFaq::find($id);
        $status = $faq->status == 1 ? 0 : 1;
        $faq->update(['status' => $status]);

        $notification = __('Updated successfully');

        return response()->json([
            'success' => true,
            'message' => $notification,
        ]);
    }}
