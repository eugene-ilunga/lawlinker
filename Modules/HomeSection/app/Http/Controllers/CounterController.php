<?php

namespace Modules\HomeSection\app\Http\Controllers;

use App\Enums\RedirectType;
use App\Traits\RedirectHelperTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Http\RedirectResponse;
use Modules\HomeSection\app\Models\Counter;
use Modules\Language\app\Models\Language;
use Modules\Language\app\Enums\TranslationModels;
use Modules\HomeSection\app\Http\Requests\CounterRequest;
use Modules\Language\app\Traits\GenerateTranslationTrait;

class CounterController extends Controller
{
    use GenerateTranslationTrait, RedirectHelperTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $counters = Counter::latest()->paginate(5)->withQueryString();
        $code = request('code') ?? getSessionLanguage();
        if (! Language::where('code', $code)->exists()) {
            abort(404);
        }
        $languages = allLanguages();
        return view('homesection::counter.index',compact('counters','languages','code'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CounterRequest $request): RedirectResponse
    {
        checkAdminHasPermissionAndThrowException('counter.store');
        $item = Counter::create($request->validated());

        $this->generateTranslations(
            TranslationModels::Counter,
            $item,
            'counter_id',
            $request,
        );

        return $this->redirectWithMessage(RedirectType::CREATE->value,'admin.counter.index',['code' => $request->code]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CounterRequest $request, $id): RedirectResponse
    {
        checkAdminHasPermissionAndThrowException('counter.update');
        $validatedData = $request->validated();

        $item = Counter::findOrFail($id);
        $item->update($validatedData);


        $this->updateTranslations($item,$request,$validatedData);

        return $this->redirectWithMessage(RedirectType::UPDATE->value,'admin.counter.index',['code' => $request->code]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        checkAdminHasPermissionAndThrowException('counter.delete');

        $item = Counter::findOrFail($id);

        $item->translations()->each(function ($translation) {
            $translation->counter()->dissociate();
            $translation->delete();
        });

        $item->delete();

        return $this->redirectWithMessage(RedirectType::DELETE->value);
    }
    public function statusUpdate($id)
    {
        checkAdminHasPermissionAndThrowException('counter.update');

        $item = Counter::find($id);
        $status = $item->status == 1 ? 0 : 1;
        $item->update(['status' => $status]);

        $notification = __('Updated successfully');

        return response()->json([
            'success' => true,
            'message' => $notification,
        ]);
    }
}
