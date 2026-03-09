<?php

namespace Modules\Service\app\Http\Controllers;

use App\Enums\RedirectType;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\RedirectHelperTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Http\RedirectResponse;
use Modules\Service\app\Models\Service;
use Modules\Language\app\Models\Language;
use Modules\Service\app\Models\ServiceImage;
use Modules\Language\app\Enums\TranslationModels;
use Modules\Service\app\Http\Requests\ServiceRequest;
use Modules\Language\app\Traits\GenerateTranslationTrait;

class ServiceController extends Controller
{
    use GenerateTranslationTrait, RedirectHelperTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        checkAdminHasPermissionAndThrowException('service.view');
        $query = Service::query();

        $query->when($request->filled('keyword'), function ($qa) use ($request) {
            $qa->whereHas('translations', function ($q) use ($request) {
                $q->where('title', 'like', '%'.$request->keyword.'%');
                $q->orWhere('description', 'like', '%'.$request->keyword.'%');
                $q->orWhere('sort_description', 'like', '%'.$request->keyword.'%');
            });
        });

        $query->when($request->filled('show_homepage'), function ($q) use ($request) {
            $q->where('show_homepage', $request->show_homepage);
        });

        $query->when($request->filled('status'), function ($q) use ($request) {
            $q->where('status', $request->status);
        });

        $orderBy = $request->filled( 'order_by' ) && $request->order_by == 1 ? 'asc' : 'desc';

        if ($request->filled('par-page')) {
            $services = $request->get('par-page') == 'all' ? $query->with('translation')->orderBy( 'slug', $orderBy )->get() : $query->with('translation')->orderBy( 'slug', $orderBy )->paginate($request->get('par-page'))->withQueryString();
        } else {
            $services = $query->with('translation')->orderBy( 'slug', $orderBy )->paginate(10)->withQueryString();
        }

        return view('service::index', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        checkAdminHasPermissionAndThrowException('service.create');
        return view('service::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ServiceRequest $request)
    {
        checkAdminHasPermissionAndThrowException('service.store');
        $service = Service::create(array_merge($request->validated()));

        if ($service && $request->hasFile('image')) {
            $large_name  = uploadAndOptimizeImage(
                file: $request->image,
                resize: [730,486]
            );

            $small_name = uploadAndOptimizeImage(
                file: $request->image,
                resize: [175,116]
            );

            ServiceImage::create([
                'service_id'=>$service->id,
                'small_image'=>$small_name,
                'large_image'=>$large_name
            ]);
        }
        if ($service && $request->hasFile('icon')) {
            $service->icon = $request->icon;
            $service->save();
        }

        $this->generateTranslations(
            TranslationModels::Service,
            $service,
            'service_id',
            $request,
        );

        return $this->redirectWithMessage(
            RedirectType::CREATE->value,
            'admin.service.edit',
            [
                'service' => $service->id,
                'code' => allLanguages()->first()->code,
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {
        checkAdminHasPermissionAndThrowException('service.edit');
        $code = request('code') ?? getSessionLanguage();
        if (! Language::where('code', $code)->exists()) {
            abort(404);
        }
        $languages = allLanguages();

        return view('service::edit', compact('service', 'code', 'languages'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ServiceRequest $request, $id): RedirectResponse
    {
        checkAdminHasPermissionAndThrowException('service.update');
        $validatedData = $request->validated();

        $service = Service::findOrFail($id);
        $service->update($request->except('image','icon'));


        if ($service && ! empty($request->image)) {
            $file_name = file_upload($request->image, 'uploads/custom-images/', $service->image);
            $service->image = $file_name;
            $service->save();
        }
        if ($service && ! empty($request->icon)) {
            $service->icon = $request->icon;
            $service->save();
        }

        $this->updateTranslations(
            $service,
            $request,
            $validatedData,
        );

        return $this->redirectWithMessage(
            RedirectType::UPDATE->value,
            'admin.service.edit',
            ['service' => $service->id, 'code' => $request->code]
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        checkAdminHasPermissionAndThrowException('service.delete');

        Service::findOrFail($id)->delete();

        return $this->redirectWithMessage(RedirectType::DELETE->value, 'admin.service.index');
    }

    public function statusUpdate($id)
    {
        checkAdminHasPermissionAndThrowException('service.update');

        $service = Service::find($id);
        $status = $service->status == 1 ? 0 : 1;
        $service->update(['status' => $status]);

        $notification = __('Updated successfully');

        return response()->json([
            'success' => true,
            'message' => $notification,
        ]);
    }
}
