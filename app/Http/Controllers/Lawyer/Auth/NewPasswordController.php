<?php

namespace App\Http\Controllers\Lawyer\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Modules\Lawyer\app\Models\Lawyer;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function custom_reset_password_page(Request $request, $token) {

        $lawyer = Lawyer::select('id', 'name', 'email', 'forget_password_token')->where('forget_password_token', $token)->first();

        if (!$lawyer) {
            $notification = __('Invalid token, please try again');
            $notification = ['message' => $notification, 'alert-type' => 'error'];

            return redirect()->route('password.request')->with($notification);
        }

        return view('lawyer.auth.reset-password', ['admin' => $lawyer, 'token' => $token]);
    }

    /**
     * Handle an incoming new password request.
     */
    public function custom_reset_password_store(Request $request, $token) {

        $setting = Cache::get('setting');

        $rules = [
            'email'    => 'required',
            'password' => 'required|min:4|confirmed',
        ];
        $customMessages = [
            'email.required'    => __('Email is required'),
            'password.required' => __('Password is required'),
            'password.min'      => __('Password must be 4 characters'),
        ];
        $this->validate($request, $rules, $customMessages);

        $lawyer = Lawyer::select('id', 'name', 'email', 'forget_password_token')->where('forget_password_token', $token)->where('email', $request->email)->first();

        if (!$lawyer) {
            $notification = __('Invalid token, please try again');
            $notification = ['message' => $notification, 'alert-type' => 'error'];

            return redirect()->back()->with($notification);
        }

        $lawyer->password = Hash::make($request->password);
        $lawyer->forget_password_token = null;
        $lawyer->save();

        $notification = __('Password Reset successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->route('login',['type'=>'lawyer'])->with($notification);

    }
}
