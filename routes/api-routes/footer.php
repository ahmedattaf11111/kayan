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

Route::prefix("footer")->group(function () {
    Route::get("top-sections", "FooterController@getTopFooterSections");
    Route::get("need-help", "FooterController@getNeedHelp");
    Route::get("our-store", "FooterController@getOurStore");
});
