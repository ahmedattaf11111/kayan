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

Route::prefix("categories")->group(function () {
    Route::post("", "CategoryController@store");
    Route::post("update", "CategoryController@update");
    Route::delete("{id}", "CategoryController@delete");
    Route::get("", "CategoryController@index");
});

Route::prefix("companies")->group(function () {
    Route::post("", "CompanyController@store");
    Route::post("update", "CompanyController@update");
    Route::delete("{id}", "CompanyController@delete");
    Route::get("", "CompanyController@index");
});

Route::prefix("pharmacist-forms")->group(function () {
    Route::post("", "PharmacistFormController@store");
    Route::post("update", "PharmacistFormController@update");
    Route::delete("{id}", "PharmacistFormController@delete");
    Route::get("", "PharmacistFormController@index");
});

Route::prefix("admin-products")->group(function () {
    Route::get("categories", "ProductController@getCategories");
    Route::get("companies", "ProductController@getCompanies");
    Route::get("pharmacist-forms", "ProductController@getPharmacistForms");
    Route::post("", "ProductController@store");
    Route::post("update", "ProductController@update");
    Route::delete("{id}", "ProductController@delete");
    Route::get("", "ProductController@index");
});


Route::prefix("sliders")->group(function () {
    Route::get("products", "SliderController@getProducts");
    Route::post("", "SliderController@store");
    Route::post("update", "SliderController@update");
    Route::delete("{id}", "SliderController@delete");
    Route::get("", "SliderController@index");
});


Route::prefix("suppliers")->group(function () {
    Route::post("", "SupplierController@store");
    Route::post("update", "SupplierController@update");
    Route::delete("{id}", "SupplierController@delete");
    Route::get("", "SupplierController@index");
});


Route::prefix("deal")->group(function () {
    Route::post("", "DealPriceController@saveDeal");
    Route::get("", "DealPriceController@getDeal");
    Route::get("suppliers", "DealPriceController@getSuppliers");
    Route::get("products", "DealPriceController@getProducts");
});


Route::prefix("prices")->group(function () {
    Route::post("", "PriceController@store");
    Route::post("update", "PriceController@update");
    Route::delete("{id}", "PriceController@delete");
    Route::get("", "PriceController@index");
    Route::get("suppliers", "PriceController@getSuppliers");
    Route::get("products", "PriceController@getProducts");
});


Route::prefix("admin-auth")->group(function () {
    Route::post("login", "AuthController@login");
    Route::get("veriy-token", "AuthController@verifyToken");
    Route::get("logout", "AuthController@logout");
});


Route::prefix("admin-orders")->group(function () {
    Route::get("", "OrderController@index");
    Route::get("mark-status-as-completed/{id}", "OrderController@markStatusAsCompleted");
});
