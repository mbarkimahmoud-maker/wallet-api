<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\AdminWalletController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// route publique no JWT needed
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login',    [AuthController::class, 'login']);

//route protege-tokenn
Route::middleware('auth:api')->group(function () {

    // auth routes
    Route::get('/auth/me',      [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // wallet routes anyone
    Route::get('/wallet',        [WalletController::class, 'balance']);
    Route::post('/wallet/spend', [WalletController::class, 'spend']);

    // Admin wallet routes 
    Route::middleware('checkrole:admin')->group(function () {
        Route::post('/admin/wallet/{user}/credit', [AdminWalletController::class, 'credit']);
        Route::post('/admin/wallet/{user}/debit',  [AdminWalletController::class, 'debit']);
    });
});