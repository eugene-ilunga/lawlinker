<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Rules\CustomRecaptcha;
use App\Traits\GetGlobalInformationTrait;
use App\Traits\GlobalMailTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller {
    use GetGlobalInformationTrait, GlobalMailTrait;

    public function create(): View {
        return view('client.profile.auth.forget-password');
    }

    public function custom_forget_password(Request $request) {

        $setting = Cache::get('setting');

        $request->validate([
            'email'                => ['required', 'email'],
            'g-recaptcha-response' => $setting->recaptcha_status == 'active' ? ['required', new CustomRecaptcha()] : '',
        ], [
            'email.required'                => __('Email is required'),
            'g-recaptcha-response.required' => __('Please complete the recaptcha to submit the form'),
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user) {
            $user->forget_password_token = Str::random(100);
            $user->save();

            $token = $user->forget_password_token;

            [$subject, $message] = $this->fetchEmailTemplate('password_reset', ['user_name' => $user->name]);
            $link = [__('RESET PASSWORD') => route('reset-password-page', $token)];

            try {
                $this->sendMail($user->email, $subject, $message, $link);
                $notification = __('A password reset link has been send to your mail');
                $notification = ['message' => $notification, 'alert-type' => 'success'];
            } catch (\Exception $e) {
                info($e->getMessage());
                $notification = __('Failed to send password reset email, please try again later.');
                $notification = ['message' => $notification, 'alert-type' => 'error'];
            }

            return redirect()->back()->with($notification);

        } else {
            $notification = __('Email does not exist');
            $notification = ['message' => $notification, 'alert-type' => 'error'];

            return redirect()->back()->with($notification);
        }
    }
}
