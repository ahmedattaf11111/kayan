<?php

namespace App\Repositories\Auth;

use App\Models\User;
use App\Utils\Repositories\EmailVerificationUtil;

class AuthRepository
{
    use EmailVerificationUtil;
    public function create(array $userInput)
    {
        return User::create($userInput);
    }
}
