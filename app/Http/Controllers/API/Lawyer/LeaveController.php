<?php

namespace App\Http\Controllers\API\Lawyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LeaveController extends Controller {
    public function index(): JsonResponse {
        $lawyer = auth()->guard('lawyer_api')->user();
        $leaves = $lawyer->leaves()->orderByDesc('date')->paginate(10);
        return response()->json(['status' => 'success', 'data' => $leaves], 200);
    }

    public function store(Request $request): JsonResponse {
        $validator = Validator::make($request->all(), ['date' => 'required|unique:leaves,date', 'reason' => 'required'], ['date.required' => __('Date is required'), 'date.unique' => __('You have already submitted a leave request for this day.'), 'reason.required' => __('Reason is required')]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()], 422);
        }
        $lawyer = auth()->guard('lawyer_api')->user();
        $lawyer->leaves()->create($request->only(['date', 'reason']));
        return response()->json(['status' => 'success', 'message' => __('Created Successfully')], 201);
    }

    public function update(Request $request, $id): JsonResponse {
        $lawyer = auth()->guard('lawyer_api')->user();
        $leave = $lawyer->leaves()->where('id', $id)->first();
        if (!$leave) {
            return response()->json(['status' => 'error', 'message' => __('Not Found!')], 404);
        }
        if ($leave->status) {
            return response()->json(['status' => 'error', 'message' => __('You can not update approved leave request')], 404);
        }

        $validator = Validator::make($request->all(), ['date' => 'required|unique:leaves,date,' . $id . ',id', 'reason' => 'required'], ['date.required' => __('Date is required'), 'date.unique' => __('You have already submitted a leave request for this day.'), 'reason.required' => __('Reason is required')]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()], 422);
        }

        $leave->update($request->only(['date', 'reason']));
        return response()->json(['status' => 'success', 'message' => __('Updated successfully')], 200);
    }

    public function destroy($id) {
        $lawyer = auth()->guard('lawyer_api')->user();
        $leave = $lawyer->leaves()->where('id', $id)->first();
        if (!$leave) {
            return response()->json(['status' => 'error', 'message' => __('Not Found!')], 404);
        }
        if ($leave->status) {
            return response()->json(['status' => 'error', 'message' => __('You can not delete approved leave request')], 404);
        }
        $leave->delete();
        return response()->json(['status' => 'success', 'message' => __('Deleted Successfully')], 200);
    }
}
