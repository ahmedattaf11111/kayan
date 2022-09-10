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

Route::prefix("home")->group(function () {
    Route::get("categories", "HomeController@getCategories");
    Route::post("newsletters", "HomeController@storeNewsletter");
    Route::get("simple-advertises", "HomeController@getSimpleAdvertises");
    Route::get("sliders", "HomeController@getSliders");
});



