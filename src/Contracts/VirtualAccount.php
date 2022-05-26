<?php

namespace CredPal\CPCash\Contracts;

use CredPal\CPCash\Exceptions\CPCashException;
use CredPal\CPCash\Exceptions\InternalServerException;

interface VirtualAccount
{
    /**
     *
     * @param string $walletId
     * @param int $userId
     * @param string $firstName
     * @param string $lastName
     * @param string|null $middleName
     * @param string|null $email
     * @return array|mixed
     * @throws InternalServerException
     * @throws CPCashException
     */
    public function createVirtualAccount(
        string $walletId,
        int $userId,
        string $firstName,
        string $lastName,
        ?string $middleName,
        ?string $email
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
