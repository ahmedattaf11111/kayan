<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileImageRequest;
use App\Http\Requests\ProfileRequest;
use App\Services\Auth\ProfileService;
use Tymon\JWTAuth\Facades\JWTAuth;

class ProfileController extends Controller
{
    private $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->middleware("auth");
        $this->profileService = $profileService;
    }
    public function updateProfile(ProfileRequest $request)
    {
        $authUserId = JWTAuth::parseToken()->getPayload()->get("id");
        return $this->profileService->updateProfile(
            $authUserId,
            $request->validated()
        );
    }
    public function updateImage(ProfileImageRequest $request)
    {
        $authUserId = JWTAuth::parseToken()->getPayload()->get("id");
        return $this->profileService->updateImage($authUserId, $request->file("image"));
    }
    public function deleteImage()
    {
        $authUserId = JWTAuth::parseToken()->getPayload()->get("id");
        $this->profileService->deleteImage($authUserId);
    }

    public function getProfileStatistics()
    {
        $authUserId = JWTAuth::parseToken()->getPayload()->get("id");
        return $this->profileService->getProfileStatistics($authUserId);
    }

    public function getProfileOrders()
    {
        $authUserId = JWTAuth::parseToken()->getPayload()->get("id");
        return $this->profileService->getProfileOrders(
            $authUserId,
            request()->page_size,
            request()->from,
            request()->to,
        );
    }
}
