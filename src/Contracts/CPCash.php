<?php

namespace CredPal\CPCash\Contracts;

use CredPal\CPCash\Exceptions\CPCashException;
use CredPal\CPCash\Exceptions\InternalServerException;

interface CPCash
{
    /**
     * @return mixed
     * @throws CPCashException
     * @throws InternalServerException
     */
    public function createWallet();

    /**
     * @param string|int $walletId
     * @param int|string|array|null $page
     * @return mixed
     * @throws CPCashException
     * @throws InternalServerException
     */
    public function getWalletTransactions($walletId, $page = 1);

    /**
     * @description get wallet details
     * @param string $walletId
     * @return array|mixed
     * @throws CPCashException
     * @throws InternalServerException
     */
    public function getWallet(string $walletId);

    /**
     * @description Top up wallet
     * @param string $walletId
     * @param string|int|float $amount
     * @param string $provider
     * @param string $description
     * @param string $authorizationCode
     * @param string $email
     * @return array|mixed
     * @throws CPCashException
     * @throws InternalServerException
     */
    public function walletTopUp(
        string $walletId,
        $amount,
        string $provider,
        string $description,
        string $authorizationCode,
        string $email
    );

    /**
     * @description Top up wallet
     * @param string $walletId
     * @param string|int|float $amount
     * @param string $description
     * @param string $category
     * @return array|mixed
     * @throws CPCashException
     * @throws InternalServerException
     */
    public function walletTopUpWithReward(
        string $walletId,
        $amount,
        string $description,
        string $category
    );

    /**
     * @description Top up wallet with reference
     * @param string $walletId
     * @param string|int|float $amount
     * @param string $provider
     * @param string $reference
     * @param string $description
     * @return array|mixed
     * @throws CPCashException
     * @throws InternalServerException
     */
    public function walletTopUpWithReference(
        string $walletId,
        $amount,
        string $provider,
        string $reference,
        string $description
    );

    /**
     * @description Withdraw wallet
     * @param string $walletId
     * @param string $amount
     * @param string $description
     * @return array|mixed
     * @throws CPCashException
     * @throws InternalServerException
     */
    public function withdrawFromWallet(string $walletId, string $amount, string $description);

    /**
     * @description Withdraw wallet using Third party invest | payment
     * @param string $walletId
     * @param string $amount
     * @param string $description
     * @param string $category
     * @return array|mixed
     * @throws InternalServerException|CPCashException
     */
    public function thirdPartyWithdraw(string $walletId, string $amount, string $description, string $category);

    /**
     * @description Generate top up reference for payment
     *
     * @param string $walletId
     * @return array|mixed
     * @throws InternalServerException|CPCashException
     */
    public function generateTopUpReference(string $walletId);

    /**
     * @param string $walletId
     * @return array|mixed
     * @throws CPCashException
     * @throws InternalServerException
     */
    public function lockWallet(string $walletId);

    /**
     * @param string $walletId
     * @return array|mixed
     * @throws CPCashException
     * @throws InternalServerException
     */
    public function unlockWallet(string $walletId);

    /**
     * @return mixed
     * @throws CPCashException
     * @throws InternalServerException
     */
    public function getProviders();
}
