<?php

namespace App\Repositories\Auth;

use App\Models\City;
use App\Models\Client;
use App\Models\User;
use App\Utils\Repositories\EmailVerificationUtil;
use Illuminate\Database\Eloquent\Collection;

class AuthRepository
{
    use EmailVerificationUtil;
    public function createClient($userInput,  $clientInput)
    {
        $user = User::create($userInput);
        $clientInput["user_id"] = $user->id;
        Client::create($clientInput);
    }
    public function getCitiesWithAreas(): Collection
    {
        return City::with("areas")->get();
    }
    public function getUser($userId)
    {
        return User::with("client")->find($userId);
    }
}
