<?php

namespace App\Http\Controllers\API\Client;

use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\MailSenderService;
use App\Traits\GlobalMailTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthenticatedController extends Controller {
    use GlobalMailTrait;

    public function register(Request $request): JsonResponse {
        // Check if client registration is allowed
        if (!cache()->get('setting')?->client_can_register) {
            return response()->json([
                'message' => __('Client registration is currently disabled'),
            ], 403);
        }

        // Validate request data
        $validator = Validator::make($request->all(), [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', 'min:4', 'max:100'],
        ], [
            'name.required'      => __('Name is required'),
            'email.required'     => __('Email is required'),
            'email.unique'       => __('Email already exists'),
            'password.required'  => __('Password is required'),
            'password.confirmed' => __('Confirm password does not match'),
            'password.min'       => __('You have to provide minimum 4 character password'),
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();
            // Create the user
            $user = User::create([
                'client_id'         => date('ymdis'),
                'name'               => $request->name,
                'email'              => $request->email,
                'status'             => 'active',
                'is_banned'          => 'no',
                'password'           => Hash::make($request->password),
                'verification_token' => Str::random(100),
            ]);

            // Send verification email
            (new MailSenderService)->sendVerifyMailSingleUser($user);

            DB::commit();

            // Return success message
            return response()->json([
                'message' => __('A verification link has been sent to your mail, please verify and enjoy our service'),
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => __('Registration failed due to an issue with sending the verification email. Please try again later.'),
            ], 500);

        }
    }
    public function forgetPassword(Request $request): JsonResponse {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
        ], [
            'email.required' => __('Email is required'),
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()], 422);
        }

        $user = User::where('email', $request->email)->first();
        if ($user) {
            $user->forget_password_token = Str::random(100);
            $user->save();

            $token = $user->forget_password_token;
            
            [$subject, $message] = $this->fetchEmailTemplate('password_reset', ['user_name' => $user->name,'token' => $token]);
            $link = [__('RESET PASSWORD') => route('reset-password-page', $token)];

            try {
                $this->sendMail($user->email, $subject, $message, $link);
                return response()->json(['status' => 'success', 'message' => __('A password reset link has been sent to your email.')], 200);
            } catch (\Exception $e) {
                return response()->json(['status' => 'error', 'message' => __('Failed to send password reset email, please try again later.')], 500);
            }

        } else {
            return response()->json(['status' => 'error', 'message' => __('Email does not exist.')], 404);
        }
    }
    public function resetPassword(Request $request): JsonResponse {
        $validator = Validator::make($request->all(), [
            'forget_password_token' => ['required', 'string'],
            'email'                 => ['required', 'string', 'email'],
            'password'              => ['required', 'confirmed', 'min:4', 'max:100'],
        ], [
            'forget_password_token.required' => __('Forget password token is required'),
            'email.required'                 => __('Email is required'),
            'password.required'              => __('Password is required'),
            'password.confirmed'             => __('Confirm password does not match'),
            'password.min'                   => __('You have to provide minimum 4 character password'),
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()], 422);
        }

        // Find the user with the provided token and email
        $user = User::select('id', 'name', 'email', 'forget_password_token')->where('forget_password_token', $request->forget_password_token)->where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'status'  => 'error',
                'message' => __('Invalid token, please try again'),
            ], 400);
        }

        // Update the user's password
        $user->password = Hash::make($request->password);
        $user->forget_password_token = null;
        $user->save();

        return response()->json([
            'status'  => 'success',
            'message' => __('Password reset successfully'),
        ], 200);
    }

    public function login(Request $request): JsonResponse {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required',
        ], [
            'email.required'    => __('Email is required'),
            'password.required' => __('Password is required'),
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()], 422);
        }

        // Find the user by email
        $user = User::where('email', $request->email)->first();

        if ($user) {
            if ($user->status == UserStatus::ACTIVE->value) {
                if ($user->is_banned == UserStatus::UNBANNED->value) {
                    if ($user->email_verified_at == null) {
                        return response()->json(['status' => 'error', 'message' => __('Please verify your email')], 403);
                    }
                    // Check password
                    if (Hash::check($request->password, $user->password)) {
                        $token = $user->createToken('clientToken', ['*'], now()->addMonth())->plainTextToken;
                        return response()->json(['status' => 'success', 'message' => __('Logged in successfully.'), 'token' => $token, 'user_id'=>$user->id], 200);
                    } else {
                        return response()->json(['status' => 'error', 'message' => __('Invalid Credentials')], 401);
                    }
                } else {
                    return response()->json(['status' => 'error', 'message' => __('Inactive account')], 403);
                }
            } else {
                return response()->json(['status' => 'error', 'message' => __('Inactive account')], 403);
            }
        } else {
            return response()->json(['status' => 'error', 'message' => __('Invalid Credentials')], 404);
        }
    }
    public function logout(): JsonResponse {
        auth()->guard('api')->user()->currentAccessToken()->delete();
        return response()->json(['status' => 'success', 'message' => __('Logged out successfully.')]);

    }
    public function logoutAllApp(): JsonResponse {
        auth()->guard('api')->user()->tokens()->delete();
        return response()->json(['status' => 'success', 'message' => __('Logged out successfully.')]);
    }

}
