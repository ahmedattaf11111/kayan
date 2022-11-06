<?php

namespace App\Utils\Repositories;

use App\Models\EmailVerification;
use Carbon\Carbon;

trait EmailVerificationUtil
{
    public function createEmailVerification($emailVerificationInput)
    {
        $emailVerificationInput["created_at"] = Carbon::now();
        EmailVerification::create($emailVerificationInput);
    }
}
