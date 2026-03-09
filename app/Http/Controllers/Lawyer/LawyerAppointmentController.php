<?php

namespace App\Http\Controllers\Lawyer;

use App\Http\Controllers\Controller;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Modules\Appointment\app\Http\Requests\TreatmentRequest;
use Modules\Appointment\app\Models\Appointment;
use Modules\Day\app\Models\Day;
use Modules\Schedule\app\Models\Schedule;

class LawyerAppointmentController extends Controller {
    public function todayAppointment(Request $request) {
        $lawyer = lawyerAuth();
        $day = Day::where('slug', date('l'))->first();
        $schedules = Schedule::where(['lawyer_id' => $lawyer?->id, 'day_id' => $day->id])->get();

        $query = Appointment::query();
        $query->where(['lawyer_id' => $lawyer?->id, 'date' => Date('Y-m-d')])->paymentSuccess()->notTreated();

        $query->when($request->filled('schedule_id'), function ($q) use ($request) {
            $q->where('schedule_id', $request->schedule_id);
        });

        $orderBy = $request->filled('order_by') && $request->order_by == 1 ? 'asc' : 'desc';

        if ($request->filled('par-page')) {
            $appointments = $request->get('par-page') == 'all' ? $query->orderBy('id', $orderBy)->get() : $query->orderBy('id', $orderBy)->paginate($request->get('par-page'))->withQueryString();
        } else {
            $appointments = $query->orderBy('id', $orderBy)->paginate(10)->withQueryString();
        }
        return view('lawyer.appointment.today', compact('appointments', 'schedules'));
    }
    public function newAppointment(Request $request) {
        $query = Appointment::query();
        $query->where(['lawyer_id' => lawyerAuth()?->id])->paymentSuccess()->notTreated();

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = date('Y-m-d', strtotime($request->to_date));
            $query->whereBetween('date', array($from_date, $to_date));
        }

        $orderBy = $request->filled('order_by') && $request->order_by == 1 ? 'asc' : 'desc';

        if ($request->filled('par-page')) {
            $appointments = $request->get('par-page') == 'all' ? $query->orderBy('id', $orderBy)->get() : $query->orderBy('id', $orderBy)->paginate($request->get('par-page'))->withQueryString();
        } else {
            $appointments = $query->orderBy('id', $orderBy)->paginate(10)->withQueryString();
        }
        return view('lawyer.appointment.new', compact('appointments'));
    }
    public function allAppointment(Request $request) {
        $query = Appointment::query();
        $query->where('lawyer_id', lawyerAuth()->id);

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = date('Y-m-d', strtotime($request->to_date));
            $query->whereBetween('date', array($from_date, $to_date));
        }

        $query->when($request->filled('client'), function ($q) use ($request) {
            $q->where('user_id', $request->client);
        });

        $orderBy = $request->filled('order_by') && $request->order_by == 1 ? 'asc' : 'desc';

        if ($request->filled('par-page')) {
            $appointments = $request->get('par-page') == 'all' ? $query->orderBy('id', $orderBy)->get() : $query->orderBy('id', $orderBy)->paginate($request->get('par-page'))->withQueryString();
        } else {
            $appointments = $query->orderBy('date', $orderBy)->paginate(10)->withQueryString();
        }
        $clients = User::select('name', 'email', 'id')->whereIn('id', lawyerAuth()->appointments()->distinct()->pluck('user_id'))->active()->get();
        return view('lawyer.appointment.all', compact('appointments', 'clients'));
    }
    public function notTreatedAppointments(Request $request) {
        $query = Appointment::query();
        $query->where('lawyer_id', lawyerAuth()->id)->notTreated();

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = date('Y-m-d', strtotime($request->to_date));
            $query->whereBetween('date', array($from_date, $to_date));
        }

        $query->when($request->filled('client'), function ($q) use ($request) {
            $q->where('user_id', $request->client);
        });

        $orderBy = $request->filled('order_by') && $request->order_by == 1 ? 'asc' : 'desc';

        if ($request->filled('par-page')) {
            $appointments = $request->get('par-page') == 'all' ? $query->orderBy('id', $orderBy)->get() : $query->orderBy('id', $orderBy)->paginate($request->get('par-page'))->withQueryString();
        } else {
            $appointments = $query->orderBy('date', $orderBy)->paginate(10)->withQueryString();
        }
        $clients = User::select('name', 'email', 'id')->whereIn('id', lawyerAuth()->appointments()->distinct()->pluck('user_id'))->active()->get();
        return view('lawyer.appointment.not_treated_appointments', compact('appointments', 'clients'));
    }
    public function oldAppointment(Request $request, $id) {
        $user_id = $id;
        $query = Appointment::query();
        $query->where('user_id', $id)->treated();

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = date('Y-m-d', strtotime($request->to_date));
            $query->whereBetween('date', array($from_date, $to_date));
        }

        $orderBy = $request->filled('order_by') && $request->order_by == 1 ? 'asc' : 'desc';

        if ($request->filled('par-page')) {
            $appointments = $request->get('par-page') == 'all' ? $query->orderBy('id', $orderBy)->get() : $query->orderBy('id', $orderBy)->paginate($request->get('par-page'))->withQueryString();
        } else {
            $appointments = $query->orderBy('id', $orderBy)->paginate(10)->withQueryString();
        }
        return view('lawyer.appointment.old', compact('appointments', 'user_id'));
    }
    public function treatment($id) {
        $appointment = Appointment::where(['id' => $id, 'lawyer_id' => lawyerAuth()?->id])->paymentSuccess()->firstOrFail();

        if ($appointment->already_treated == 0) {
            return view('lawyer.appointment.treatment', compact('appointment'));
        } else {
            return to_route('lawyer.already.treatment', $id);
        }

    }
    public function storeTreatment(TreatmentRequest $request, $id) {
        $lawyer_id = lawyerAuth()->id;
        $appointment = Appointment::where(['id' => $id, 'lawyer_id' => $lawyer_id])->paymentSuccess()->firstOrFail();

        if ($appointment?->already_treated == 0) {
            $appointment->subject = $request->subject;
            $appointment->description = $request->description;
            $appointment->already_treated = 1;
            $appointment->save();

            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $file_name = file_upload_storage_folder($file);
                    $appointment->documents()->create(['path' => $file_name]);
                }
            }

            $notification = ['message' => __('Create Successfully'), 'alert-type' => 'success'];
            return to_route('lawyer.already.treatment', $id)->with($notification);
        } else {
            return to_route('lawyer.already.treatment', $id);
        }

    }
    public function editTreatment($id) {
        $lawyer_id = lawyerAuth()->id;
        $appointment = Appointment::where(['id' => $id, 'lawyer_id' => $lawyer_id])->paymentSuccess()->firstOrFail();

        if ($appointment?->already_treated == 1) {

            return view('lawyer.appointment.edit_treatment', compact('appointment'));
        } else {
            return to_route('lawyer.treatment', $id);
        }

    }
    public function updateTreatment(TreatmentRequest $request, $id) {
        $lawyer_id = lawyerAuth()->id;
        $appointment = Appointment::where(['id' => $id, 'lawyer_id' => $lawyer_id])->paymentSuccess()->firstOrFail();

        if ($appointment?->already_treated == 1) {
            $appointment->subject = $request->subject;
            $appointment->description = $request->description;
            $appointment->save();

            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $file_name = file_upload_storage_folder($file);
                    $appointment->documents()->create(['path' => $file_name]);
                }
            }

            $notification = ['message' => __('Updated successfully'), 'alert-type' => 'success'];
            return to_route('lawyer.already.treatment', $id)->with($notification);
        } else {
            $notification = ['message' => __('Something went wrong'), 'alert-type' => 'error'];
            return back()->with($notification);
        }
    }
    public function downloadDocument($id, $path) {
        $lawyer_id = lawyerAuth()->id;
        Appointment::where(['id' => $id, 'lawyer_id' => $lawyer_id])->firstOrFail();
        $path = storage_path("app/public/uploads/{$path}");
        if (!file_exists($path)) {
            abort(404);
        }
        return response()->download($path);
    }
    public function deleteDocument($appointment_id, $id) {
        $lawyer_id = lawyerAuth()->id;

        $appointment = Appointment::where([
            'id'        => $appointment_id,
            'lawyer_id' => $lawyer_id,
        ])->first();

        if (!$appointment) {
            return response()->json(['success' => false, 'message' => __('Appointment not found!')], 404);
        }

        $document = $appointment->documents()->where('id', $id)->first();

        if (!$document) {
            return response()->json(['success' => false, 'message' => __('Document not found!')], 404);
        }

        // Optionally delete the DB record
        $document->deleteDocuments();
        $document->delete();

        return response()->json(['success' => true, 'message' => __('Deleted Successfully')], 200);
    }
    public function allReadyTreatment($id) {
        $lawyer_id = lawyerAuth()->id;
        $appointment = Appointment::where(['id' => $id, 'lawyer_id' => $lawyer_id])->paymentSuccess()->firstOrFail();

        if ($appointment->already_treated == 1) {
            return view('lawyer.appointment.already_treated', compact('appointment'));
        } else {
            return to_route('lawyer.treatment', $id);
        }

    }
    function printPrescription($id) {
        $appointment = lawyerAuth()->appointments()->treated()->find($id);

        if ($appointment) {
            $data = [
                'appointment' => $appointment,
            ];
            $pdf = Pdf::loadView('prescription', $data)->setPaper('a4', 'landscape');
            return $pdf->stream('prescription_' . $appointment->id . '.pdf');
        } else {
            $notification = __('No appointment found.');
            $notification = ['message' => $notification, 'alert-type' => 'error'];

            return redirect()->back()->with($notification);
        }
    }
    public function paymentHistory(Request $request) {
        $endDate = Carbon::now();
        $startDate = $endDate->copy()->subDays(30);

        $startDateFormatted = $startDate->format('Y-m-d H:i:s');
        $endDateFormatted = $endDate->format('Y-m-d H:i:s');

        $query = Appointment::query();
        $query->where('lawyer_id', lawyerAuth()?->id)->paymentSuccess();

        $payment = $query->whereBetween('created_at', [$startDateFormatted, $endDateFormatted])->sum('appointment_fee_usd');
        $total_appointment = $query->whereBetween('created_at', [$startDateFormatted, $endDateFormatted])->count();

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = date('Y-m-d', strtotime($request->to_date));
            $query->whereBetween('date', array($from_date, $to_date));
        }

        $orderBy = $request->filled('order_by') && $request->order_by == 1 ? 'asc' : 'desc';

        if ($request->filled('par-page')) {
            $appointments = $request->get('par-page') == 'all' ? $query->orderBy('id', $orderBy)->get() : $query->orderBy('id', $orderBy)->paginate($request->get('par-page'))->withQueryString();
        } else {
            $appointments = $query->orderBy('id', $orderBy)->paginate(10)->withQueryString();
        }
        return view('lawyer.payment.index', compact('appointments', 'payment', 'total_appointment'));

    }
    public function schedule() {
        $days = Day::select('id')->with([
            'translation' => function ($query) {
                $query->select('day_id', 'title');
            },
        ])->active()->get();
        $schedules = Schedule::select('day_id', 'start_time', 'end_time')->where('lawyer_id', lawyerAuth()?->id)->active()->get();
        return view('lawyer.schedule.index', compact('days', 'schedules'));
    }
}
