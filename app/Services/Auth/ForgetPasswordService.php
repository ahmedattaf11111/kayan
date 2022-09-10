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
    const EXPIRED_AT = 15;
    private $forgetPasswordRepository;
    public function __construct(ForgetPasswordRepository $forgetPasswordRepository)
    {
        $this->forgetPasswordRepository = $forgetPasswordRepository;
    }
    public function forgetPassword(string $email): User
    {
        $user = User::where("email", $email)->first();
        if ($user) {
            $token = Str::random(40);
            $this->forgetPasswordRepository->insertResetPassword($user->email, $token);
            Mail::to($email)->send(new ForgetPassword(['user' => $user, 'token' => $token]));
        }
        return $user;
    }
    public function resetPassword(array $resetPasswordInput): PasswordReset
    {
        $passwordReset = $this->forgetPasswordRepository
            ->getPasswordReset($resetPasswordInput["token"], self::EXPIRED_AT);
        if ($passwordReset) {
            $this->authRepository->changePassword($resetPasswordInput["password"], $passwordReset->email);
        }
        return $passwordReset;
    }
}
