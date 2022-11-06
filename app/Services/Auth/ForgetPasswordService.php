<?php

namespace App\Services\Auth;

use App\Mail\ForgetPassword;
use App\Mail\TokenVerification;
use App\Models\User;
use App\Repositories\Auth\ForgetPasswordRepository;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ForgetPasswordService
{
    const EXPIRATION_DURATION = 15;
    private $forgetPasswordRepository;
    public function __construct(ForgetPasswordRepository $forgetPasswordRepository)
    {
        $this->forgetPasswordRepository = $forgetPasswordRepository;
    }
    public function forgetPassword($email)
    {
        $user = User::where("email", $email)->first();
        if ($user) {
            $token = Str::random(40);
            $this->forgetPasswordRepository->insertResetPassword($user->email, $token);
            Mail::to($email)->send(new ForgetPassword(['user' => $user, 'token' => $token]));
        }
        return $user;
    }

    public function forgetPasswordOtp($email)
    {
        $user = User::where("email", $email)->first();
        if ($user) {
            $token = mb_substr(strval(random_int(100000, 999999)), 0, 4, 'UTF-8');
            $this->forgetPasswordRepository->insertResetPassword($user->email, $token);
            Mail::to($email)->send(new TokenVerification($token));
        }
        return $user;
    }

    public function verifyToken($token)
    {
        $passwordReset = $this->forgetPasswordRepository->getPasswordReset(
            $token,
            self::EXPIRATION_DURATION
        );
        return $passwordReset;
    }

    public function resetPassword($resetPasswordInput)
    {
        $passwordReset = $this->forgetPasswordRepository->getPasswordReset(
            $resetPasswordInput["token"],
            self::EXPIRATION_DURATION
        );
        if ($passwordReset) {
            $this->forgetPasswordRepository->changePassword(
                $resetPasswordInput["password"],
                $passwordReset->email
            );
        }
        return $passwordReset;
    }
}
