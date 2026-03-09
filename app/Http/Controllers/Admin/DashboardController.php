<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Order\app\Models\Order;
use App\Http\Controllers\Controller;
use Modules\Lawyer\app\Models\Lawyer;
use Modules\NewsLetter\app\Models\NewsLetter;
use Modules\Appointment\app\Models\Appointment;

class DashboardController extends Controller {
    public function dashboard(Request $request) {
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

        $data = Appointment::selectRaw('date, SUM(appointment_fee_usd) as total_price')
            ->paymentSuccess()->whereBetween('created_at', [$start, $end])->groupBy('date')->get();

        $dates = [];

        while ($start <= $end) {
            $dates[] = $start->toDateString();
            $start->addDay();
        }

        $dataCal = array_fill_keys($dates, 0);

        foreach ($data as $item) {
            $dataCal[$item->date] = $item->total_price;
        }

        $data = [];
        $data['monthly_data'] = json_encode(array_values($dataCal));
        
        $data['new_appointment_qty'] = Appointment::paymentSuccess()->notTreated()->count();

        $pendingOrders = Order::paymentPending();
        $data['pending_orders_qty'] = $pendingOrders->count();
        $data['pending_orders'] = $pendingOrders->paginate(10);


        $data['success_appointment_qty'] = Appointment::paymentSuccess()->treated()->count();
        $data['client_qty'] = User::active()->count();
        $data['lawyer_qty'] = Lawyer::active()->count();
        $data['totalEarning'] = Appointment::paymentSuccess()->sum('appointment_fee_usd');
        $data['subscriber_qty'] = NewsLetter::verify()->count();
        
        $firstDay = new Carbon('first day of this month');
        $first_date= $firstDay->format('Y-m-d');
        $lastDay = new Carbon('last day of this month');
        $last_date= $lastDay->format('Y-m-d');

        $data['monthlyEarning'] = Appointment::whereBetween('created_at', [$first_date, $last_date])->paymentSuccess()->sum('appointment_fee_usd');
        $data['payment_histories'] = Appointment::whereBetween('created_at', [$first_date, $last_date])->paymentSuccess()->with(['lawyer' => function ($query) {
            $query->select('id', 'name', 'email', 'phone');
        }])->select('lawyer_id', DB::raw('SUM(appointment_fee_usd) as total_payment_fee'))
            ->groupBy('lawyer_id')->paginate(10);


        $data['oldestYear'] = Carbon::parse(Appointment::select('created_at')->orderBy('created_at', 'asc')->first()?->created_at)->year ?? Carbon::now()->year;
        $data['latestYear'] = Carbon::parse(Appointment::select('created_at')->orderBy('created_at', 'desc')->first()?->created_at)->year ?? Carbon::now()->year;

        return view('admin.dashboard', $data);
    }

    public function setLanguage() {
        $action = setLanguage(request('code'));

        if ($action) {
            $notification = __('Language Changed Successfully');
            $notification = ['message' => $notification, 'alert-type' => 'success'];
            return redirect()->back()->with($notification);
        }

        $notification = __('Language Changed Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }
    public function setCurrency() {
        $currency = allCurrencies()->where('currency_code', request('currency'))->first();

        if (session()->has('currency_code')) {
            session()->forget('currency_code');
            session()->forget('currency_position');
            session()->forget('currency_icon');
            session()->forget('currency_rate');
        }
        if ($currency) {
            session()->put('currency_code', $currency->currency_code);
            session()->put('currency_position', $currency->currency_position);
            session()->put('currency_icon', $currency->currency_icon);
            session()->put('currency_rate', $currency->currency_rate);

            $notification = __('Currency Changed Successfully');
            $notification = ['message' => $notification, 'alert-type' => 'success'];

            return redirect()->back()->with($notification);
        }
        getSessionCurrency();
        $notification = __('Currency Changed Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }
}
