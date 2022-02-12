<?php

namespace CredPal\CPCash\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static mixed createWallet()
 * @method static mixed getWallets()
 * @method static array|mixed generateTopUpReference(string $walletId)
 *
 * @see \CredPal\CPCash\Contracts\VirtualAccount
 */

class VirtualAccount extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'virtual-account';
    }
}
