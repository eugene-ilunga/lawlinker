<?php

namespace App\Http\Controllers\Lawyer\Auth;

use App\Http\Controllers\Controller;
use App\Rules\CustomRecaptcha;
use App\Traits\GetGlobalInformationTrait;
use App\Traits\GlobalMailTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Modules\Lawyer\app\Enums\LawyerStatus;
use Modules\Lawyer\app\Models\Lawyer;
use Modules\Language\app\Enums\TranslationModels;
use Modules\Language\app\Traits\GenerateTranslationTrait;

class RegisteredController extends Controller {
    use GenerateTranslationTrait, GetGlobalInformationTrait, GlobalMailTrait;

    public function store(Request $request): RedirectResponse {
        $setting = Cache::get('setting');
        if (!$setting?->lawyer_can_register) {
            return redirect()->back();
        }

        $request->validate([
            'name'                 => ['required', 'string', 'max:255'],
            'designations'         => 'required',
            'email'                => ['required', 'string', 'email', 'max:255', 'unique:' . Lawyer::class],
            'phone'                => 'required',
            'password'             => ['required', 'confirmed', 'min:4', 'max:100'],
            'department_id'        => 'sometimes|exists:departments,id',
            'location_id'          => 'sometimes|exists:locations,id',
            'g-recaptcha-response' => $setting->recaptcha_status == 'active' ? ['required', new CustomRecaptcha()] : '',
        ], [
            'name.required'                 => __('Name is required'),
            'designations.required'         => __('Designations is required.'),
            'email.required'                => __('Email is required'),
            'email.unique'                  => __('Email already exist'),
            'phone.required'                => __('Phone number is required.'),
            'password.required'             => __('Password is required'),
            'password.confirmed'            => __('Confirm password does not match'),
            'password.min'                  => __('You have to provide minimum 4 character password'),
            'department_id.required'        => __('The department is required.'),
            'department_id.exists'          => __('The selected department is invalid.'),
            'location_id.required'          => __('The location is required.'),
            'location_id.exists'            => __('The selected location is invalid.'),
            'g-recaptcha-response.required' => __('Please complete the recaptcha to submit the form'),
        ]);
        try {
            DB::beginTransaction();

            $slug = Str::slug($request->name);
            if (Lawyer::whereSlug($slug)->exists()) {
                $slug = $slug . '-' . Str::random(5);
            }

            $lawyer = Lawyer::create([
                'name'                 => $request->name,
                'slug'                 => $slug,
                'designations'         => $request->designations,
                'phone'                => $request->phone,
                'email'                => $request->email,
                'fee'                  => 0,
                'department_id'        => $request->department_id,
                'location_id'          => $request->location_id,
                'password'             => Hash::make($request->password),
                'email_verified_token' => Str::random(100),
            ]);

            [$subject, $message] = $this->fetchEmailTemplate('user_verification', ['user_name' => $lawyer->name]);
            $link = [__('CONFIRM YOUR EMAIL') => route('lawyer.verification', $lawyer->email_verified_token)];

            $this->sendMail($lawyer->email, $subject, $message, $link);

            $this->generateTranslations(
                TranslationModels::Lawyer,
                $lawyer,
                'lawyer_id',
                $request,
            );
            DB::commit();

            $notification = __('A verification link has been sent to your mail, please verify and enjoy our service');
            $notification = ['message' => $notification, 'alert-type' => 'success'];

            return redirect()->route('login', ['type' => 'lawyer'])->with($notification);
        } catch (\Exception $e) {
            DB::rollBack();
            info($e->getMessage());
            return $this->handleMailException($e);
        }

    }
    public function custom_lawyer_verification($token) {
        $lawyer = Lawyer::where('email_verified_token', $token)->first();
        if ($lawyer) {

            if ($lawyer->email_verified_at != null) {
                $notification = __('Email already verified');
                $notification = ['message' => $notification, 'alert-type' => 'error'];

                return redirect()->route('login', ['type' => 'lawyer'])->with($notification);
            }

            $lawyer->email_verified_at = date('Y-m-d H:i:s');
            $lawyer->email_verified_token = null;
            $lawyer->status = LawyerStatus::ACTIVE->value;
            $lawyer->save();

            $notification = __('A verification link has been sent to your mail, please verify and enjoy our service');
            $notification = ['message' => $notification, 'alert-type' => 'success'];

            return redirect()->route('login', ['type' => 'lawyer'])->with($notification);
        } else {
            $notification = __('Invalid token');
            $notification = ['message' => $notification, 'alert-type' => 'error'];

            return redirect()->route('register')->with($notification);
        }
    }
}
