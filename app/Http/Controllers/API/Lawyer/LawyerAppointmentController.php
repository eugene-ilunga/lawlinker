<?php

namespace App\Http\Controllers\API\Lawyer;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Appointment\app\Models\Appointment;
use Modules\Day\app\Models\Day;
use Modules\Schedule\app\Models\Schedule;

class LawyerAppointmentController extends Controller {
    public function days(): JsonResponse {
        $code = strtolower(request()->query('language', 'en'));
        $days = Day::select('id', 'slug', 'status')->with(['translations' => function ($q) use ($code) {
            $q->select('day_id', 'title')->where('lang_code', $code);
        }])->active()->get();
        if ($days->count()) {
            return response()->json(['status' => 'success', 'data' => $days], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'Not Found!'], 404);
    }

    public function todayAppointment(): JsonResponse {
        $lawyer = auth()->guard('lawyer_api')->user();

        $appointments = Appointment::with(['user:id,name,email,image', 'user.details', 'schedule'])->where(['lawyer_id' => $lawyer?->id, 'date' => date('Y-m-d')])->paymentSuccess()->notTreated()->latest()->paginate(10);

        if ($appointments) {
            return response()->json(['status' => 'success', 'data' => $appointments], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'Not Found!'], 404);
    }
    public function newAppointment(): JsonResponse {
        $lawyer = auth()->guard('lawyer_api')->user();
        $appointments = Appointment::with(['user:id,name,email,image', 'user.details', 'schedule'])
            ->where('lawyer_id', $lawyer?->id)->paymentSuccess()->notTreated()->latest()->paginate(10);

        if ($appointments->count()) {
            return response()->json(['status' => 'success', 'data' => $appointments], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'Not Found!'], 404);
    }

    public function allAppointment() {
        $lawyer = auth()->guard('lawyer_api')->user();

        $code = strtolower(request()->query('language', 'en'));

        $appointments = Appointment::with(['user:id,name,email,image', 'user.details', 'schedule', 'schedule.lawyer:id,name,email', 'schedule.day:id', 'schedule.day.translations' => function ($query) use ($code) {
            $query->where('lang_code', $code)->select('day_id', 'title');
        }])->where('lawyer_id', $lawyer?->id)->latest()->paginate(10);

        $treated = Appointment::where('lawyer_id', $lawyer?->id)->paymentSuccess()->treated()->count();
        $notTreated = Appointment::where('lawyer_id', $lawyer?->id)->paymentSuccess()->notTreated()->count();
        if ($appointments->count()) {
            return response()->json(['status' => 'success', 'data' => ['appointments' => $appointments, 'treated' => $treated, 'notTreated' => $notTreated]], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'Not Found!'], 404);

    }
    public function clientOldAppointment($client_id) {
        $lawyer = auth()->guard('lawyer_api')->user();

        $appointments = Appointment::with(['lawyer:id,name,email,image', 'user:id,name,email,image', 'user.details', 'schedule'])->where(['user_id' => $client_id, 'lawyer_id' => $lawyer?->id])->treated()->latest()->paginate(10);

        if ($appointments->count()) {
            return response()->json(['status' => 'success', 'data' => $appointments], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'Not Found!'], 404);
    }

    public function treatment(Request $request, $appointment_id): JsonResponse {
        $lawyer = auth()->guard('lawyer_api')->user();
        $appointment = Appointment::find($appointment_id);

        if (!$appointment) {
            return response()->json(['status' => 'error', 'message' => __('Appointment not found.')], 404);
        }
        $validator = Validator::make($request->all(), [
            'subject'     => 'required|string|max:255',
            'description' => 'required',
            'files'       => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,txt,jpg,jpeg,png,webp|max:5240',
        ], [
            'subject.required'     => __('The subject is required.'),
            'subject.string'       => __('The subject must be a string.'),
            'subject.max'          => __('The subject may not be greater than 255 characters.'),

            'description.required' => __('Description is required.'),

            'files.file'           => __('Each attachment must be a valid file.'),
            'files.mimes'          => __('Each file must be one of the following types: pdf, doc, docx, xls, xlsx, txt, jpg, jpeg, png, webp.'),
            'files.max'            => __('Each file may not be greater than 5MB.'),
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()], 422);
        }

        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()], 422);
        }

        if ($appointment->payment_status != 1) {
            return response()->json(['status' => 'error', 'message' => __('Payment is not completed.')], 400);
        }

        if ($appointment->already_treated == 1) {
            return response()->json(['status' => 'error', 'message' => __('This appointment has already been Consulted .')], 400);
        }

        if ($appointment->lawyer_id != $lawyer->id) {
            return response()->json(['status' => 'error', 'message' => __('Unauthorized action.')], 403);
        }

        try {
            // Update appointment details
            $appointment->subject = $request->subject;
            $appointment->description = $request->description;
            $appointment->already_treated = 1;
            $appointment->save();

            if ($request->hasFile('files')) {
                $file_name = file_upload_storage_folder($request->file('files'));
                $appointment->documents()->create(['path' => $file_name]);
            }

            return response()->json(['status' => 'success', 'message' => __('Consultation created successfully.')], 201);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => __('An error occurred while processing the Consultation.')], 500);
        }
    }
    public function updateTreatment(Request $request, $appointment_id): JsonResponse {
        $lawyer = auth()->guard('lawyer_api')->user();
        $appointment = Appointment::find($appointment_id);

        if (!$appointment || $appointment->already_treated != 1) {
            return response()->json(['status' => 'error', 'message' => __('Appointment not found or not Consulted yet.')], 404);
        }

        if ($appointment->lawyer_id != $lawyer->id) {
            return response()->json(['status' => 'error', 'message' => __('Unauthorized action.')], 403);
        }

        $validator = Validator::make($request->all(), [
            'subject'     => 'required|string|max:255',
            'description' => 'required',
            'files'       => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,txt,jpg,jpeg,png,webp|max:5240',
        ], [
            'subject.required'     => __('The subject is required.'),
            'subject.string'       => __('The subject must be a string.'),
            'subject.max'          => __('The subject may not be greater than 255 characters.'),

            'description.required' => __('Description is required.'),

            'files.file'           => __('Each attachment must be a valid file.'),
            'files.mimes'          => __('Each file must be one of the following types: pdf, doc, docx, xls, xlsx, txt, jpg, jpeg, png, webp.'),
            'files.max'            => __('Each file may not be greater than 5MB.'),
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()], 422);
        }

        try {
            // Update appointment details
            $appointment->subject = $request->subject;
            $appointment->description = $request->description;
            $appointment->save();

            if ($request->hasFile('files')) {
                $file_name = file_upload_storage_folder($request->file('files'));
                $appointment->documents()->create(['path' => $file_name]);
            }

            return response()->json(['status' => 'success', 'message' => __('Consultation updated successfully.')], 200);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => __('An error occurred while updating the Consultation.')], 500);
        }
    }
    public function appointmentDocuments($appointment_id) {
        $lawyer_id = auth()->guard('lawyer_api')->user()->id;

        $appointment = Appointment::where([
            'id'        => $appointment_id,
            'lawyer_id' => $lawyer_id,
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
    public function deleteDocument($appointment_id, $id) {
        $lawyer_id = auth()->guard('lawyer_api')->user()->id;

        $appointment = Appointment::where([
            'id'        => $appointment_id,
            'lawyer_id' => $lawyer_id,
        ])->first();

        if (!$appointment) {
            return response()->json(['status' => 'error', 'message' => __('Appointment not found!')], 404);
        }

        $document = $appointment->documents()->where('id', $id)->first();

        if (!$document) {
            return response()->json(['status' => 'error', 'message' => __('Document not found!')], 404);
        }

        // Optionally delete the DB record
        $document->deleteDocuments();
        $document->delete();

        return response()->json(['status' => 'success', 'message' => __('Deleted Successfully')], 200);
    }
    public function downloadDocument($appointment_id, $path) {
        $lawyer_id = auth()->guard('lawyer_api')->user()->id;
        Appointment::where(['id' => $appointment_id, 'lawyer_id' => $lawyer_id])->firstOrFail();
        $path = storage_path("app/public/uploads/{$path}");
        if (!file_exists($path)) {
            return response()->json(['message' => 'File not found.'], 404);
        }
        return response()->download($path);
    }

    public function printPrescription($id) {
        $user = auth()->guard('lawyer_api')->user();
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => __('Unauthorized')], 401);
        }
        $appointment = $user->appointments()->treated()->find($id);

        if ($appointment) {
            $data = [
                'appointment' => $appointment,
            ];

            $pdf = Pdf::loadView('prescription', $data)->setPaper('a4', 'landscape');
            return $pdf->download('prescription_' . $appointment->id . '.pdf');
        } else {
            return response()->json(['status' => 'error', 'message' => __('No appointment found.')], 404);
        }
    }

    public function paymentHistory(): JsonResponse {
        $currency_code = strtoupper(request()->query('currency', 'USD'));
        $endDate = Carbon::now(); // Current date and time
        $startDate = $endDate->copy()->subDays(30); // Copy and subtract 30 days

        $startDateFormatted = $startDate->format('Y-m-d H:i:s'); // Start of the date range
        $endDateFormatted = $endDate->format('Y-m-d H:i:s'); // End of the date range (including current time)

        $lawyer = auth()->guard('lawyer_api')->user();
        $query = Appointment::query();
        $query->where('lawyer_id', $lawyer?->id)->paymentSuccess();

        $payment = $query->whereBetween('created_at', [$startDateFormatted, $endDateFormatted])->sum('appointment_fee_usd');
        $total_appointment = $query->whereBetween('created_at', [$startDateFormatted, $endDateFormatted])->count();

        $appointments = $query->with(['user:id,name,email,image', 'user.details'])->latest()->paginate(10);
        if ($appointments) {
            return response()->json(['status' => 'success', 'data' => [
                'last_thirty_days_total_earning' => apiCurrency($currency_code, $payment ?? 0), //currency()
                'last_thirty_days_total_client'  => $total_appointment ?? 0,
                'appointments'                   => $appointments,
            ]], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'Not Found!'], 404);

    }
    public function schedule(): JsonResponse {
        $code = strtolower(request()->query('language', 'en'));
        $lawyer = auth()->guard('lawyer_api')->user();

        $days = Day::with(['translations' => function ($q) use ($code) {
            $q->where('lang_code', $code)->select('day_id', 'title');
        }])->select('id')->active()->get();

        $schedules = Schedule::select('day_id', 'start_time', 'end_time')
            ->where('lawyer_id', $lawyer?->id)
            ->active()
            ->get()
            ->groupBy('day_id');

        $scheduleData = $days->map(function ($day) use ($schedules) {
            $times = $schedules->get($day->id);
            if ($times) {
                return [
                    'day'  => $day->translations->first()?->title,
                    'time' => $times->map(function ($time) {
                        return strtoupper($time->start_time) . ' - ' . strtoupper($time->end_time);
                    })->toArray(),
                ];
            }
            return null;
        })->filter()->values();

        if ($scheduleData->isNotEmpty()) {
            return response()->json(['status' => 'success', 'data' => $scheduleData], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'No schedules found.'], 404);
    }

}
