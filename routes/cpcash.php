<?php

use CredPal\CPCash\Http\Controllers\CPCashController;
use Illuminate\Support\Facades\Route;

Route::post('create-wallet', [CPCashController::class, 'createWallet'])
    ->middleware('check-wallet-account');
Route::post('top-up-wallet/{walletId}', [CPCashController::class, 'walletTopUp'])->middleware(['feature-access:cash-top-up']);
Route::post('top-up-wallet-reference/{walletId}', [CPCashController::class, 'walletTopUpWithReference'])->middleware(['feature-access:cash-top-up']);
Route::post('withdraw-wallet/{walletId}', [CPCashController::class, 'withdrawFromWallet'])->middleware(['feature-access:cash-withdrawal']);

Route::get('wallets', [CPCashController::class, 'getWallets']);
Route::get('wallet/{walletId}', [CPCashController::class, 'getWallet']);
Route::get('wallet/{walletId}/transactions', [CPCashController::class, 'getWalletTransactions']);

Route::post('lock/{walletId}', [CPCashController::class, 'lockWallet']);
Route::post('unlock/{walletId}', [CPCashController::class, 'unlockWallet']);

Route::get('providers', [CPCashController::class, 'getWalletProviders']);
