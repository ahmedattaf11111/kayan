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
        "biggest-client-discount",
        "ProductController@getBiggestClientDiscountProducts"
    );
    Route::get("categories", "ProductController@getCategories");
    Route::get("deals", "ProductController@getDeals");
    Route::get("best-sellers", "ProductController@getBestSellers");
    Route::get("{productId}", "ProductController@getProductDetails");
});
