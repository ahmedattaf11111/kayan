<?php

namespace App\Services\Auth;

use App\Mail\ForgetPassword;
use App\Models\PasswordReset;
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
    public function forgetPassword(string $email)
    {
        $user = User::where("email", $email)->first();
        if ($user) {
            $token = Str::random(40);
            $this->forgetPasswordRepository->insertResetPassword($user->email, $token);
            Mail::to($email)->send(new ForgetPassword(['user' => $user, 'token' => $token]));
        }
        return $user;
    }
    public function resetPassword(array $resetPasswordInput)
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
