<?php

namespace CredPal\CPCash\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static mixed createWallet()
 * @method static mixed getWallets()
 * @method static mixed getWalletTransactions($walletId, $page)
 * @method static array|mixed getWallet(string $walletId);
 * @method static array|mixed walletTopUp(string $walletId, $amount, string $provider, string $reference, string $description)
 * @method static array|mixed thirdPartyWalletTopUp(string $walletId, $amount, string $description, string $category)
 * @method static array|mixed withdrawFromWallet(string $walletId, string $amount, string $description)
 * @method static array|mixed thirdPartyWithdraw(string $walletId, string $amount, string $description, string $category)
 * @method static array|mixed debitWallet(string $walletId, $amount, string $description)
 * @method static array|mixed lockWallet(string $walletId)
 * @method static array|mixed unlockWallet(string $walletId)
 * @method static array|mixed generateTopUpReference(string $walletId)
 * @method static array|mixed updateWalletTransactionByReference(string $walletId, string $reference, string $status)
 * @method static array|mixed refundTransaction(string $walletId, string $reference)
 * @method static mixed getProviders()
 * @method static array|mixed transferFunds(string $walletId, $amount, $accountNumber, $bankCode, string $description, string $reference)
 * @method static array|mixed createVirtualAccount(string $walletId, int $userId, string $firstName, string $lastName, ?string $middleName, ?string $email)
 *
 * @see \CredPal\CPCash\Contracts\CPCash
 */

class CPCash extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'cpcash';
    }
}
