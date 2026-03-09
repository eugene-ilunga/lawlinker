<?php

namespace App\Http\Controllers\Lawyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Appointment\app\Models\Appointment;
use Modules\PaymentWithdraw\app\Models\WithdrawMethod;
use Modules\PaymentWithdraw\app\Models\WithdrawRequest;

class LawyerWithdrawController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $lawyer = lawyerAuth();
        $methods = WithdrawMethod::active()->get();
        $withdraws = WithdrawRequest::where('lawyer_id', $lawyer->id)->latest()->paginate(15);

        $withdraw = WithdrawRequest::where('lawyer_id', $lawyer->id)->active()->get();

        $current_balance = $lawyer?->wallet_balance;
        $total_withdraw = $withdraw->sum('total_amount');
        $total_earning = $current_balance + $total_withdraw;

        return view('lawyer.withdraw.index', compact('methods', 'withdraws', 'total_earning', 'total_withdraw', 'current_balance'));
    }
    /**
     * Show the form for creating a new resource.
     *
     */
    public function create() {
         $lawyer = lawyerAuth();
        $methods = WithdrawMethod::active()->get();

        $appointment = Appointment::where('lawyer_id', $lawyer->id)->paymentSuccess()->treated()->get();
        $withdraw = WithdrawRequest::where('lawyer_id', $lawyer->id)->active()->get();

        $current_balance = $lawyer?->wallet_balance;
        $total_withdraw = $withdraw->sum('total_amount');
        $total_earning = $current_balance + $total_withdraw;

        return view('lawyer.withdraw.create', compact('methods', 'total_earning', 'total_withdraw', 'current_balance'));
    }

    public function getWithDrawAccountInfo($id) {
        $method = WithdrawMethod::find($id);
        return view('lawyer.withdraw.withdraw_account_info', compact('method'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request) {
        $rules = [
            'withdraw_method_id' => 'required',
            'amount'             => 'required|numeric',
            'account_info'       => 'required',
        ];

        $customMessages = [
            'withdraw_method_id.required' => __('Payment Method filed is required'),
            'amount.required'             => __('Withdraw amount filed is required'),
            'amount.numeric'              => __('Please provide valid numeric number'),
            'account_info.required'       => __('Account filed is required'),
        ];

        $request->validate($rules, $customMessages);

        $lawyer = lawyerAuth();

        if (!$lawyer?->fee) {
            $notification = ['message' => __('Please fill up your profile information before payment withdraw'), 'alert-type' => 'error'];
            return back()->with($notification);
        }
        if (WithdrawRequest::where('lawyer_id', $lawyer->id)->pending()->exists()) {
            $notification = ['message' => __('You have already sent a withdrawal request'), 'alert-type' => 'error'];
            return redirect()->route('lawyer.withdraw.index')->with($notification);
        }

        $current_balance = $lawyer?->wallet_balance;

        if ($request->amount > $current_balance) {
            $notification = ['message' => __('Sorry! Your Payment request is more then your current balance'), 'alert-type' => 'error'];
            return back()->with($notification);
        }

        $method = WithdrawMethod::whereId($request?->withdraw_method_id)->first();
        if ($request->amount >= $method->min_amount && $request->amount <= $method->max_amount) {
            $widthdraw = new WithdrawRequest();
            $widthdraw->lawyer_id = $lawyer?->id;
            $widthdraw->withdraw_method_id = $request?->withdraw_method_id;
            $widthdraw->current_balance  = $current_balance;
            $widthdraw->total_amount = $request?->amount;
            $withdraw_request = $request?->amount;
            $withdraw_amount = ($method?->withdraw_charge / 100) * $withdraw_request;
            $widthdraw->withdraw_amount = $request?->amount - $withdraw_amount;
            $widthdraw->withdraw_charge = $method?->withdraw_charge;
            $widthdraw->account_info = $request?->account_info;
            $widthdraw->save();

            $notification = ['message' => __('Withdraw request sent successfully, please wait for admin approval'), 'alert-type' => 'success'];
            return to_route('lawyer.withdraw.index')->with($notification);

        } else {
            $notification = ['message' => __('Your amount range is not available'), 'alert-type' => 'error'];
            return back()->with($notification);
        }
    }

    /**
     * Display the specified resource.
     *
     */
    public function show($id) {
        $withdraw = WithdrawRequest::find($id);
        return view('lawyer.withdraw.show', compact('withdraw'));
    }
}
