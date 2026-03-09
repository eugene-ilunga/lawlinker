<?php

namespace Modules\PaymentWithdraw\app\Http\Controllers\Admin;

use App\Enums\RedirectType;
use App\Http\Controllers\Controller;
use App\Traits\GlobalMailTrait;
use App\Traits\RedirectHelperTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Lawyer\app\Models\Lawyer;
use Modules\PaymentWithdraw\app\Models\WithdrawMethod;
use Modules\PaymentWithdraw\app\Models\WithdrawRequest;

class WithdrawMethodController extends Controller {
    use GlobalMailTrait, RedirectHelperTrait;
    public function index(Request $request) {

        $query = WithdrawMethod::query();

        $query->when($request->filled('keyword'), function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->keyword . '%')
                ->orWhere('description', 'like', '%' . $request->keyword . '%')
                ->orWhere('min_amount', 'like', '%' . $request->keyword . '%')
                ->orWhere('max_amount', 'like', '%' . $request->keyword . '%')
                ->orWhere('withdraw_charge', 'like', '%' . $request->keyword . '%');
        });
        $query->when($request->filled('status'), function ($q) use ($request) {
            $q->where('status', $request->status);
        });

        $query->when($request->filled('order_by'), function ($q) use ($request) {
            $q->orderBy('id', $request->order_by == 1 ? 'asc' : 'desc');
        });

        if ($request->filled('par-page')) {
            $methods = $request->get('par-page') == 'all' ? $query->get() : $query->paginate($request->get('par-page'))->withQueryString();
        } else {
            $methods = $query->paginate()->withQueryString();
        }

        return view('paymentwithdraw::admin.method.index', compact('methods'));
    }

    public function create() {
        return view('paymentwithdraw::admin.method.create');
    }

    public function store(Request $request) {
        $rules = [
            'name'            => 'required',
            'minimum_amount'  => 'required|numeric',
            'maximum_amount'  => 'required|numeric',
            'withdraw_charge' => 'required|numeric',
            'description'     => 'required',
        ];
        $customMessages = [
            'name.required'            => __('Name is required'),
            'minimum_amount.required'  => __('Min amount is required'),
            'maximum_amount.required'  => __('Max amount is required'),
            'withdraw_charge.required' => __('Charge is required'),
            'description.required'     => __('Description is required'),
        ];
        $request->validate($rules, $customMessages);

        $method = new WithdrawMethod();
        $method->name = $request->name;
        $method->min_amount = $request->minimum_amount;
        $method->max_amount = $request->maximum_amount;
        $method->withdraw_charge = $request->withdraw_charge;
        $method->description = $request->description;
        $method->status = $request->status;
        $method->save();

        $notification = __('Create Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->route('admin.withdraw-method.index')->with($notification);
    }

    public function edit($id) {
        $method = WithdrawMethod::find($id);

        return view('paymentwithdraw::admin.method.edit', compact('method'));
    }

    public function update(Request $request, $id) {

        $rules = [
            'name'            => 'required',
            'minimum_amount'  => 'required|numeric',
            'maximum_amount'  => 'required|numeric',
            'withdraw_charge' => 'required|numeric',
            'description'     => 'required',
        ];
        $customMessages = [
            'name.required'            => __('Name is required'),
            'minimum_amount.required'  => __('Min amount is required'),
            'maximum_amount.required'  => __('Max amount is required'),
            'withdraw_charge.required' => __('Charge is required'),
            'description.required'     => __('Description is required'),
        ];

        $this->validate($request, $rules, $customMessages);

        $method = WithdrawMethod::find($id);
        $method->name = $request->name;
        $method->min_amount = $request->minimum_amount;
        $method->max_amount = $request->maximum_amount;
        $method->withdraw_charge = $request->withdraw_charge;
        $method->description = $request->description;
        $method->status = $request->status;
        $method->save();

        $notification = __('Updated successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->route('admin.withdraw-method.index')->with($notification);
    }

    public function destroy($id) {

        $method = WithdrawMethod::find($id);
        if ($method->withdraw_request()->count() > 0) {
            return $this->redirectWithMessage(RedirectType::ERROR->value);
        }
        $method->delete();

        $notification = __('Deleted Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->route('admin.withdraw-method.index')->with($notification);
    }

    public function withdraw_list(Request $request) {

        $query = WithdrawRequest::query();
        $query->with('lawyer');

        $query->when($request->filled('keyword'), function ($q) use ($request) {
            $q->where('method', 'like', '%' . $request->keyword . '%')
                ->orWhere('total_amount', 'like', '%' . $request->keyword . '%')
                ->orWhere('withdraw_amount', 'like', '%' . $request->keyword . '%')
                ->orWhere('withdraw_charge', 'like', '%' . $request->keyword . '%')
                ->orWhere('account_info', 'like', '%' . $request->keyword . '%')->orWhereHas('withdraw_method', function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->keyword . '%');
            });
        });

        $query->when($request->filled('status'), function ($q) use ($request) {
            $q->where('status', $request->status);
        });

        $query->when($request->filled('lawyer'), function ($q) use ($request) {
            $q->where('lawyer_id', $request->lawyer);
        });

        $orderBy = $request->filled('order_by') && $request->order_by == 1 ? 'asc' : 'desc';

        if ($request->filled('par-page')) {
            $withdraws = $request->get('par-page') == 'all' ? $query->orderBy('id', $orderBy)->get() : $query->orderBy('id', $orderBy)->paginate($request->get('par-page'))->withQueryString();
        } else {
            $withdraws = $query->paginate()->withQueryString();
        }

        $title = __('Withdraw Request');
        $lawyers = Lawyer::select('name', 'id')->active()->get();

        return view('paymentwithdraw::admin.index', compact('withdraws', 'title', 'lawyers'));
    }

    public function pending_withdraw_list(Request $request) {
        $query = WithdrawRequest::query();
        $query->with('lawyer')->where('status', 'pending');

        $query->when($request->filled('keyword'), function ($q) use ($request) {
            $q->where('method', 'like', '%' . $request->keyword . '%')
                ->orWhere('total_amount', 'like', '%' . $request->keyword . '%')
                ->orWhere('withdraw_amount', 'like', '%' . $request->keyword . '%')
                ->orWhere('withdraw_charge', 'like', '%' . $request->keyword . '%')
                ->orWhere('account_info', 'like', '%' . $request->keyword . '%');
        });

        $query->when($request->filled('lawyer'), function ($q) use ($request) {
            $q->where('lawyer_id', $request->lawyer);
        });

        $orderBy = $request->filled('order_by') && $request->order_by == 1 ? 'asc' : 'desc';

        if ($request->filled('par-page')) {
            $withdraws = $request->get('par-page') == 'all' ? $query->orderBy('id', $orderBy)->get() : $query->orderBy('id', $orderBy)->paginate($request->get('par-page'))->withQueryString();
        } else {
            $withdraws = $query->paginate()->withQueryString();
        }

        $title = __('Pending withdraw');
        $lawyers = Lawyer::select('name', 'id')->active()->get();

        return view('paymentwithdraw::admin.index', compact('withdraws', 'title', 'lawyers'));
    }

    public function show_withdraw($id) {

        $withdraw = WithdrawRequest::find($id);

        return view('paymentwithdraw::admin.show', compact('withdraw'));
    }

    public function destroy_withdraw($id) {

        $withdraw = WithdrawRequest::findOrFail($id);
        if ($withdraw->status != 'approved') {
            $withdraw->delete();
            $notification = ['message' => __('Deleted Successfully'), 'alert-type' => 'success'];
            return to_route('admin.withdraw-list')->with($notification);
        }
        return $this->redirectWithMessage(RedirectType::ERROR->value);
    }

    public function approved_withdraw($id) {
        try {
            DB::beginTransaction();

            $withdraw = WithdrawRequest::find($id);
            $lawyer = Lawyer::findOrFail($withdraw->lawyer_id);

            $withdraw->status = 'approved';
            $withdraw->current_balance = $lawyer->wallet_balance;
            $withdraw->approved_date = date('Y-m-d');
            $withdraw->save();

            $lawyer->decrement('wallet_balance', $withdraw->total_amount);

            //mail send
            [$subject, $message] = $this->fetchEmailTemplate('approved_withdraw', ['user_name' => $lawyer?->name]);
            try {
                $this->sendMail($lawyer->email, $subject, $message);
            } catch (\Exception $e) {
                info($e->getMessage());
            }

            DB::commit();

            $notification = __('Withdraw request approval successfully');
            $notification = ['message' => $notification, 'alert-type' => 'success'];

            return redirect()->route('admin.withdraw-list')->with($notification);

        } catch (Exception $e) {
            DB::rollback();
            info($e->getMessage());

            $notification = __('Something went wrong, please try again');
            $notification = ['message' => $notification, 'alert-type' => 'error'];

            return redirect()->route('admin.withdraw-list')->with($notification);
        }

    }
    public function statusUpdate($id) {

        $withdraw_method = WithdrawMethod::find($id);
        $status = $withdraw_method->status == 'active' ? 'inactive' : 'active';
        $withdraw_method->update(['status' => $status]);

        $notification = __('Updated successfully');

        return response()->json([
            'success' => true,
            'message' => $notification,
        ]);
    }
}
