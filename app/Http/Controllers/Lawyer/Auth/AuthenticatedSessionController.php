<?php

namespace App\Http\Controllers\Lawyer\Auth;

use App\Http\Controllers\Controller;
use App\Rules\CustomRecaptcha;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Modules\Lawyer\app\Enums\LawyerStatus;
use Modules\Lawyer\app\Models\Lawyer;

class AuthenticatedSessionController extends Controller {
    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request) {
        $setting = Cache::get('setting');

        $rules = [
            'email'                => 'required|email',
            'password'             => 'required',
            'g-recaptcha-response' => $setting->recaptcha_status == 'active' ? ['required', new CustomRecaptcha()] : '',
        ];

        $customMessages = [
            'email.required'                => __('Email is required'),
            'password.required'             => __('Password is required'),
            'g-recaptcha-response.required' => __('Please complete the recaptcha to submit the form'),
        ];
        $this->validate($request, $rules, $customMessages);

        $credential = [
            'email'    => $request->email,
            'password' => $request->password,
        ];

        $lawyer = Lawyer::where('email', $request->email)->first();

        if ($lawyer) {
            if ($lawyer->status == LawyerStatus::ACTIVE->value) {
                if ($lawyer->email_verified_at == null) {
                    $notification = __('Please verify your email');
                    $notification = ['message' => $notification, 'alert-type' => 'error'];
                    return redirect()->route('login', ['type' => 'lawyer'])->with($notification);
                }

                if (Hash::check($request->password, $lawyer->password)) {
                    if (Auth::guard('lawyer')->attempt($credential, $request->lawyer_remember)) {

                        $notification = __('Logged in successfully.');
                        $notification = ['message' => $notification, 'alert-type' => 'success'];

                        $intendedUrl = session()->get('url.intended');
                        if ($intendedUrl && Str::contains($intendedUrl, '/lawyer')) {
                            return redirect()->intended(route('lawyer.dashboard'))->with($notification);
                        }
                        return redirect()->route('lawyer.dashboard')->with($notification);
                    }
                } else {
                    $notification = __('Invalid Credentials');
                    $notification = ['message' => $notification, 'alert-type' => 'error'];
                    return redirect()->route('login', ['type' => 'lawyer'])->with($notification);
                }
            } else {
                $notification = __('Inactive account');
                $notification = ['message' => $notification, 'alert-type' => 'error'];
                return redirect()->route('login', ['type' => 'lawyer'])->with($notification);
            }
        } else {
            $notification = __('Invalid Credentials');
            $notification = ['message' => $notification, 'alert-type' => 'error'];
            return redirect()->route('login', ['type' => 'lawyer'])->with($notification);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(): RedirectResponse {
        Auth::guard('lawyer')->logout();

        $notification = __('Logged out successfully.');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->route('login', ['type' => 'lawyer'])->with($notification);
    }
}
