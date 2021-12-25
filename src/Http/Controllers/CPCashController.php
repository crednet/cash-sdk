<?php

namespace CredPal\CPCash\Http\Controllers;

use CredPal\CPCash\Exceptions\{CPCashException, NotFoundException};
use CredPal\CPCash\Facades\CPCash;
use CredPal\CPCash\Traits\HasWalletAccount;
use Illuminate\Http\JsonResponse;
use CredPal\CPCash\Http\Requests\{CreateWalletRequest, WalletTopUpRequest, WalletWithdrawRequest};
use Symfony\Component\HttpFoundation\Response;

class CPCashController extends Controller
{
    use HasWalletAccount;

    /**
     * @return JsonResponse
     * @throws CPCashException
     * @throws NotFoundException
     */
    public function createWallet(CreateWalletRequest $request): JsonResponse
    {
        $this->isWalletConditionPassed($request->input('user_id'));

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
    public function walletTopUp($walletId, WalletTopUpRequest $request): JsonResponse
    {
        $card = $this->findCard($request->input('card_id'));

        return $this->successResponse(
            CPCash::walletTopUp(
                $walletId,
                $request->input('amount'),
                $request->input('provider'),
                $request->input('description'),
                $card->{'authorization_code'},
                $card->{'email'}
            ),
            trans('cpcash::wallet.topup')
        );
    }

    /**
     * @param string|int $walletId
     * @return JsonResponse
     */
    public function walletTopUpWithReference($walletId, WalletTopUpRequest $request): JsonResponse
    {
        return $this->successResponse(
            CPCash::walletTopUpWithReference(
                $walletId,
                $request->input('amount'),
                $request->input('provider'),
                $request->input('reference'),
                $request->input('description'),
            ),
            trans('cpcash::wallet.topup')
        );
    }

    /**
     * @param string|int $walletId
     * @return JsonResponse
     */
    public function withdrawFromWallet($walletId, WalletWithdrawRequest $request): JsonResponse
    {
        return $this->successResponse(
            CPCash::withdrawFromWallet($walletId, $request->input('amount'), $request->input('description')),
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
}
