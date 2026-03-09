<?php

namespace Modules\Appointment\app\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\MeetingHistory;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Modules\Lawyer\app\Models\Lawyer;
use Modules\Appointment\app\Models\Appointment;

class AppointmentController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {
        $query = Appointment::query();

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = date('Y-m-d', strtotime($request->to_date));
            $query->whereBetween('date', array($from_date, $to_date));
        }

        $query->when($request->filled('client'), function ($q) use ($request) {
            $q->where('user_id', $request->client);
        });

        $query->when($request->filled('treated'), function ($q) use ($request) {
            $q->where('already_treated', $request->treated);
        });

        $query->when($request->filled('payment_status'), function ($q) use ($request) {
            $q->where('payment_status', $request->payment_status);
        });

        $orderBy = $request->filled('order_by') && $request->order_by == 1 ? 'asc' : 'desc';

        if ($request->filled('par-page')) {
            $appointments = $request->get('par-page') == 'all' ? $query->orderBy('id', $orderBy)->get() : $query->orderBy('id', $orderBy)->paginate($request->get('par-page'))->withQueryString();
        } else {
            $appointments = $query->orderBy('id', $orderBy)->paginate(10)->withQueryString();
        }

        $clients = User::select('name','email', 'id')->active()->get();
        $title = __('All Appointment');
        return view('appointment::index', compact('appointments', 'clients', 'title'));
    }

    public function pending_appointment(Request $request) {
        $query = Appointment::query();
        $query = $query->paymentPending();

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = date('Y-m-d', strtotime($request->to_date));
            $query->whereBetween('date', array($from_date, $to_date));
        }

        $query->when($request->filled('client'), function ($q) use ($request) {
            $q->where('user_id', $request->client);
        });

        $query->when($request->filled('treated'), function ($q) use ($request) {
            $q->where('already_treated', $request->treated);
        });

        $orderBy = $request->filled('order_by') && $request->order_by == 1 ? 'asc' : 'desc';

        if ($request->filled('par-page')) {
            $appointments = $request->get('par-page') == 'all' ? $query->orderBy('id', $orderBy)->get() : $query->orderBy('id', $orderBy)->paginate($request->get('par-page'))->withQueryString();
        } else {
            $appointments = $query->orderBy('id', $orderBy)->paginate(10)->withQueryString();
        }

        $clients = User::select('name', 'id')->active()->get();
        $title = __('Pending Appointment');
        return view('appointment::index', compact('appointments', 'clients','title'));
    }
    public function new_appointment(Request $request) {
        $query = Appointment::query();
        $query = $query->paymentSuccess()->notTreated();

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
            $appointments = $query->orderBy('id', $orderBy)->paginate(10)->withQueryString();
        }
        $clients = User::select('name', 'id')->active()->get();
        $title = __('New Appointment');
        return view('appointment::index', compact('appointments', 'clients','title'));
    }
    public function show($id) {
        $appointment = Appointment::with('lawyer','user','order')->where('order_id', $id)->firstOrFail();
        return view('appointment::show', compact('appointment'));
    }
    public function paymentHistory(Request $request) {
        $lawyers = Lawyer::select('id','name')->active()->get();

        $query = Appointment::query();
        $query->whereHas('lawyer', function ($query) {
            $query->active();
        })->with(['lawyer' => function ($query) {
            $query->select('id', 'name', 'email', 'phone');
        }])->select('lawyer_id', DB::raw('SUM(appointment_fee_usd) as total_payment_fee'))
            ->groupBy('lawyer_id');

        $query->when($request->filled('lawyer'), function ($q) use ($request) {
            $q->where('lawyer_id', $request->lawyer);
        });

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = date('Y-m-d', strtotime($request->to_date));
            $query->whereBetween('date', array($from_date, $to_date));
        }

        $orderBy = $request->filled('order_by') && $request->order_by == 1 ? 'asc' : 'desc';

        if ($request->filled('par-page')) {
            $payment_histories = $request->get('par-page') == 'all' ? $query->orderBy('id', $orderBy)->get() : $query->orderBy('id', $orderBy)->paginate($request->get('par-page'))->withQueryString();
        } else {
            $payment_histories = $query->orderBy('id', $orderBy)->paginate(10)->withQueryString();
        }

        return view('appointment::payment.index', compact('payment_histories','lawyers'));

    }
    public function upcommingMeeting(Request $request){
        $now = now();
        $query = MeetingHistory::query();
        $query = $query->whereRaw('DATE_ADD(meeting_time, INTERVAL duration MINUTE) > ?', [$now]);



        if ($request->filled('from_date') && $request->filled('to_date')) {
            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = date('Y-m-d', strtotime($request->to_date));
            $query->whereBetween('meeting_time', array($from_date, $to_date));
        }

        $query->when($request->filled('lawyer'), function ($q) use ($request) {
            $q->where('lawyer_id', $request->lawyer);
        });

        $query->when($request->filled('client'), function ($q) use ($request) {
            $q->where('user_id', $request->client);
        });

        $orderBy = $request->filled('order_by') && $request->order_by == 1 ? 'asc' : 'desc';

        if ($request->filled('par-page')) {
            $histories = $request->get('par-page') == 'all' ? $query->orderBy('meeting_time', $orderBy)->get() : $query->orderBy('meeting_time', $orderBy)->paginate($request->get('par-page'))->withQueryString();
        } else {
            $histories = $query->orderBy('meeting_time', $orderBy)->paginate(10)->withQueryString();
        }

        $lawyers = Lawyer::select('name', 'id')->active()->get();
        $clients = User::select('name', 'id')->active()->get();


        $title = __('Upcomming Meeting');
        return view('appointment::meeting.index',compact('histories','lawyers','clients','title'));
    }
    public function previousMeeting(Request $request){
        $now = now();
        $query = MeetingHistory::query();
        $query = $query->whereRaw('DATE_ADD(meeting_time, INTERVAL duration MINUTE) < ?', [$now]);

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = date('Y-m-d', strtotime($request->to_date));
            $query->whereBetween('meeting_time', array($from_date, $to_date));
        }

        $query->when($request->filled('lawyer'), function ($q) use ($request) {
            $q->where('lawyer_id', $request->lawyer);
        });

        $query->when($request->filled('client'), function ($q) use ($request) {
            $q->where('user_id', $request->client);
        });

        $orderBy = $request->filled('order_by') && $request->order_by == 1 ? 'asc' : 'desc';

        if ($request->filled('par-page')) {
            $histories = $request->get('par-page') == 'all' ? $query->orderBy('meeting_time', $orderBy)->get() : $query->orderBy('meeting_time', $orderBy)->paginate($request->get('par-page'))->withQueryString();
        } else {
            $histories = $query->orderBy('meeting_time', $orderBy)->paginate(10)->withQueryString();
        }

        $lawyers = Lawyer::select('name', 'id')->active()->get();
        $clients = User::select('name', 'id')->active()->get();
        $title = __('Previous Meeting');
        return view('appointment::meeting.index',compact('histories','lawyers','clients','title'));
    }
}
