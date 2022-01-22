<?php


namespace CredPal\CPCash\Services;


class TransactionService
{


    /**
     * @param $response
     * @param $amount
     * @param $walletId
     * @param $description
     * @param $category
     */
    public static function createTransaction($response, $amount, $walletId, $description, $category): void
    {
        $creditCardTransactionModel = config('cpcash.credit_card_transaction');
        $creditCardTransaction = new $creditCardTransactionModel($amount, null);
        $creditCardTransaction->logTransactionsForCash(
            'credpal_cash',
            $amount,
            $description,
            $category,
            $walletId,
            $response->json()
        );
    }
}