<?php

namespace CredPal\CPCash\Contracts;

interface VirtualAccount
{
    /**
     *
     * @param string $walletId
     * @param int $userId
     * @param string $firstName
     * @param string $lastName
     * @param string|null $middleName
     * @return array|mixed
     * @throws InternalServerException|CPCashException
     */
    public function createVirtualAccount(
        string $walletId,
        int $userId,
        string $firstName,
        string $lastName,
        ?string $middleName
    );

    /**
     *
     * @param string $walletId
     * @param float|int $amount
     * @param int|string $accountNumber
     * @param int|string $bankCode
     * @param string $description
     * @param string $reference
     * @return array|mixed
     * @throws InternalServerException|CPCashException
     */
    public function transferFunds(
        string $walletId,
        $amount,
        $accountNumber,
        $bankCode,
        string $description,
        string $reference
    );
}
