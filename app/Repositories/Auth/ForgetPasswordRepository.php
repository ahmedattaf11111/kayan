<?php

namespace App\Repositories\Auth;

use App\Models\User;
use Carbon\Carbon;
use App\Models\PasswordReset;

class ForgetPasswordRepository
{
    public function insertResetPassword($email, $token)
    {
        PasswordReset::updateOrCreate(
            ["email" => $email],
            ["token" => $token, "created_at" => Carbon::now()]
        );
    }
    public function getPasswordReset($token, $expirationTime)
    {
        return  PasswordReset::where('token', $token)
            ->where('created_at', '>', Carbon::now()->subMinutes($expirationTime))->first();
    }
    public function changePassword($password, $email)
    {
        User::where('email', $email)->update(['password' => bcrypt($password)]);
        PasswordReset::where('email', $email)->delete();
    }
}
