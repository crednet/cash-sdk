<?php

namespace CredPal\CPCash\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use CredPal\CPCash\Facades\CPCash;
use Symfony\Component\HttpFoundation\Response;

class VirtualAccountController extends Controller
{
    /**
     * int|string $walletId
     * @return JsonResponse
     */
    public function __invoke($walletId, Request $request): JsonResponse
    {
        $request->validate($this->rules());

        $response = CPCash::transferFunds(
            $walletId,
            $request->input('amount'),
            $request->input('account_number'),
            $request->input('bank_name'),
            $request->input('description'),
            $request->input('reference')
        );

        return $this->successResponse(
            $response,
            trans('Transfer Successful'),
            Response::HTTP_OK,
        );
    }

    /**
     * Get the bvn validation rules.
     *
     * @return array
     */
    protected function rules(): array
    {
        return [
            'amount' => ['required', 'numeric'],
            'account_number' => ['required', 'digits:10'],
            'bank_name' => ['required', 'numeric'],
            'description' => ['required', 'string'],
            'reference' => ['nullable'],
        ];
    }
}
