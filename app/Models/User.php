<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\CanResetPassword;
use Laravel\Sanctum\HasApiTokens;
use App\Utils\Models\AuthModelUtil;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail, CanResetPassword
{
    use AuthModelUtil;
    use HasApiTokens, HasFactory, Notifiable;
    protected $guarded = [];
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function client()
    {
        return $this->hasOne(Client::class);
    }
}
