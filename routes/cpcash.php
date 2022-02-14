<?php

use CredPal\CPCash\Http\Controllers\CPCashController;
use CredPal\CPCash\Http\Controllers\VirtualAccountController;
use Illuminate\Support\Facades\Route;

Route::post('create-wallet', [CPCashController::class, 'createWallet'])
    ->middleware('check-wallet-account');
Route::post('top-up-wallet/{walletId}', [CPCashController::class, 'walletTopUp']);
Route::post('top-up-wallet-reference/{walletId}', [CPCashController::class, 'walletTopUpWithReference']);
Route::post('withdraw-wallet/{walletId}', [CPCashController::class, 'withdrawFromWallet']);
Route::get('generate-top-up-reference/{walletId}', [CPCashController::class, 'generateTopUpReference']);

Route::get('wallets', [CPCashController::class, 'getWallets']);
Route::get('wallet/{walletId}', [CPCashController::class, 'getWallet']);
Route::get('wallet/{walletId}/transactions', [CPCashController::class, 'getWalletTransactions']);

Route::post('lock/{walletId}', [CPCashController::class, 'lockWallet']);
Route::post('unlock/{walletId}', [CPCashController::class, 'unlockWallet']);

Route::get('providers', [CPCashController::class, 'getWalletProviders']);

Route::prefix('virtual-accounts')->group(function () {
    Route::post('{walletId}/transfer', [VirtualAccountController::class]);
});
