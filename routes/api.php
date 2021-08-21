<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', UserController::class);
    Route::get('/dashboard', DashboardController::class);
    Route::get('/states', StateController::class);
    Route::get('/shop', [ShopController::class, 'show']);
    Route::patch('/shop', [ShopController::class, 'save']);
    Route::get('/invoice-count', [InvoiceController::class, 'invoiceCount']);
    Route::apiResource('/invoices', InvoiceController::class);
    Route::apiResource('/products', ProductController::class);
    Route::apiResource('/customers', CustomerController::class);
});
