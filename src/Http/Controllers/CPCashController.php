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
            'Wallet Created Successfully',
            Response::HTTP_CREATED
        );
    }
    /**
     * @return JsonResponse
     */
    public function getWallets(): JsonResponse
    {
        return $this->successResponse(CPCash::getWallets(), 'All Wallet Fetched Successfully');
    }

    /**
     * @param string|int $walletId
     * @return JsonResponse
     */
    public function getWallet($walletId): JsonResponse
    {
        return $this->successResponse(CPCash::getWallet($walletId), 'Wallet Details');
    }

    /**
     * @param string|int $walletId
     * @return JsonResponse
     */
    public function getWalletTransactions($walletId): JsonResponse
    {
        return $this->successResponse(
            CPCash::getWalletTransactions($walletId, request()->query('page')),
            'Wallet Transactions'
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
            'Wallet TopUp Successfully'
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
            'Wallet Withdrawal Successful'
        );
    }

    /**
     * @param int|string $walletId
     * @return JsonResponse
     */
    public function lockWallet($walletId): JsonResponse
    {
        return $this->successResponse(CPCash::lockWallet($walletId), 'Wallet Locked Successfully');
    }

    /**
     * @param int|string $walletId
     * @return JsonResponse
     */
    public function unlockWallet($walletId): JsonResponse
    {
        return $this->successResponse(CPCash::unlockWallet($walletId), 'Wallet Unlocked Successfully');
    }

    /**
     * @return JsonResponse
     */
    public function getWalletProviders(): JsonResponse
    {
        return $this->successResponse(CPCash::getProviders(), 'Wallet Payment Providers');
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
