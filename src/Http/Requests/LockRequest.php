<?php

namespace CredPal\CPCash\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LockRequest extends FormRequest
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
			'pnd_type' => 'required',
			'pnd_description' => 'required',
			'pnd_expiration' => 'sometimes|date|after_or_equal:tomorrow',
		];
	}
}