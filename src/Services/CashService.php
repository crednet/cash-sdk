<?php

namespace CredPal\CPCash\Services;

use CredPal\CPCash\Contracts\CPCash;
use CredPal\CPCash\Exceptions\CPCashException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use CredPal\CPCash\Exceptions\InternalServerException;
use Symfony\Component\HttpFoundation\Response;

class CashService implements CPCash
{
    /**
     * @var string
     */
    protected static string $token;

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
     * @throws InternalServerException
     */
    public function createWallet()
    {
        return static::handleResponse(Http::withHeaders(static::$headers)->post(static::getUrl('wallets')));
    }

    /**
     * @return mixed
     * @throws CPCashException
     * @throws InternalServerException
     */
    public function getWallets()
    {
        return static::handleResponse(Http::withHeaders(static::$headers)->get(static::getUrl('wallets')));
    }

    /**
     * @description get wallet details
     * @param string|int $walletId
     * @return array|mixed
     * @throws CPCashException
     * @throws InternalServerException
     */
    public function getWallet($walletId): array
    {
        return static::handleResponse(
            Http::withHeaders(static::$headers)->get(static::getUrl("wallets/{$walletId}"))
        );
    }

    /**
     * @param string|int $walletId
     * @param int|null $page
     * @return mixed
     * @throws CPCashException
     * @throws InternalServerException
     */
    public function getWalletTransactions($walletId, ?int $page = 1)
    {
        return static::handleResponse(
            Http::withHeaders(static::$headers)
                ->get(static::getUrl("wallets/{$walletId}/transactions?page={$page}"))
        );
    }

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
    public function walletTopUp(
        string $walletId,
        $amount,
        string $provider,
        string $reference,
        string $description
    ): array
    {
        $response = Http::withHeaders(static::$headers)->post(static::getUrl("wallets/{$walletId}/top-up"), [
            'amount' => $amount,
            'provider' => $provider,
            'reference' => $reference,
            'description' => $description
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
     * @throws InternalServerException
     */
    public function withdrawFromWallet(string $walletId, string $amount, string $description): array
    {
        $response = Http::withHeaders(static::$headers)->post(static::getUrl("wallets/{$walletId}/withdraw"), [
            'amount' => $amount,
            'description' => $description,
        ]);

        return static::handleResponse($response);
    }

    /**
     * @param string $walletId
     * @return array|mixed
     * @throws CPCashException
     * @throws InternalServerException
     */
    public function lockWallet(string $walletId): array
    {
        return static::handleResponse(
            Http::withHeaders(static::$headers)->post(static::getUrl("wallets/{$walletId}/lock"))
        );
    }

    /**
     * @param string $walletId
     * @return array|mixed
     * @throws CPCashException
     * @throws InternalServerException
     */
    public function unlockWallet(string $walletId): array
    {
        return static::handleResponse(
            Http::withHeaders(static::$headers)->post(static::getUrl("wallets/{$walletId}/unlock"))
        );
    }

    /**
     * @return mixed
     * @throws CPCashException
     * @throws InternalServerException
     */
    public function getProviders()
    {
        return static::handleResponse(
            Http::withHeaders(static::$headers)->get(static::getUrl("providers"))
        );
    }

    /**
     * @param mixed $response
     * @return mixed
     * @throws CPCashException
     * @throws InternalServerException
     */
    final protected static function handleResponse($response)
    {
        if ($response->failed()) {
            static::handleErrorResponse($response);
        }

        return $response->json('data');
    }

    /**
     * @description Transform Error Response
     * @param mixed $response
     * @throws CPCashException
     * @throws InternalServerException
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
            throw new CPCashException(trans('cpcash::exception.wallet-not-found'));
        }

        throw new CPCashException($response['message']);
    }

    /**
     * @return void
     */
    public function setHeaders(): void
    {
        static::$token = app()->environment('production') ?
            config('cpcash.live.secret_key') :
            config('cpcash.test.secret_key');

        static::$headers = [
            'Accept' => 'application/json',
            'Authorization' => "Bearer " . static::$token
        ];
    }

    /**
     * @param string $uri
     * @return string
     */
    final public static function getUrl(string $uri): string
    {
        static::$url = config('cpcash.base_url');

        return static::$url . $uri;
    }
}
