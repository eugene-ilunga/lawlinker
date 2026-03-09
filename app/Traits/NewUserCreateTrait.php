<?php

namespace App\Traits;

use App\Enums\UserStatus;
use App\Models\User;
use App\Traits\GlobalMailTrait;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

trait NewUserCreateTrait {
    use GlobalMailTrait;
    private function createNewUser($callbackUser, $provider_name, $user) {
        if (!$user) {
            $password = Str::random(10);
            $user = User::create([
                'client_id'         => date('ymdis'),
                'name'              => $callbackUser->name,
                'email'             => $callbackUser->email,
                'status'             => 'active',
                'is_banned'          => 'no',
                'password'          => Hash::make($password),
                'email_verified_at' => now(),
            ]);
            try {
                [$subject, $message] = $this->fetchEmailTemplate('social_login', ['user_name' => $user->name, 'app_name' => config('app.name'),'email' => $user->email, 'password' => $password,'login_url' => route('login'),]);
                $this->sendMail($user->email, $subject, $message);
            } catch (Exception $e) {
                info($e);
            }
        }

        $socialite = $user->socialite()->create([
            'provider_name' => $provider_name,
            'provider_id'   => $callbackUser->getId(),
            'access_token'  => $callbackUser->token ?? null,
            'refresh_token' => $callbackUser->refreshToken ?? null,
        ]);

        return $socialite;
    }
}
