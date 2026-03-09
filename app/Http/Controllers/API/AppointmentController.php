<?php

namespace App\Http\Controllers\API;

use App\Models\ShoppingCart;
use Illuminate\Http\Request;
use Modules\Day\app\Models\Day;
use Illuminate\Http\JsonResponse;
use Modules\Leave\app\Models\Leave;
use App\Http\Controllers\Controller;
use Modules\Lawyer\app\Models\Lawyer;
use Illuminate\Support\Facades\Validator;
use Modules\Lawyer\app\Models\Department;
use Modules\Schedule\app\Models\Schedule;
use Modules\Appointment\app\Models\Appointment;

class AppointmentController extends Controller {
    public function getDepartmentLawyer($department_id): JsonResponse {
        $lawyers = Lawyer::where(['department_id' => $department_id])->active()->verify()->paid()->get();
        if ($lawyers) {
            return response()->json(['status' => 'success', 'data' => $lawyers], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'Not Found!'], 404);
    }
    public function getAppointmentSchedule(Request $request): JsonResponse {
        $validator = Validator::make($request->all(), [
            'lawyer_id' => 'required',
            'date'      => 'required',
        ], [
            'lawyer_id.required' => __('Lawyer is required'),
            'date.required'      => __('Date is required'),
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()], 422);
        }
        $leave = Leave::where(['lawyer_id' => $request->lawyer_id, 'date' => $request->date])->count();

        if ($leave == 0) {
            $lawyer_id = $request->lawyer_id;
            $day_name = date('l', strtotime($request->date));
            $day = Day::where('slug', strtolower($day_name))->first();
            $schedules = Schedule::where(['lawyer_id' => $lawyer_id, 'day_id' => $day?->id])->get();

            if ($schedules->count() != 0) {
                return response()->json(['status' => 'success', 'data' => $schedules], 200);
            } else {
                return response()->json(['status' => 'error', 'message' => __('Schedule Not Found')], 404);
            }
        } else {
            return response()->json(['status' => 'error', 'message' => __('Lawyer is unavailable on the selected date')], 404);
        }
    }
    public function createAppointment(Request $request): JsonResponse {
        $currency_code = strtoupper(request()->query('currency', 'USD'));
        $validator = Validator::make($request->all(), [
            'lawyer_id'     => 'required',
            'department_id' => 'required',
            'date'          => 'required',
            'schedule_id'   => 'required',
        ], [
            'lawyer_id.required'     => __('Lawyer is required'),
            'department_id.required' => __('The department is required.'),
            'date.required'          => __('Date is required'),
            'schedule_id.required'   => __('Schedule is required'),
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()], 422);
        }
        $user = auth()->guard('api')->user();
        $lawyer_id = $request->lawyer_id;
        $department_id = $request->department_id;
        $date = $request->date;
        $schedule_id = $request->schedule_id;

        $schedule = Schedule::find($schedule_id);
        $lawyer = Lawyer::where(['id' => $lawyer_id, 'department_id' => $department_id])->first();
        $department = Department::find($department_id);

        // Check if lawyer, department, or schedule is found
        if (!$lawyer) {
            return response()->json(['status' => 'error', 'message' => __('Lawyer Not Found')], 404);
        } elseif (!$department) {
            return response()->json(['status' => 'error', 'message' => __('Department not found')], 404);
        } elseif (!$schedule) {
            return response()->json(['status' => 'error', 'message' => __('Schedule not found')], 404);
        }

        $exist_item = ShoppingCart::where(['user_id' => $user->id, 'lawyer_id' => $lawyer_id, 'schedule_id' => $schedule_id, 'date' => $date])->count();

        if($exist_item > 0) {
            return response()->json(['status' => 'error', 'message' => __('Item is already in the cart.')], 403);
        }

        $item = new ShoppingCart();
        $item->user_id = $user->id;
        $item->name = $lawyer->name;
        $item->lawyer_id = $lawyer_id;
        $item->department_id = $department->id;
        $item->qty = 1;
        $item->price = $lawyer->fee;
        $item->location_id = $lawyer->location->id ?? null;
        $item->date = $date;
        $item->time = $schedule->start_time . '-' . $schedule->end_time;
        $item->schedule_id = $schedule->id;
        $item->day_id = $schedule->day_id;
        $item->save();

        // Wrap the price with the currency conversion function
        $item->price = apiCurrency($currency_code, $item->price ?? 0);

        // Return success response
        return response()->json(['status' => 'success', 'message' => __('Appointment Created Successfully'), 'data' => $item], 201);
    }
    public function totalAppointment(): JsonResponse {
        $user = auth()->guard('api')->user();
        $currency_code = strtoupper(request()->query('currency', 'USD'));

        $totalAppointment =  ShoppingCart::where('user_id', $user->id)->get();
        $sub_total = $totalAppointment->sum('price');
        $count = $totalAppointment->sum('qty');

        // Wrap the price with apiCurrency for each item in totalAppointment
        $totalAppointment->transform(function ($item) use ($currency_code) {
            $item->price = apiCurrency($currency_code, $item->price ?? 0);
            return $item;
        });
        if ($totalAppointment) {
            return response()->json(['status' => 'success', 'data' => [
                'sub_total' => apiCurrency($currency_code, $sub_total ?? 0),
                'total_qty' => $count,
                'items' => $totalAppointment,
            ]], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'Not Found!'], 404);
    }
    public function removeAppointment($id): JsonResponse {
        $user = auth()->guard('api')->user();
        $item = ShoppingCart::where(['user_id'=> $user->id,'id'=>$id])->first();
        if ($item) {
            $item->delete();
            return response()->json(['status' => 'success', 'message' => __('Deleted Successfully')], 200);
        }
        return response()->json(['status' => 'error', 'message' => __('Not Found!')], 404);
    }

    public function appointmentDocuments($appointment_id) {
        $user_id = auth()->guard('api')->user()->id;

        $appointment = Appointment::where([
            'id'        => $appointment_id,
            'user_id' => $user_id,
        ])->first();

        if (!$appointment) {
            return response()->json(['status' => 'error', 'message' => __('Appointment not found!')], 404);
        }

        $documents = $appointment->documents()->get();

        if (!count($documents)) {
            return response()->json(['status' => 'error', 'message' => __('Document not found!')], 404);
        }

        return response()->json(['status' => 'success', 'data' => $documents], 200);
    }

    public function downloadDocument($appointment_id, $path) {
        $user_id = auth()->guard('api')->user()->id;
        Appointment::where(['id' => $appointment_id, 'user_id' => $user_id])->firstOrFail();
        $path = storage_path("app/public/uploads/{$path}");
        if (!file_exists($path)) {
            return response()->json(['message' => 'File not found.'], 404);
        }
        return response()->download($path);
    }

}
