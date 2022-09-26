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

Route::prefix("products")->group(function () {
    Route::get(
        "biggest-client-discount/{categoryId}/{categoryLevel}",
        "ProductController@getBiggestClientDiscountProducts"
    );
    Route::get("main-with-sub-categories", "ProductController@getMainWithSubCategories");
    Route::get("deal", "ProductController@getDealProducts");
});
