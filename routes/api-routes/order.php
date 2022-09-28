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

Route::prefix("payment")->group(function () {
    Route::get("cash", "PaymentController@cashPayment");
    Route::get("online", "PaymentController@onlinePayment");
    Route::get("callback-success", "PaymentController@callback_success")->name("callback_success");
    Route::get("callback-error", "PaymentController@callback_error")->name("callback_error");
});

Route::prefix("cart")->group(function () {
    Route::post("", "CartController@addToCart");
    Route::delete("", "CartController@removeCartItem");
    Route::get("", "CartController@getCartItems");
    Route::put("", "CartController@updateCartQuantity");
});
