<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Utils\Controllers\AuthUtil;

class AuthController extends Controller
{
    use AuthUtil;
    public function __construct()
    {
        $this->middleware('auth:admin')->except("login");
    }
    public function verifyToken()
    {
        return ["verified" => true];
    }
    public function login(LoginRequest $request)
    {
        return $this->attempt($request->validated(), "admin");
    }
    public function logout()
    {
        auth()->logout();
    }
}
