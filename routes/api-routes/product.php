<?php

use App\Constants\OrderStatus;
use App\Models\Order;
use App\Models\Product;
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
    Route::get("main-with-sub-categories", "ProductController@getMainWithSubCategories");
    Route::get("deals", "ProductController@getDeals");
    Route::get("best-sellers", "ProductController@getBestSellers");
    Route::get("most-populars", "ProductController@getMostPopulars");
    Route::get("bought", "ProductController@getBoughtProducts");
    Route::get("{productId}", "ProductController@getProductDetails");
});
Route::get("test", function () {
});
