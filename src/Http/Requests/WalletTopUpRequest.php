<?php

namespace CredPal\CPCash\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WalletTopUpRequest extends FormRequest
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
            'amount' => 'required|numeric|min:99',
            'provider' => 'required|string|max:50',
            'reference' => 'required_without:card_id|string',
            'description' => 'nullable',
            'card_id' => ['required_without:reference', Rule::exists('personal_repayment_cards', 'id')]
        ];
    }

    public function messages()
    {
        return [
            'amount.min' => 'Minumum topup amount should be 100 and above'
        ];
    }
}
