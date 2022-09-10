<?php

namespace App\Services\Auth;

use App\Mail\EmailVerification;
use App\Repositories\Auth\AuthRepository;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthService
{
    private $authRepository;
    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }
    public function register(array $registerInput): string
    {
        $user = $this->authRepository->create($registerInput);
        $verificationCode = Str::random(5);
        $this->authRepository->createEmailVerification([
            "email" => $registerInput["email"],
            "verification_code" => $verificationCode,
        ]);
        Mail::to($user->email)->send(new EmailVerification($verificationCode));
        return $verificationCode;
    }
}
