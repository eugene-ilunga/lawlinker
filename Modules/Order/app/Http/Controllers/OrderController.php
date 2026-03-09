<?php

namespace Modules\Order\app\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\GlobalMailTrait;
use Modules\Order\app\Models\Order;
use App\Http\Controllers\Controller;

class OrderController extends Controller {
    use GlobalMailTrait;
    public function index(Request $request) {
        $query = Order::query();
        $query->with('user');

        if ($request->filled(['from_date', 'to_date'])) {
            $from_date = date('Y-m-d 00:00:00', strtotime($request->from_date));
            $to_date = date('Y-m-d 23:59:59', strtotime($request->to_date));
            
            $query->whereBetween('created_at', [$from_date, $to_date]);
        }

        $query->when($request->filled('payment_status'), function ($q) use ($request) {
            $q->where('payment_status', $request->payment_status);
        });

        $orderBy = $request->filled('order_by') && $request->order_by == 1 ? 'asc' : 'desc';

        if ($request->filled('par-page')) {
            $orders = $request->get('par-page') == 'all' ? $query->orderBy('id', $orderBy)->get() : $query->orderBy('id', $orderBy)->paginate($request->get('par-page'))->withQueryString();
        } else {
            $orders = $query->orderBy('id', $orderBy)->paginate(10)->withQueryString();
        }

        $title = __('Order History');

        return view('order::index', ['orders' => $orders, 'title' => $title]);
    }

    public function pending_order(Request $request) {
        $query = Order::query();
        $query->with('user')->paymentPending();

        if ($request->filled(['from_date', 'to_date'])) {
            $from_date = date('Y-m-d 00:00:00', strtotime($request->from_date));
            $to_date = date('Y-m-d 23:59:59', strtotime($request->to_date));
            
            $query->whereBetween('created_at', [$from_date, $to_date]);
        }

        $query->when($request->filled('payment_status'), function ($q) use ($request) {
            $q->where('payment_status', $request->payment_status);
        });

        $orderBy = $request->filled('order_by') && $request->order_by == 1 ? 'asc' : 'desc';

        if ($request->filled('par-page')) {
            $orders = $request->get('par-page') == 'all' ? $query->orderBy('id', $orderBy)->get() : $query->orderBy('id', $orderBy)->paginate($request->get('par-page'))->withQueryString();
        } else {
            $orders = $query->orderBy('id', $orderBy)->paginate(10)->withQueryString();
        }
        $title = __('Pending Order');

        return view('order::index', ['orders' => $orders, 'title' => $title]);
    }
    public function active_orders(Request $request) {
        $query = Order::query();
        $query->with('user')->paymentSuccess();

        if ($request->filled(['from_date', 'to_date'])) {
            $from_date = date('Y-m-d 00:00:00', strtotime($request->from_date));
            $to_date = date('Y-m-d 23:59:59', strtotime($request->to_date));
            
            $query->whereBetween('created_at', [$from_date, $to_date]);
        }

        $query->when($request->filled('payment_status'), function ($q) use ($request) {
            $q->where('payment_status', $request->payment_status);
        });

        $orderBy = $request->filled('order_by') && $request->order_by == 1 ? 'asc' : 'desc';

        if ($request->filled('par-page')) {
            $orders = $request->get('par-page') == 'all' ? $query->orderBy('id', $orderBy)->get() : $query->orderBy('id', $orderBy)->paginate($request->get('par-page'))->withQueryString();
        } else {
            $orders = $query->orderBy('id', $orderBy)->paginate(10)->withQueryString();
        }
        $title = __('Successful Order');
        return view('order::index', ['orders' => $orders, 'title' => $title]);
    }

    public function show($order_id) {
        $order = Order::where('order_id', $order_id)->firstOrFail();

        return view('order::show', ['order' => $order]);
    }

    public function order_payment_approved($id) {
        $order = Order::findOrFail($id);
        $order->payment_status = 1;
        $order->approved_date = now();
        $order->appointments->payment_status = 1;
        $order->save();
        $order->appointments()->update(['payment_status' => 1]);

        $user = User::findOrFail($order->user_id);

        //mail send
        try {
            [$subject, $message] = $this->fetchEmailTemplate('approve_payment',['client_name' => $user->name,'orderId' => $order->order_id]);
            $this->sendMail($user->email, $subject, $message);
        } catch (\Exception $e) {
            info($e->getMessage());
        }

        $notification = __('Payment approved successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    public function destroy($id) {
        $order = Order::paymentPending()->find($id);

        $notification = $order
            ? ['message' => __('Deleted Successfully'), 'alert-type' => 'success']
            : ['message' => __('Something went wrong, please try again'), 'alert-type' => 'error'];

        if ($order) {
            $order->delete();
        }

        return redirect()->route('admin.orders')->with($notification);
    }
}
