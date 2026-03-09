<?php

namespace Modules\Appointment\app\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Lawyer\app\Models\Lawyer;
use Modules\Appointment\app\Models\Appointment;
use Barryvdh\DomPDF\Facade\Pdf;

class PrescribeController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {
        $lawyers = Lawyer::active()->get();
        
        $query = Appointment::query();
        $query->treated()->orderBy('id', 'desc');

        if ($request->filled('from_date') && $request->filled('to_date')){
            $from_date= date('Y-m-d',strtotime($request->from_date));
            $to_date= date('Y-m-d',strtotime($request->to_date));
            $query->whereBetween('date', array($from_date, $to_date));
        }
        $query->when($request->filled('lawyer'), function ($q) use ($request) {
            $q->where('lawyer_id', $request->lawyer);
        });

        $orderBy = $request->filled('order_by') && $request->order_by == 1 ? 'asc' : 'desc';

        if ($request->filled('par-page')) {
            $appointments = $request->get('par-page') == 'all' ? $query->orderBy('id', $orderBy)->get() : $query->orderBy('id', $orderBy)->paginate($request->get('par-page'))->withQueryString();
        } else {
            $appointments = $query->orderBy('id', $orderBy)->paginate(10)->withQueryString();
        }
        return view('appointment::prescribe.index', compact('appointments', 'lawyers'));
    }

    public function show($id) {
        $appointment = Appointment::find($id);
        return view('appointment::prescribe.show', compact('appointment'));
    }
    public function printPrescription($id) {
        $appointment = Appointment::find($id);

        if ($appointment) {
            $data = [
                'appointment'         => $appointment,
            ];
            $pdf = Pdf::loadView( 'prescription', $data )->setPaper( 'a4', 'landscape' );
            return $pdf->stream('prescription_'.$appointment->id. '.pdf' );
        } else {
            $notification = __('No appointment found.');
            $notification = ['message' => $notification, 'alert-type' => 'error'];

            return redirect()->back()->with($notification);
        }
    }
}
