<?php

use App\Http\Controllers\Api\FarmProfileInputsController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\WooCommerceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Location API (cascading dropdowns for registration)
|--------------------------------------------------------------------------
*/
Route::prefix('locations')->group(function () {
    Route::get('countries', [LocationController::class, 'countries']);
    Route::get('districts', [LocationController::class, 'districts']);
    Route::get('sectors', [LocationController::class, 'sectors']);
    Route::get('cells', [LocationController::class, 'cells']);
    Route::get('villages', [LocationController::class, 'villages']);
});

/*
|--------------------------------------------------------------------------
| Farm Profile Inputs API (for input applications)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->prefix('farm-profiles')->group(function () {
    Route::get('available-inputs', [FarmProfileInputsController::class, 'availableInputs']);
    Route::get('input-categories', [FarmProfileInputsController::class, 'allCategories']);
});

/*
|--------------------------------------------------------------------------
| WooCommerce Pre-order Integration API
|--------------------------------------------------------------------------
| These routes are used by WordPress/WooCommerce to fetch pre-order
| listings and to send order webhooks. Protect with WOOCOMMERCE_API_TOKEN.
*/
Route::middleware('woocommerce.api')->prefix('woocommerce')->group(function () {
    Route::get('listings', [WooCommerceController::class, 'listings']);
    Route::get('listings/{listing}', [WooCommerceController::class, 'showListing']);
    Route::post('orders', [WooCommerceController::class, 'storeOrder']);
});
