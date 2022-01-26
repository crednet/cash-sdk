<?php

namespace CredPal\CPCash\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class CPCashException extends Exception
{
    /**
     * @param array $errors
     */
    protected array $errors = [];

    /**
     * InternalServerException constructor.
     * @param string $message
     * @param int $code
     * @param array $errors
     */
    public function __construct(string $message, int $code = 400, array $errors = [])
    {
        parent::__construct($message, $code);

        $this->errors = $errors;
    }

    /**
     * @return JsonResponse
     */
    public function render(): JsonResponse
    {
        $response = ['success' => false, 'message' => $this->message];
        count($this->errors) > 0 && $response['errors'] = $this->errors;

        return response()->json($response, $this->code);
    }
}
