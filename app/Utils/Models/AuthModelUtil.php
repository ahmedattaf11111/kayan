<?php

namespace App\Utils\Models;

use Illuminate\Support\Facades\Hash;

trait AuthModelUtil
{
    public function setPasswordAttribute($value)
    {
        $this->attributes["password"] = Hash::make($value);
    }
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [
            "id" => $this->id,
            "email" => $this->email,
            "image" => $this->image,
            "first_name" => $this->first_name,
            "last_name" => $this->last_name,
            "phone" => $this->phone,
            "address" => $this->address,
            "city" => $this->city,
            "age" => $this->age,
            "education" => $this->education,
            "job" => $this->job,
            "about_me" => $this->about_me,
            "email_verified_at" => $this->email_verified_at,
        ];
    }
}
