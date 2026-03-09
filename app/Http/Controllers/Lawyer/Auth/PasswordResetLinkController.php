<?php

namespace App\Http\Controllers\Lawyer\Auth;

use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Traits\GlobalMailTrait;
use App\Http\Controllers\Controller;
use Modules\Lawyer\app\Models\Lawyer;
use App\Traits\GetGlobalInformationTrait;

class PasswordResetLinkController extends Controller
{
    use GetGlobalInformationTrait, GlobalMailTrait;

    /**
     * Display the password reset link request view.
     */
    public function create(): View {
        return view('lawyer.auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     */
    public function custom_forget_password(Request $request) {
        $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => __('Email is required'),
        ]);

        $lawyer = Lawyer::where('email', $request->email)->first();

        if ($lawyer) {
            $lawyer->forget_password_token = Str::random(100);
            $lawyer->save();

            $token = $lawyer->forget_password_token;

            [$subject, $message] = $this->fetchEmailTemplate('password_reset', ['user_name' => $lawyer->name]);
            $link = [__('CONFIRM YOUR EMAIL') => route('lawyer.password.reset', $token)];

            try {
                $this->sendMail($lawyer->email, $subject, $message, $link);
            } catch (\Exception $e) {
                info($e->getMessage());
            }

            $notification = __('A password reset link has been send to your mail');
            $notification = ['message' => $notification, 'alert-type' => 'success'];

            return redirect()->back()->with($notification);
        } else {
            $notification = __('Email does not exist');
            $notification = ['message' => $notification, 'alert-type' => 'error'];

            return redirect()->back()->with($notification);
        }
    }
}
