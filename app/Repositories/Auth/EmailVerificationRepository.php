<?php

namespace App\Repositories\Auth;

use Carbon\Carbon;
use App\Models\EmailVerification;
use App\Models\User;
use App\Utils\Repositories\EmailVerificationUtil;

class EmailVerificationRepository
{
    use EmailVerificationUtil;
    public function updateEmailVerification(string $email, string $verificationCode)
    {
        EmailVerification::where("email", $email)->update([
            "verification_code" => $verificationCode,
            "created_at" => Carbon::now(),
        ]);
    }
    public function verifyUser($email, $verificationCode, $expirationTime)
    {
        $emailVerification = $this->getEmailVerificationQuery($email, $verificationCode, $expirationTime);
        if ($emailVerification) {
            $emailVerification->toQuery()->delete();
            User::where("email", $email)->update(["email_verified_at" => Carbon::now()]);
        }
        return $emailVerification;
    }
    //Commons
    private function getEmailVerificationQuery($email, $verificationCode, $expirationTime): EmailVerification
    {
        return EmailVerification::where("email", $email)
            ->where("verification_code", $verificationCode)
            ->where('created_at', '>', Carbon::now()->subMinutes($expirationTime))->first();
    }
}
