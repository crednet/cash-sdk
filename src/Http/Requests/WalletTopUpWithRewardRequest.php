<?php

namespace CredPal\CPCash\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WalletTopUpWithRewardRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'amount' => 'required|numeric',
            'description' => 'required|string',
            'category' => 'required|string',
        ];
    }
}
