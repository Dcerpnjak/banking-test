<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

// Homepage
Route::get('/', function () {
    return view('home');
})->name('home');

// Customer routes
Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
Route::post('/customers/{customer}/block', [CustomerController::class, 'block'])->name('customers.block');

// Account routes
Route::get('/accounts', [AccountController::class, 'index'])->name('accounts.index');
Route::get('/accounts/create', [AccountController::class, 'create'])->name('accounts.create');
Route::post('/accounts', [AccountController::class, 'store'])->name('accounts.store');
Route::post('/accounts/{account}/block', [AccountController::class, 'block'])->name('accounts.block');
Route::post('/accounts/{account}/close', [AccountController::class, 'close'])->name('accounts.close');

// Transaction routes
Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
Route::get('/transactions/deposit', [TransactionController::class, 'createDeposit'])->name('transactions.deposit');
Route::post('/transactions/deposit', [TransactionController::class, 'deposit'])->name('transactions.deposit.store');
Route::get('/transactions/withdraw', [TransactionController::class, 'createWithdrawal'])->name('transactions.withdraw');
Route::post('/transactions/withdraw', [TransactionController::class, 'withdraw'])->name('transactions.withdraw.store');
Route::get('/transactions/transfer', [TransactionController::class, 'createTransfer'])->name('transactions.transfer');
Route::post('/transactions/transfer', [TransactionController::class, 'transfer'])->name('transactions.transfer.store');
