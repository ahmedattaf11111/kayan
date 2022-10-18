<?php

namespace App\Services\Auth;

use App\Mail\TokenVerification;
use App\Repositories\Auth\EmailVerificationRepository;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class EmailVerificationService
{
    const EXPIRATION_DURATION = 15; //In minutes
    private $emailVerificationRepository;
    public function __construct(EmailVerificationRepository $emailVerificationRepository)
    {
        $this->emailVerificationRepository = $emailVerificationRepository;
    }
    public function verifyEmail(string $email, string $verificationCode): string
    {
        return $this->emailVerificationRepository
            ->verifyUser($email, $verificationCode, self::EXPIRATION_DURATION);
    }
    public function resendVerificationCode(string $email)
    {
        $verificationCode = Str::random(5);
        $this->emailVerificationRepository->updateEmailVerification($email, $verificationCode);
        Mail::to($email)->send(new TokenVerification($verificationCode));
    }
}
