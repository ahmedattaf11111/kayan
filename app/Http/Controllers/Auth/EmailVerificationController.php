<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmailVerificationRequest;
use App\Services\Auth\EmailVerificationService;
use Tymon\JWTAuth\Facades\JWTAuth;

class EmailVerificationController extends Controller
{
    private $emailVerificationService;

    public function __construct(EmailVerificationService $emailVerificationService)
    {
        $this->middleware("auth", ["except" => "userVerified"]);
        $this->middleware("verified", ["only" => ["userVerified"]]);
        $this->emailVerificationService = $emailVerificationService;
    }
    //To verify the token in front end
    public function verifyToken()
    {
        return ["valid" => true];
    }
    public function verifyEmail(EmailVerificationRequest $request)
    {
        $authUserEmail = JWTAuth::parseToken()->getPayload()->get("email");
        $verificationCode = $this->emailVerificationService->verifyEmail(
            $authUserEmail,
            $request->verification_code
        );
        if (!$verificationCode) {
            return response()->json(["errorMessage" => "Verification code is not valid"], 400);
        }
    }
    public function resendVerificationCode()
    {
        $authUserEmail = JWTAuth::parseToken()->getPayload()->get("email");
        $this->emailVerificationService->resendVerificationCode($authUserEmail);
    }
    //To check if user verified
    public function userVerified()
    {
        return response()->json(["verified" => true]);
    }
}
