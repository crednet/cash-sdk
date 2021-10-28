<?php

namespace CredPal\CPCash\Traits;

use CredPal\CPCash\Exceptions\CPCashException;
use CredPal\CPCash\Exceptions\NotFoundException;
use Illuminate\Support\Facades\DB;

trait WalletAccount
{
    /**
     * @param int|string $userId
     * @return bool
     */
    protected function userWalletExists($userId): bool
    {
        return DB::table('cpcash_wallets')->where('user_id', $userId)->exists();
    }

    /**
     * @param mixed $response
     * @param int|string $userId
     * @return int
     */
    protected function storeWalletDetails($response, $userId): int
    {
        return DB::table('cpcash_wallets')->insertGetId([
            'user_id' => $userId,
            'wallet_id' => $response['id']
        ]);
    }

    /**
     * @param int $userId
     * @param string $table
     * @return bool
     */
    protected function findUser(int $userId, string $table = 'users'): bool
    {
        return DB::table($table)->whereId($userId)->exists();
    }

    /**
     * @param $userId
     * @throws CPCashException
     * @throws NotFoundException
     * @return void
     */
    protected function isWalletConditionPassed($userId): void
    {
        if (!$this->findUser($userId)) {
            throw new NotFoundException(trans('cpcash::exception.user-not-found'));
        }

        if ($this->userWalletExists($userId)) {
            throw new CPCashException(trans('cpcash::exception.wallet-exists'));
        }
    }
}
