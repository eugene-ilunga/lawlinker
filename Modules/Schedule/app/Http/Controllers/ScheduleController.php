<?php

namespace Modules\Schedule\app\Http\Controllers;

use Carbon\Carbon;
use App\Enums\RedirectType;
use Illuminate\Http\Request;
use Modules\Day\app\Models\Day;
use App\Traits\RedirectHelperTrait;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Modules\Lawyer\app\Models\Lawyer;
use Modules\Schedule\app\Models\Schedule;
use Modules\Schedule\app\Http\Requests\ScheduleRequest;

class ScheduleController extends Controller {
    use RedirectHelperTrait;
    public function index(Request $request) {
        checkAdminHasPermissionAndThrowException('schedule.view');

        // $schedules = Schedule::latest()->paginate(10)->withQueryString();
        $days = Day::get();
        $lawyers = Lawyer::orderBy('name', 'asc')->get();

        $query = Schedule::query();

        $query->when($request->filled('status'), function ($q) use ($request) {
            $q->where('status', $request->status);
        });

        $query->when($request->filled('lawyer'), function ($q) use ($request) {
            $q->where('lawyer_id', $request->lawyer);
        });

        $query->when($request->filled('day'), function ($q) use ($request) {
            $q->where('day_id', $request->day);
        });

        $orderBy = 'desc';

        if ($request->filled('par-page')) {
            $schedules = $request->get('par-page') == 'all' ? $query->orderBy('id', $orderBy)->get() : $query->orderBy('id', $orderBy)->paginate($request->get('par-page'))->withQueryString();
        } else {
            $schedules = $query->orderBy('id', $orderBy)->paginate()->withQueryString();
        }

        return view('schedule::index', compact('schedules', 'days', 'lawyers'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(ScheduleRequest $request) {
        checkAdminHasPermissionAndThrowException('schedule.store');
        $validatedData = $request->validated();
        $validatedData['start_time'] = Carbon::createFromFormat('H:i', $validatedData['start_time'])->format('g:i A');
        $validatedData['end_time'] = Carbon::createFromFormat('H:i', $validatedData['end_time'])->format('g:i A');

        Schedule::create($validatedData);
        return $this->redirectWithMessage(RedirectType::CREATE->value);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ScheduleRequest $request, $id): RedirectResponse {
        checkAdminHasPermissionAndThrowException('schedule.update');
        $validatedData = $request->validated();
        $validatedData['start_time'] = Carbon::createFromFormat('H:i', $validatedData['start_time'])->format('g:i A');
        $validatedData['end_time'] = Carbon::createFromFormat('H:i', $validatedData['end_time'])->format('g:i A');

        Schedule::findOrFail($id)->update($validatedData);
        return $this->redirectWithMessage(RedirectType::UPDATE->value);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {
        checkAdminHasPermissionAndThrowException('schedule.delete');
        Schedule::findOrFail($id)->delete();
        return $this->redirectWithMessage(RedirectType::DELETE->value);
    }

    public function statusUpdate($id) {
        checkAdminHasPermissionAndThrowException('schedule.update');

        $item = Schedule::find($id);
        $status = $item->status == 1 ? 0 : 1;
        $item->update(['status' => $status]);

        $notification = __('Updated successfully');

        return response()->json([
            'success' => true,
            'message' => $notification,
        ]);
    }
}
