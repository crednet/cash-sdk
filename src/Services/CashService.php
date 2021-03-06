<?php

namespace CredPal\CPCash\Services;

use CredPal\CPCash\Contracts\{CPCash, VirtualAccount};
use CredPal\CPCash\Exceptions\CPCashException;
use CredPal\CPCash\Exceptions\NotFoundException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use CredPal\CPCash\Exceptions\InternalServerException;
use Symfony\Component\HttpFoundation\Response;

class CashService implements CPCash, VirtualAccount
{
    /**
     * @var string|null
     */
    protected static ?string $token;

    /**
     * @var array
     */
    protected static array $headers = [];

    /**
     * @var string
     */
    protected static string $url;

    /**
     * CashService constructor.
     */
    public function __construct()
    {
        $this->setHeaders();
    }

    /**
     * @return mixed
     * @throws CPCashException
     * @throws InternalServerException|NotFoundException
     */
    public function createWallet()
    {
        return static::handleResponse($this->sendRequest()->post(static::getUrl('create')));
    }

    /**
     * @return mixed
     * @throws CPCashException
     * @throws InternalServerException|NotFoundException
     */
    public function getWallets()
    {
        return static::handleResponse($this->sendRequest()->get(static::getUrl('')));
    }

    /**
     * @description get wallet details
     * @param string|int $walletId
     * @return array|mixed
     * @throws CPCashException
     * @throws InternalServerException|NotFoundException
     */
    public function getWallet($walletId)
    {
        return static::handleResponse($this->sendRequest()->get(static::getUrl("{$walletId}")));
    }

    /**
     * @param string|int $walletId
     * @param int|string|array|null $pageNo
     * @return mixed
     * @throws CPCashException
     * @throws InternalServerException|NotFoundException
     */
    public function getWalletTransactions($walletId, $pageNo = 1)
    {
        $page = request()->query('page');
        $limit = request()->query('limit');

        return static::handleResponse(
            $this->sendRequest()->get(static::getUrl("{$walletId}/transactions?page={$page}&limit={$limit}"))
        );
    }

    /**
     * @description Top up wallet with card
     * @param string $walletId
     * @param string|int|float $amount
     * @param string $provider
     * @param string $description
     * @param string $authorizationCode
     * @param string $email
     * @return array|mixed
     * @throws CPCashException
     * @throws InternalServerException|NotFoundException
     */
    public function walletTopUp(
        string $walletId,
        $amount,
        string $provider,
        string $description,
        string $authorizationCode,
        string $email
    ) {
        $response = $this->sendRequest()->post(static::getUrl("{$walletId}/top-up"), [
            'amount' => $amount,
            'provider' => $provider,
            'description' => $description,
            'authorization_code' => $authorizationCode,
            'email' => $email
        ]);

        return static::handleResponse($response);
    }

    /**
     * @description Top up wallet with reference
     * @param string $walletId
     * @param string|int|float $amount
     * @param string $provider
     * @param string $reference
     * @param string $description
     * @return array|mixed
     * @throws CPCashException
     * @throws InternalServerException|NotFoundException
     */
    public function walletTopUpWithReference(
        string $walletId,
        $amount,
        string $provider,
        string $reference,
        string $description
    ) {
        $response = $this->sendRequest()->post(static::getUrl("{$walletId}/top-up-reference"), [
            'amount' => $amount,
            'provider' => $provider,
            'reference' => $reference,
            'description' => $description
        ]);

        return static::handleResponse($response);
    }

    /**
     * @description Topup wallet with reward | referral | invest cashbacks | virtual-card
     * @param string $walletId
     * @param string|int|float $amount
     * @param string $description
     * @param string $category
     * @return array|mixed
     * @throws CPCashException
     * @throws InternalServerException
     */
    public function thirdPartyWalletTopUp(
        string $walletId,
        $amount,
        string $description,
        string $category
    ) {
        $response = $this->sendRequest()->post(static::getUrl("{$walletId}/top-up-reward"), [
            'amount' => $amount,
            'description' => $description,
            'category' => $category,
        ]);

        return static::handleResponse($response);
    }

    /**
     * @description Update a wallet's transaction status
     *
     * @param string $walletId
     * @param string $reference
     * @param string $status
     * @return array|mixed
     * @throws InternalServerException|CPCashException
     */
    public function updateWalletTransactionByReference(string $walletId, string $reference, string $status)
    {
        return static::handleResponse($this->sendRequest()
            ->put(static::getUrl("{$walletId}/transactions/{$reference}"), ['status' => $status]));
    }

    /**
     * @description Withdraw wallet
     * @param string $walletId
     * @param string $amount
     * @param string $description
     * @return array|mixed
     * @throws CPCashException
     * @throws InternalServerException|NotFoundException
     */
    public function withdrawFromWallet(string $walletId, string $amount, string $description)
    {
        $response = $this->sendRequest()->post(static::getUrl("{$walletId}/withdraw"), [
            'amount' => $amount,
            'description' => $description,
            'category' => 'withdrawal'
        ]);

        return static::handleResponse($response);
    }

    /**
     * @description Withdraw wallet using Third party invest | payment | virtual-card
     * @param string $walletId
     * @param string $amount
     * @param string $description
     * @param string $category
     * @return array|mixed
     * @throws InternalServerException|CPCashException
     */
    public function thirdPartyWithdraw(string $walletId, string $amount, string $description, string $category)
    {
        $response = $this->sendRequest()->post(static::getUrl("{$walletId}/third-party-withdraw"), [
            'amount' => $amount,
            'description' => $description,
            'category' => $category
        ]);

        return static::handleResponse($response);
    }
    /**
     * @description Debit a wallet
     * @param string $walletId
     * @param float|int|string $amount
     * @param string $description
     * @return array|mixed
     * @throws CPCashException
     * @throws InternalServerException|NotFoundException
     */
    public function debitWallet(string $walletId, $amount, string $description)
    {
        $response = $this->sendRequest()->post(static::getUrl("{$walletId}/debit"), [
            'amount' => $amount,
            'description' => $description,
        ]);

        return static::handleResponse($response);
    }
    /**
     * @description Generate top up reference for payment
     *
     * @param string $walletId
     * @return array|mixed
     * @throws InternalServerException|CPCashException
     */
    public function generateTopUpReference(string $walletId)
    {
        return static::handleResponse(
            $this->sendRequest()->post(static::getUrl("{$walletId}/generate-top-up-reference"))
        );
    }

    /**
     * @param string $walletId
     * @return array|mixed
     * @throws CPCashException
     * @throws InternalServerException|NotFoundException
     */
    public function lockWallet(string $walletId)
    {
        return static::handleResponse($this->sendRequest()->post(static::getUrl("{$walletId}/lock")));
    }

    /**
     * @param string $walletId
     * @return array|mixed
     * @throws CPCashException
     * @throws InternalServerException|NotFoundException
     */
    public function unlockWallet(string $walletId)
    {
        return static::handleResponse($this->sendRequest()->post(static::getUrl("{$walletId}/unlock")));
    }

    /**
     * @return mixed
     * @throws CPCashException
     * @throws InternalServerException|NotFoundException
     */
    public function getProviders()
    {
        return static::handleResponse($this->sendRequest()->get(static::getUrl("providers")));
    }

    /**
     *
     * @param string $walletId
     * @param int $userId
     * @param string $firstName
     * @param string $lastName
     * @param string|null $middleName
     * @param string|null $email
     * @return array|mixed
     * @throws CPCashException|InternalServerException|NotFoundException
     */
    public function createVirtualAccount(
        string $walletId,
        int $userId,
        string $firstName,
        string $lastName,
        ?string $middleName,
        ?string $email
    ) {
        $response = $this->sendRequest()->post(static::getUrl("{$walletId}/virtual-accounts"), [
            'user_id' => $userId,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'middle_name' => $middleName,
            'email' => $email
        ]);

        return static::handleResponse($response);
    }

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
    ) {
        $response = $this->sendRequest()->post(static::getUrl("{$walletId}/virtual-accounts/funds-transfer"), [
            'amount' => $amount,
            'account_number' => $accountNumber,
            'bank_code' => $bankCode,
            'description' => $description,
            'reference' => $reference
        ]);

        return static::handleResponse($response);
    }

    /**
     * @return PendingRequest
     */
    public function sendRequest(): PendingRequest
    {
        return Http::timeout(20)->withHeaders(static::$headers);
    }

    /**
     * @param mixed $response
     * @return mixed
     * @throws CPCashException
     * @throws InternalServerException|NotFoundException
     */
    final protected static function handleResponse($response)
    {
        if ($response->failed()) {
            static::handleErrorResponse($response);
        }

        $data = $response->json('data');

        if (isset($data['datatable'])) {
            return $data['datatable'];
        } else if (isset($data['data'])) {
            return $data['data'];
        } else {
            return $data;
        }
    }

    /**
     * @description Transform Error Response
     * @param mixed $response
     * @throws CPCashException
     * @throws InternalServerException
     * @throws NotFoundException
     */
    final protected static function handleErrorResponse($response): void
    {
        Log::info($response);

        if ($response->status() >= Response::HTTP_INTERNAL_SERVER_ERROR) {
            throw new InternalServerException(trans('cpcash::exception.internal-error'));
        }

        if ($response->status() === Response::HTTP_UNAUTHORIZED) {
            throw new CPCashException(trans('cpcash::exception.unauthorized'));
        }

        if ($response->status() === Response::HTTP_UNPROCESSABLE_ENTITY) {
            throw new CPCashException(
                __('Invalid Data Provided'),
                Response::HTTP_UNPROCESSABLE_ENTITY,
                $response->json('data')['errors']
            );
        }

        if ($response->status() === Response::HTTP_NOT_FOUND) {
            throw new NotFoundException(trans('cpcash::exception.wallet-not-found'));
        }

        throw new CPCashException($response['message']);
    }

    private function setHeaders()
    {
        static::$token = app()->environment('production') ?
            config('cpcash.live.secret_key') :
            config('cpcash.test.secret_key');

        return tap($this, function () {
            static::$headers = array_merge_recursive(static::$headers, [
                'Accept' => 'application/json',
                'Authorization' => "Bearer " . static::$token
            ]);
        });
    }

    /**
     * @param string $uri
     * @return string
     */
    final public static function getUrl(string $uri): string
    {
        static::$url = config('cpcash.base_url');

        return trim(static::$url . 'wallets/' . $uri);
    }
}
