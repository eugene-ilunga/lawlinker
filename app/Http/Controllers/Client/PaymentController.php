<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Session;

class PaymentController extends Controller {
    public function payment() {
        $user = userAuth();
        if ($user?->ready_for_appointment == 1) {
            $appointments = Cart::content();
            $payable_amount = Cart::priceTotal();

            Session::put('payable_amount', $payable_amount);

            $paymentService = app(\Modules\BasicPayment\app\Services\PaymentMethodService::class);
            $activeGateways = $paymentService->getActiveGatewaysWithDetails();

            return view('client.profile.payment')->with([
                'user'           => $user,
                'appointments'   => $appointments,
                'payable_amount' => $payable_amount,
                'paymentService' => $paymentService,
                'activeGateways' => $activeGateways,
            ]);
        } else {
            $notification = ['message' => __('Please fill up the form before payment'), 'alert-type' => 'error'];
            return to_route('dashboard')->with($notification);
        };

    }
}