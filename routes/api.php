<?php

use App\Http\Controllers\Api\WooCommerceController;
use Illuminate\Support\Facades\Route;

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
