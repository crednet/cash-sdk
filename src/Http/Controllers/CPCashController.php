<?php

namespace CredPal\CPCash\Http\Controllers;

use CredPal\CPCash\Exceptions\CPCashException;
use CredPal\CPCash\Exceptions\NotFoundException;
use CredPal\CPCash\Facades\CPCash;
use CredPal\CPCash\Traits\WalletAccount;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CPCashController extends Controller
{
    use WalletAccount;

    /**
     * @return JsonResponse
     * @throws CPCashException
     * @throws NotFoundException
     */
    public function createWallet(): JsonResponse
    {
        $this->validateWalletRequest('create');

        $this->isWalletConditionPassed(request('user_id'));

        $response = CPCash::createWallet();

        return $this->successResponse(
            $this->storeWalletDetails($response['wallet'], request('user_id')),
            trans('cpcash::wallet.created'),
            Response::HTTP_CREATED
        );
    }
    /**
     * @return JsonResponse
     */
    public function getWallets(): JsonResponse
    {
        return $this->successResponse(CPCash::getWallets(), trans('cpcash::wallet.all'));
    }

    /**
     * @param string|int $walletId
     * @return JsonResponse
     */
    public function getWallet($walletId): JsonResponse
    {
        return $this->successResponse(CPCash::getWallet($walletId), trans('cpcash::wallet.details'));
    }

    /**
     * @param string|int $walletId
     * @return JsonResponse
     */
    public function getWalletTransactions($walletId): JsonResponse
    {
        return $this->successResponse(
            CPCash::getWalletTransactions($walletId, request()->query('page')),
            trans('cpcash::wallet.transactions')
        );
    }

    /**
     * @param string|int $walletId
     * @return JsonResponse
     */
    public function walletTopUp($walletId): JsonResponse
    {
        $this->validateWalletRequest();

        return $this->successResponse(
            CPCash::walletTopUp(
                $walletId,
                request('amount'),
                request('provider'),
                request('reference'),
                request('description')
            ),
            trans('cpcash::wallet.topup')
        );
    }

    /**
     * @param string|int $walletId
     * @return JsonResponse
     */
    public function withdrawFromWallet($walletId): JsonResponse
    {
        $this->validateWalletRequest('withdraw');

        return $this->successResponse(
            CPCash::withdrawFromWallet($walletId, request('amount'), request('description')),
            trans('cpcash::wallet.withdraw')
        );
    }

    /**
     * @param int|string $walletId
     * @return JsonResponse
     */
    public function lockWallet($walletId): JsonResponse
    {
        return $this->successResponse(CPCash::lockWallet($walletId), trans('cpcash::wallet.lock'));
    }

    /**
     * @param int|string $walletId
     * @return JsonResponse
     */
    public function unlockWallet($walletId): JsonResponse
    {
        return $this->successResponse(CPCash::unlockWallet($walletId), trans('cpcash::wallet.unlock'));
    }

    /**
     * @return JsonResponse
     */
    public function getWalletProviders(): JsonResponse
    {
        return $this->successResponse(CPCash::getProviders(), trans('cpcash::wallet.providers'));
    }

    /**
     * @param string $type
     * @return void
     */
    protected function validateWalletRequest(string $type = 'topup'): void
    {
        switch($type) {
            case 'topup':
                request()->validate([
                    'amount' => 'required|numeric',
                    'reference' => 'required|string',
                    'provider' => 'required|string',
                    'description' => 'required'
                ]);
                break;
            case 'withdraw':
                request()->validate(['amount' => 'required|numeric', 'description' => 'required']);
                break;
            case 'create':
                request()->validate(['user_id' => 'required']);
                break;
            default:
        }
    }
}
