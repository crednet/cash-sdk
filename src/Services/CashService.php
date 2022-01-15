<?php

namespace CredPal\CPCash\Services;

use CredPal\CPCash\Contracts\CPCash;
use CredPal\CPCash\Exceptions\CPCashException;
use CredPal\CPCash\Exceptions\NotFoundException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use CredPal\CPCash\Exceptions\InternalServerException;
use Symfony\Component\HttpFoundation\Response;

class CashService implements CPCash
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
        return static::handleResponse($this->sendRequest()->post(static::getUrl('wallets')));
    }

    /**
     * @return mixed
     * @throws CPCashException
     * @throws InternalServerException|NotFoundException
     */
    public function getWallets()
    {
        return static::handleResponse($this->sendRequest()->get(static::getUrl('wallets')));
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
        return static::handleResponse($this->sendRequest()->get(static::getUrl("wallets/{$walletId}")));
    }

    /**
     * @param string|int $walletId
     * @param int|string|array|null $page
     * @return mixed
     * @throws CPCashException
     * @throws InternalServerException|NotFoundException
     */
    public function getWalletTransactions($walletId, $page = 1)
    {
        return static::handleResponse(
            $this->sendRequest()->get(static::getUrl("wallets/{$walletId}/transactions?page={$page}"))
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
        $response = $this->sendRequest()->post(static::getUrl("wallets/{$walletId}/top-up"), [
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
        $response = $this->sendRequest()->post(static::getUrl("wallets/{$walletId}/top-up-reference"), [
            'amount' => $amount,
            'provider' => $provider,
            'reference' => $reference,
            'description' => $description
        ]);

        return static::handleResponse($response);
    }

    /**
     * @description Topup wallet with reward | referral | invest cashbacks
     * @param string $walletId
     * @param string $amount
     * @param string $description
     * @return array|mixed
     * @throws CPCashException
     * @throws InternalServerException
     */
    public function walletTopUpWithReward(
        string $walletId,
        $amount,
        string $description,
        string $category
    ) {
        $response = $this->sendRequest()->post(static::getUrl("wallets/{$walletId}/top-up-reward"), [
            'amount' => $amount,
            'provider' => $$description,
            'reference' => $category,
        ]);

        return static::handleResponse($response);
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
        $response = $this->sendRequest()->post(static::getUrl("wallets/{$walletId}/withdraw"), [
            'amount' => $amount,
            'description' => $description,
            'category' => 'withdrawal'
        ]);

        return static::handleResponse($response);
    }

    /**
     * @param string $walletId
     * @return array|mixed
     * @throws CPCashException
     * @throws InternalServerException|NotFoundException
     */
    public function lockWallet(string $walletId)
    {
        return static::handleResponse($this->sendRequest()->post(static::getUrl("wallets/{$walletId}/lock")));
    }

    /**
     * @param string $walletId
     * @return array|mixed
     * @throws CPCashException
     * @throws InternalServerException|NotFoundException
     */
    public function unlockWallet(string $walletId)
    {
        return static::handleResponse($this->sendRequest()->post(static::getUrl("wallets/{$walletId}/unlock")));
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
     * @return PendingRequest
     */
    public function sendRequest(): PendingRequest
    {
        return Http::timeout(60)->withHeaders(static::$headers);
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

        return $response->json('data')['data'];
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
        if ($response->status() >= Response::HTTP_INTERNAL_SERVER_ERROR) {
            Log::info($response);
            throw new InternalServerException(trans('cpcash::exception.internal-error'));
        }

        if ($response->status() === Response::HTTP_UNAUTHORIZED) {
            throw new CPCashException(trans('cpcash::exception.unauthorized'));
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

        return trim(static::$url . $uri);
    }
}
