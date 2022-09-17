<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Services\Auth\AuthService;
use App\Utils\Controllers\AuthUtil;

class AuthController extends Controller
{
    use AuthUtil;
    private $authService;
    public function __construct(AuthService $authService)
    {
        $this->middleware('auth', ['only' => ['getCurrentUser', 'logout']]);
        $this->authService = $authService;
    }
    public function login(LoginRequest $request)
    {
        return $this->attempt($request->validated());
    }
    public function register(RegisterRequest $request)
    {
        $this->authService->register($request->validated());
        return $this->attempt($request->only("email", "password"));
    }
    public function getCitiesWithAreas()
    {
        return $this->authService->getCitiesWithAreas();
    }
    public function logout()
    {
        auth()->logout();
    }
    public function getCurrentUser(): User
    {
        return request()->user();
    }
}
