<?php

use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;

// Customer routes
Route::get('/', [CustomerController::class, 'index'])->name('customers.index');
Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
