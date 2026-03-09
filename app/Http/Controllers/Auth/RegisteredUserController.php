<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Rules\CustomRecaptcha;
use App\Traits\GlobalMailTrait;
use Illuminate\Support\Facades\DB;
use App\Services\MailSenderService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Modules\Lawyer\app\Models\Location;
use App\Traits\GetGlobalInformationTrait;
use Modules\Lawyer\app\Models\Department;

class RegisteredUserController extends Controller {
    use GetGlobalInformationTrait, GlobalMailTrait;

    protected $setting;

    public function __construct() {
        $this->setting = cache()->get('setting');
    }

    public function create(): View {
        if (!$this->setting?->client_can_register && !$this->setting?->lawyer_can_register) {
            abort(404);
        }

        $departments = Department::with('translation')->active()->get();
        $locations = Location::with('translation')->active()->get();
        return view('client.profile.auth.register', compact('departments', 'locations'));
    }

    public function store(Request $request): RedirectResponse {
        if (!$this->setting?->client_can_register) {
            return redirect()->back();
        }

        $request->validate([
            'name'                 => ['required', 'string', 'max:255'],
            'email'                => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password'             => ['required', 'confirmed', 'min:4', 'max:100'],
            'g-recaptcha-response' => $this->setting?->recaptcha_status == 'active' ? ['required', new CustomRecaptcha()] : '',
        ], [
            'name.required'                 => __('Name is required'),
            'email.required'                => __('Email is required'),
            'email.unique'                  => __('Email already exist'),
            'password.required'             => __('Password is required'),
            'password.confirmed'            => __('Confirm password does not match'),
            'password.min'                  => __('You have to provide minimum 4 character password'),
            'g-recaptcha-response.required' => __('Please complete the recaptcha to submit the form'),
        ]);
        try {
            DB::beginTransaction();
            $user = User::create([
                'client_id'         => date('ymdis'),
                'name'               => $request->name,
                'email'              => $request->email,
                'status'             => 'active',
                'is_banned'          => 'no',
                'password'           => Hash::make($request->password),
                'verification_token' => Str::random(100),
            ]);

            (new MailSenderService)->sendVerifyMailSingleUser($user);
            DB::commit();

            $notification = __('A verification link has been sent to your mail, please verify and enjoy our service');
            $notification = ['message' => $notification, 'alert-type' => 'success'];

            return redirect()->back()->with($notification);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleMailException($e);
        }

    }

    public function custom_user_verification($token) {
        $user = User::where('verification_token', $token)->first();
        if ($user) {

            if ($user->email_verified_at != null) {
                $notification = __('Email already verified');
                $notification = ['message' => $notification, 'alert-type' => 'error'];

                return redirect()->route('login')->with($notification);
            }

            $user->email_verified_at = date('Y-m-d H:i:s');
            $user->verification_token = null;
            $user->save();

            $notification = __('Email verified successfully');
            $notification = ['message' => $notification, 'alert-type' => 'success'];

            return redirect()->route('login')->with($notification);
        } else {
            $notification = __('Invalid token');
            $notification = ['message' => $notification, 'alert-type' => 'error'];

            return redirect()->route('register')->with($notification);
        }
    }
}
