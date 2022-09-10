<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Repositories\Auth\ProfileRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProfileService
{
    private $profileRepository;
    public function __construct(ProfileRepository $profileRepository)
    {
        $this->profileRepository = $profileRepository;
    }
    public function updateProfile(int $userId, UploadedFile $image, array $userInput): User
    {
        if ($image) {
            $userInput["image"] = $image->store("");
        }
        $result = $this->profileRepository->updateProfile($userId, $userInput);
        if ($image) {
            Storage::delete($result["old_image"]);
        }
        return $result["user"];
    }
}
