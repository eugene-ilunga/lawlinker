<?php

namespace Modules\Leave\app\Http\Controllers;

use App\Enums\RedirectType;
use App\Http\Controllers\Controller;
use App\Traits\RedirectHelperTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LeaveController extends Controller {
    use RedirectHelperTrait;
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $leaves = lawyerAuth()->leaves()->orderByDesc('date')->paginate(10);
        return view('leave::index', compact('leaves'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse {
        $request->validate(['date' => 'required|unique:leaves,date','reason' => 'required'], ['date.required' => __('Date is required'),'date.unique' => __('You have already submitted a leave request for this day.'),'reason.required' => __('Reason is required')]);
        lawyerAuth()->leaves()->create($request->only(['date', 'reason']));
        return $this->redirectWithMessage(RedirectType::CREATE->value);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse {
        $request->validate(['date' => 'required|unique:leaves,date,'.$id.',id','reason' => 'required'], ['date.required' => __('Date is required'),'date.unique' => __('You have already submitted a leave request for this day.'),'reason.required' => __('Reason is required')]);

        $leave = lawyerAuth()->leaves()->where('id', $id)->pending()->firstOrFail();
        $leave->update($request->only(['date', 'reason']));

        return $this->redirectWithMessage(RedirectType::UPDATE->value);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {
        $leave = lawyerAuth()->leaves()->where('id', $id)->pending()->firstOrFail();
        $leave->delete();
        return $this->redirectWithMessage(RedirectType::DELETE->value);
    }
}
