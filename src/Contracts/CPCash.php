<?php

namespace CredPal\CPCash\Contracts;


use CredPal\CPCash\Exceptions\CPCashException;
use CredPal\CPCash\Exceptions\InternalServerException;

interface CPCash
{
    /**
     * @param string $uri
     * @return string
     */
    public static function getUrl(string $uri): string;

    /**
     * @return void
     */
    public function setHeaders(): void;

    /**
     * @return mixed
     * @throws CPCashException
     * @throws InternalServerException
     */
    public function createWallet();

    /**
     * @param string|int $walletId
     * @param int $page
     * @return mixed
     * @throws CPCashException
     * @throws InternalServerException
     */
    public function getWalletTransactions($walletId, int $page);

    /**
     * @description get wallet details
     * @param string $walletId
     * @return array|mixed
     * @throws CPCashException
     * @throws InternalServerException
     */
    public function getWallet(string $walletId): array;

    /**
     * @description Top up wallet
     * @param string $walletId
     * @param string|int $amount
     * @param string $provider
     * @param string $reference
     * @param string $description
     * @return array|mixed
     * @throws CPCashException
     * @throws InternalServerException
     */
    public function walletTopUp(string $walletId, $amount, string $provider, string $reference, string $description): array;

    /**
     * @description Withdraw wallet
     * @param string $walletId
     * @param string $amount
     * @param string $description
     * @return array|mixed
     * @throws CPCashException
     * @throws InternalServerException
     */
    public function withdrawFromWallet(string $walletId, string $amount, string $description): array;

    /**
     * @param string $walletId
     * @return array|mixed
     * @throws CPCashException
     * @throws InternalServerException
     */
    public function lockWallet(string $walletId): array;

    /**
     * @param string $walletId
     * @return array|mixed
     * @throws CPCashException
     * @throws InternalServerException
     */
    public function unlockWallet(string $walletId): array;

    /**
     * @return mixed
     * @throws CPCashException
     * @throws InternalServerException
     */
    public function getProviders();
}
