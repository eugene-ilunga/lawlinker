<?php

namespace Modules\Lawyer\app\Http\Controllers;

use App\Enums\RedirectType;
use App\Traits\RedirectHelperTrait;
use Modules\Leave\app\Models\Leave;
use App\Http\Controllers\Controller;

class LeaveController extends Controller {
    use RedirectHelperTrait;
    /**
     * Display a listing of the resource.
     */
    public function index() {
        checkAdminHasPermissionAndThrowException('leave.management');
        $leaves = Leave::orderByDesc('date')->paginate(10);
        return view('lawyer::leave.index', compact('leaves'));
    }
    public function show($id) {
        checkAdminHasPermissionAndThrowException('leave.management');
        $leave = Leave::find($id);
        return view('lawyer::leave.show', compact('leave'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {
        checkAdminHasPermissionAndThrowException('leave.management');
        $leave = Leave::find($id);
        $leave->delete();
        return $this->redirectWithMessage(RedirectType::DELETE->value);
    }

    public function statusUpdate($id) {
        checkAdminHasPermissionAndThrowException('leave.management');
        $item = Leave::find($id);
        $status = $item->status == 1 ? 0 : 1;
        $item->update(['status' => $status]);
        $notification = __('Updated successfully');
        return response()->json([
            'success' => true,
            'message' => $notification,
        ]);
    }
}
