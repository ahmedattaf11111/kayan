<?php

namespace App\Services\Auth;

use App\Constants\PlatformType;
use App\Mail\EmailVerification;
use App\Repositories\Auth\AuthRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthService
{
    private $authRepository;
    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }
    public function register(array $registerInput): void
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
        Mail::to($registerInput["email"])->send(new EmailVerification($verificationCode));
    }
    public function getCitiesWithAreas(): Collection
    {
        return $this->authRepository->getCitiesWithAreas();
    }
    //Commons
    private function getUserInput(array $registerInput): array
    {
        return [
            "email" => $registerInput["email"],
            "password" => $registerInput["password"],
            "phone" => $registerInput["phone"]
        ];
    }
    private function getClientInput(array $registerInput): array
    {
        return [
            "store_name" => $registerInput["store_name"],
            "city_id" => $registerInput["city_id"],
            "area_id" => $registerInput["area_id"],
            "address" => $registerInput["address"],
            "type" => $registerInput["type"],
            "platform_type" => PlatformType::WEB
        ];
    }
}
