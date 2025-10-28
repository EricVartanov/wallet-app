<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\WalletController;


Route::prefix('api')->group(function () {
    Route::post('/deposit', [WalletController::class, 'deposit']);
    Route::post('/withdraw', [WalletController::class, 'withdraw']);
    Route::post('/transfer', [WalletController::class, 'transfer']);
    Route::get('/balance/{user_id}', [WalletController::class, 'balance']);
});
