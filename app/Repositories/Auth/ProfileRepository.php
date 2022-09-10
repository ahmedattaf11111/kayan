<?php

namespace App\Repositories\Auth;

use App\Models\User;

class ProfileRepository
{
    public function updateProfile($id, $userInput)
    {
        $user = User::find($id);
        $userInput["image"] = isset($userInput["image"]) ? $userInput["image"] : $user->getOriginal("image");
        $user->update($userInput);
        return ["old_image" => $user->getOriginal("image"), "user" => $user];
    }
}
