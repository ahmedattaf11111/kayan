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

Route::prefix("filter")->group(function () {
    Route::get("suppliers", "FilterController@getSuppliers");
    Route::get("companies", "FilterController@getCompanies");
    Route::get("pharmacological-forms", "FilterController@getPharmacologicalForms");
});
