<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Auth
Route::prefix("auth")->group(function () {
    Route::post("register", "AuthController@register");
    Route::post("login", "AuthController@login");
    Route::get("verify-token", "AuthController@verifyToken");
    Route::post("verify-email", "AuthController@verifyEmail");
    Route::get("resend-verification-code", "AuthController@resendVerificationCode");
    Route::get("user-verified", "AuthController@userVerified");
    Route::get('forget-password/{user:email}', "AuthController@forgetPassword");
    Route::post('reset-password', "AuthController@resetPassword");
    Route::get("logout", "AuthController@logout");
    Route::get('current-user', "AuthController@getCurrentUser");
    Route::post('update-profile', "AuthController@updateProfile");
});

//Categories
Route::prefix("categories")->group(function () {
    Route::get("", "CategoryController@getCategories");
});

//Newsletters
Route::prefix("newsletters")->group(function () {
    Route::post("", "NewsletterController@store");
});

//Sliders
Route::prefix("sliders")->group(function () {
    Route::get("", "SliderController@index");
});

//Simple advertises
Route::prefix("simple-advertises")->group(function () {
    Route::get("", "SimpleAdvertiseController@index");
});
