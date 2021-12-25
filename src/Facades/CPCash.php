<?php

namespace CredPal\CPCash\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static mixed createWallet()
 * @method static mixed getWalletTransactions($walletId, $page)
 * @method static array|mixed getWallet(string $walletId);
 * @method static array|mixed walletTopUp(string $walletId, $amount, string $provider, string $reference, string $description)
 * @method static array|mixed withdrawFromWallet(string $walletId, string $amount, string $description)
 * @method static array|mixed lockWallet(string $walletId)
 * @method static array|mixed unlockWallet(string $walletId)
 * @method static mixed getProviders()
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
