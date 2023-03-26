<?php

namespace App\Utils\Controllers;

use Illuminate\Support\Facades\Auth;

trait AuthUtil
{
    protected function attempt(array $loginInput, $guard = "api")
    {
        if (!$token = Auth::guard($guard)->attempt($loginInput)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->respondWithToken($token);
    }
    //Commons
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
