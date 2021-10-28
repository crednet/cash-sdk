<?php

namespace CredPal\CPCash\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class CPCashException extends Exception
{
    /**
     * InternalServerException constructor.
     * @param string $message
     * @param int $code
     */
    public function __construct(string $message, int $code = 400)
    {
        parent::__construct($message, $code);
    }

    /**
     * @return JsonResponse
     */
    public function render(): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $this->message
        ], $this->code);
    }
}
