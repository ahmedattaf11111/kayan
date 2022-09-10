<?php

namespace App\Utils\Controllers;

trait AuthUtil
{
    protected function attempt(array $loginInput)
    {
        if (!$token = auth()->attempt($loginInput)) {
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
