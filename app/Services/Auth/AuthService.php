<?php

namespace App\Services\Auth;

use App\Constants\PlatformType;
use App\Mail\TokenVerification;
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
    public function register($registerInput)
    {
        $this->authRepository->createClient(
            $this->getUserInput($registerInput),
            $this->getClientInput($registerInput)
        );
        $verificationCode = Str::random(5);
        $this->authRepository->createEmailVerification([
            "email" => $registerInput["email"],
            "verification_code" => $verificationCode,
        ]);
        Mail::to($registerInput["email"])->send(new TokenVerification($verificationCode));
    }
    public function getCitiesWithAreas()
    {
        return $this->authRepository->getCitiesWithAreas();
    }
    public function getUser($userId)
    {
        return $this->authRepository->getUser($userId);
    }
    //Commons
    private function getUserInput($registerInput)
    {
        return [
            "name" => $registerInput["name"],
            "email" => $registerInput["email"],
            "password" => $registerInput["password"],
            "phone" => $registerInput["phone"]
        ];
    }
    private function getClientInput($registerInput)
    {
        return [
            "store_name" => $registerInput["store_name"],
            "city_id" => $registerInput["city_id"],
            "area_id" => $registerInput["area_id"],
            "address" => $registerInput["address"],
            "type" => $registerInput["type"],
        ];
    }
}
