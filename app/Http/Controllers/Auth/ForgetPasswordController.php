<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResetPassword;
use App\Services\Auth\ForgetPasswordService;
use App\Utils\Controllers\AuthUtil;

class ForgetPasswordController extends Controller
{
    use AuthUtil;

    private $forgetPasswordService;
    public function __construct(ForgetPasswordService $forgetPasswordService)
    {
        $this->forgetPasswordService = $forgetPasswordService;
    }
    public function forgetPassword(string $email)
    {
        $user = $this->forgetPasswordService->forgetPassword($email);
        if (!$user) {
            abort(404);
        }
    }
    public function resetPassword(ResetPassword $request)
    {
        $passwordReset = $this->forgetPasswordService->resetPassword($request->validated());
        if (!$passwordReset) {
            return response()->json(["error" => "Token isn't valid"], 400);
        }
        $request->merge(["email" => $passwordReset->email]);
        return $this->attempt($request->only("email", "password"));
    }
}
