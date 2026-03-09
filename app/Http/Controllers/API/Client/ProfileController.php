<?php

namespace App\Http\Controllers\API\Client;

use App\Models\UserDetails;
use Illuminate\Http\Request;
use App\Models\MeetingHistory;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Modules\Order\app\Models\Order;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Modules\Appointment\app\Models\Appointment;
use Barryvdh\DomPDF\Facade\Pdf;

class ProfileController extends Controller {
    public function dashboard() {
        $user = auth()->guard('api')->user();
        $appointments = Appointment::where('user_id', $user->id)->get();
        $orders = Order::where('user_id', $user->id)->get();
        $response = [
            'total_order'   => $orders->count(),
            'pending_appointment'     => $appointments->where('payment_status',0)->count(),
            'total_appointment' => $appointments->count(),
        ];
        return response()->json(['status' => 'success', 'data' => $response], 200);
    }
    public function account(): JsonResponse {
        $user = auth()->guard('api')->user();
        $user->load('details');
        return response()->json(['status' => 'success', 'data' => $user], 200);
    }
    public function updateProfile(Request $request): JsonResponse {
        $user = auth()->guard('api')->user();
        // Validate request data
        $validator = Validator::make($request->all(), [
            'name'       => 'required',
            'email'      => 'required|unique:users,email,' . $user->id,
            'phone'      => 'required',
            'age'        => 'required',
            'occupation' => 'required',
            'gender'     => 'required',
            'address'    => 'required',
            'country'    => 'required',
            'city'       => 'required',
        ], [
            'name.required'       => __('Name is required'),
            'email.required'      => __('Email is required'),
            'email.unique'        => __('Email already exists'),
            'phone.required'      => __('Phone is required'),
            'address.required'    => __('Address is required'),
            'age.required'        => __('Age is required'),
            'occupation.required' => __('Occupation is required'),
            'gender.required'     => __('Gender is required'),
            'country.required'    => __('Country is required'),
            'city.required'       => __('City is required'),
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->ready_for_appointment = 1;
            if ($request->hasFile('image')) {
                $file_name = uploadAndOptimizeImage(
                    file: $request->image,
                    oldFile: $user->image,
                );
                $user->image = $file_name;
            }

            $user->save();

            UserDetails::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'phone'                   => $request->phone,
                    'guardian_name'           => $request->guardian_name,
                    'guardian_phone'          => $request->guardian_phone,
                    'age'                     => $request->age,
                    'occupation'              => $request->occupation,
                    'gender'                  => $request->gender,
                    'address'                 => $request->address,
                    'country'                 => $request->country,
                    'city'                    => $request->city,
                    'date_of_birth'           => $request->date_of_birth,
                ]
            );
            DB::commit();
            return response()->json([
                'status' => 'success', 'message' => __('Your profile updated successfully')], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => __('Your profile update failed')], 500);
        }
    }

    public function updatePassword(Request $request): JsonResponse {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password'         => 'required|min:4|confirmed',
        ], [
            'current_password.required' => __('Current password is required'),
            'password.required'         => __('Password is required'),
            'password.min'              => __('Password must be at least 4 characters'),
            'password.confirmed'        => __('Confirm password does not match'),
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()], 422);
        }

        $user = auth()->guard('api')->user();

        if (Hash::check($request->current_password, $user->password)) {
            $user->password = Hash::make($request->password);
            $user->save();

            return response()->json([
                'status'  => 'success',
                'message' => __('Password changed successfully'),
            ], 200);

        } else {
            return response()->json([
                'status'  => 'error',
                'message' => __('Current password does not match'),
            ], 400);
        }
    }
    public function appointments(): JsonResponse {
        $user = auth()->guard('api')->user();
        $appointments = Appointment::with(['lawyer:id,name,email,image'])->where('user_id', $user->id)->orderBy('id', 'desc')->paginate(10);
        return response()->json(['status' => 'success', 'data' => $appointments], 200);
    }
    public function showAppointment($id): JsonResponse {
        $user = auth()->guard('api')->user();


        $appointment = Appointment::with(['user', 'user.details', 'lawyer'])
            ->where(['user_id' => $user->id, 'id' => $id])
            ->first();

        if ($appointment) {
            return response()->json(['status' => 'success', 'data' => $appointment], 200);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Appointment not found'], 404);
        }
    }
    public function printPrescription($id) {
        $user = auth()->guard('api')->user();
        if(!$user){
            return response()->json(['status' => 'error', 'message' => __('Unauthorized')], 401);
        }
        $appointment = $user->appointments()->treated()->find($id);

        if ($appointment) {
            $data = [
                'appointment'         => $appointment,
            ];
            $pdf = Pdf::loadView( 'prescription', $data )->setPaper( 'a4', 'landscape' );
            return $pdf->download('prescription_' . $appointment->id . '.pdf');
        } else {
            return response()->json(['status' => 'error', 'message' => __('No appointment found.')], 404);
        }
    }
    public function orders(): JsonResponse {
        $user = auth()->guard('api')->user();
        $orders = Order::where('user_id', $user->id)->orderBy('id', 'desc')->paginate(10);
        return response()->json(['status' => 'success', 'data' => $orders], 200);
    }
    public function showOrder($id) {
        $user = auth()->guard('api')->user();
        $order = Order::with(['appointments','appointments.lawyer:id,name,email,image','appointments.schedule'])->where(['user_id'=>$user->id,'id'=>$id])->first();
        if($order){
            return response()->json(['status' => 'success', 'data' => $order], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'Not Found!'], 404);
    }
    public function meetingHistory(): JsonResponse {
        $user = auth()->guard('api')->user();
        $now = now();
        $histories = MeetingHistory::select('id','lawyer_id','meeting_id')->with(['lawyer:id,name,email,image','meeting:topic,duration,start_time,meeting_id,password,join_url'])->where('user_id', $user->id)
            ->whereRaw('DATE_ADD(meeting_time, INTERVAL duration MINUTE) < ?', [$now])
            ->orderBy('meeting_time', 'asc')
            ->paginate(10);

        return response()->json(['status' => 'success', 'data' => $histories], 200);
    }

    public function upComingMeeting(): JsonResponse {
        $user = auth()->guard('api')->user();
        $now = now();
        $histories = MeetingHistory::select('id','lawyer_id','meeting_id')->with(['lawyer:id,name,email,image','meeting:topic,duration,start_time,meeting_id,password,join_url'])->where('user_id', $user->id)
            ->whereRaw('DATE_ADD(meeting_time, INTERVAL duration MINUTE) > ?', [$now])
            ->orderBy('meeting_time', 'asc')
            ->paginate(10);
        return response()->json(['status' => 'success', 'data' => $histories], 200);
    }


}
