<?php

namespace App\Http\Controllers\API\Lawyer;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Modules\Appointment\app\Models\Appointment;
use Modules\Lawyer\app\Models\Lawyer;
use Modules\Language\app\Traits\GenerateTranslationTrait;

class ProfileController extends Controller {
    use GenerateTranslationTrait;
    public function dashboard(Request $request): JsonResponse {
        $lawyer = auth()->guard('lawyer_api')->user();
        $currency_code = strtoupper(request()->query('currency', 'USD'));

        $dataCal = [];
        $start = Carbon::now()->startOfMonth();
        $end = Carbon::now()->endOfMonth();

        if ($request->filled('year') && $request->filled('month')) {
            $year = $request->input('year');
            $month = $request->input('month');

            $start = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $end = $start->copy()->endOfMonth();
        } elseif ($request->filled('year')) {
            $year = $request->input('year');

            $start = Carbon::createFromDate($year, 1, 1)->startOfYear();
            $end = $start->copy()->endOfYear();
        }

        $data = [];

        // Weekly data aggregation
        $startOfWeek = Carbon::now()->startOfWeek(); // Use fresh instance
        $endOfWeek = Carbon::now()->endOfWeek();
        $weeklyData = array_fill_keys(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'], 0);

        $weeklyAppointments = Appointment::selectRaw("DAYNAME(created_at) as day, SUM(appointment_fee_usd) as total_price")
            ->paymentSuccess()
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->where('lawyer_id', $lawyer->id)
            ->groupBy('day')
            ->get();

        foreach ($weeklyAppointments as $appointment) {
            $day = strtolower($appointment->day);
            $weeklyData[$day] = apiCurrency($currency_code, $appointment->total_price, false);
        }
        $data['weekly_data'] = $weeklyData;

        $data['today_appointment'] = Appointment::where(['lawyer_id' => $lawyer->id, 'date' => date('Y-m-d')])->paymentSuccess()->notTreated()->get();
        $data['new_appointment'] = Appointment::where('lawyer_id', $lawyer->id)->paymentSuccess()->notTreated()->count();
        $data['success_appointment'] = Appointment::where('lawyer_id', $lawyer->id)->paymentSuccess()->treated()->count();

        $data['totalEarning'] = apiCurrency($currency_code, Appointment::paymentSuccess()->where('lawyer_id', $lawyer->id)->sum('appointment_fee_usd'), false);

        $data['oldestYear'] = Carbon::parse(Appointment::select('created_at')->where('lawyer_id', $lawyer->id)->orderBy('created_at', 'asc')->first()?->created_at)->year ?? Carbon::now()->year;
        $data['latestYear'] = Carbon::parse(Appointment::select('created_at')->where('lawyer_id', $lawyer->id)->orderBy('created_at', 'desc')->first()?->created_at)->year ?? Carbon::now()->year;

        return response()->json(['status' => 'success', 'data' => $data], 200);
    }

    public function account(): JsonResponse {
        $code = strtolower(request()->query('language', 'en'));

        $lawyer_id = auth()->guard('lawyer_api')->user()?->id;

        $lawyer = Lawyer::with(['translations' => function ($q) use ($code) {
            $q->where('lang_code', $code);
        }])->find($lawyer_id);

        return response()->json([
            'status' => 'success',
            'data'   => $lawyer,
        ], 200);
    }
    public function updateProfile(Request $request): JsonResponse {
        $lawyer = auth()->guard('lawyer_api')->user();
        // Validate request data
        $validator = Validator::make($request->all(), [
            'code'            => 'required|exists:languages,code',
            'name'            => 'sometimes|string|max:255',
            'email'           => 'sometimes|unique:lawyers,email,' . $lawyer->id,
            'phone'           => 'sometimes',
            'fee'             => 'sometimes|numeric',

            'department_id'   => 'sometimes|exists:departments,id',
            'location_id'     => 'sometimes|exists:locations,id',

            'designations'    => 'required',
            'about'           => 'required',
            'address'         => 'required',
            'educations'      => 'required',
            'experience'      => 'required',
            'qualifications'  => 'required',
            'facebook'        => 'sometimes',
            'twitter'         => 'sometimes',
            'linkedin'        => 'sometimes',
            'lawyer_image'    => 'sometimes|image|max:2048',
            'seo_title'       => 'nullable|string|max:1000',
            'seo_description' => 'nullable|string|max:2000',
            'years_of_experience' => 'required',
        ], [
            'department_id.required'  => __('The department is required.'),
            'department_id.exists'    => __('The selected department is invalid.'),

            'location_id.required'    => __('The location is required.'),
            'location_id.exists'      => __('The selected location is invalid.'),

            'fee.required'            => __('Fee is required.'),
            'fee.numeric'             => __('Fee must be a numeric.'),

            'code.required'           => __('Language is required and must be a string.'),
            'code.exists'             => __('The selected language is invalid.'),

            'seo_title.max'           => __('SEO title may not be greater than 1000 characters.'),
            'seo_title.string'        => __('SEO title must be a string.'),

            'seo_description.max'     => __('SEO description may not be greater than 2000 characters.'),
            'seo_description.string'  => __('SEO description must be a string.'),

            'lawyer_image.required'   => __('The image is required.'),
            'lawyer_image.image'      => __('The image must be an image.'),
            'lawyer_image.max'        => __('The image may not be greater than 2048 kilobytes.'),

            'name.required'           => __('Name is required'),
            'name.max'                => __('The name may not be greater than 255 characters.'),
            'name.string'             => __('The name must be a string.'),

            'phone.required'          => __('Phone number is required.'),
            'email.required'          => __('Email is required.'),
            'email.unique'            => __('Email already exist'),
            'designations.required'   => __('Designations is required.'),
            'about.required'          => __('About information is required.'),
            'address.required'        => __('Address is required.'),
            'educations.required'     => __('Educations is required.'),
            'experience.required'     => __('Experience is required.'),
            'qualifications.required' => __('Qualifications is required.'),
            'years_of_experience.required' => __('Years of experience is required.'),
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();
            // Update lawyer fields
            $validatedData = $validator->validated();
            $lawyer->update($validatedData);

            // Handle lawyer image upload
            if ($request->hasFile('lawyer_image')) {
                $file_name = uploadAndOptimizeImage(
                    file: $request->lawyer_image,
                    oldFile: $lawyer->image,
                );
                $lawyer->image = $file_name;
            }

            // Save the updated profile data
            $lawyer->save();

            // Handle translations
            $this->updateTranslations(
                $lawyer,
                $request,
                $validatedData
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

        $lawyer = auth()->guard('lawyer_api')->user();

        if (Hash::check($request->current_password, $lawyer->password)) {
            $lawyer->password = Hash::make($request->password);
            $lawyer->save();

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

}
