<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use App\Models\User;
use App\Services\Auth\ProfileService;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    private $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->middleware("auth");
        $this->profileService = $profileService;
    }
    public function updateProfile(ProfileRequest $request): User
    {
        $authUserId = JWTAuth::parseToken()->getPayload()->get("id");
        return $this->profileService->updateProfile(
            $authUserId,
            $request->file("image"),
            $request->validated()
        );
    }
}
