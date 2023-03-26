<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Laravel\Sanctum\HasApiTokens;
use App\Utils\Models\AuthModelUtil;

class Admin extends Authenticatable implements JWTSubject
{
    use AuthModelUtil;
    use HasApiTokens, HasFactory, Notifiable;
    protected $guarded = [];
}
