<?php

namespace App\Http\Controllers\Lawyer;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Appointment\app\Models\Appointment;

class DashboardController extends Controller {
    public function index(Request $request): View {
        $lawyer = lawyerAuth();

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

        $data = Appointment::selectRaw("DATE_FORMAT(created_at, '%Y-%m-%d') as month, SUM(appointment_fee_usd) as total_price")
            ->paymentSuccess()->whereBetween('created_at', [$start, $end])->where('lawyer_id', $lawyer->id)->groupBy('month')->get();

        $dates = [];

        while ($start <= $end) {
            $dates[] = $start->toDateString();
            $start->addDay();
        }

        $dataCal = array_fill_keys($dates, 0);

        foreach ($data as $item) {
            $dataCal[$item->month] = $item->total_price;
        }

        $data = [];
        $data['monthly_data'] = json_encode(array_values($dataCal));

        $data['today_appointment'] = Appointment::where(['lawyer_id' => $lawyer->id, 'date' => date('Y-m-d')])->paymentSuccess()->notTreated()->get();
        $data['new_appointment'] = Appointment::where('lawyer_id', $lawyer->id)->paymentSuccess()->notTreated()->count();
        $data['success_appointment'] = Appointment::where('lawyer_id', $lawyer->id)->paymentSuccess()->treated()->count();

        $data['totalEarning'] = Appointment::paymentSuccess()->where('lawyer_id', $lawyer->id)->sum('appointment_fee_usd');

        $data['oldestYear'] = Carbon::parse(Appointment::select('created_at')->where('lawyer_id', $lawyer->id)->orderBy('created_at', 'asc')->first()?->created_at)->year ?? Carbon::now()->year;
        $data['latestYear'] = Carbon::parse(Appointment::select('created_at')->where('lawyer_id', $lawyer->id)->orderBy('created_at', 'desc')->first()?->created_at)->year ?? Carbon::now()->year;

        return view('lawyer.dashboard', $data);
    }
}
