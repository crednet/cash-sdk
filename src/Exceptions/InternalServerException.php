<?php

namespace CredPal\CPCash\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class InternalServerException extends Exception
{
    /**
     * InternalServerException constructor.
     * @param string|null $message
     * @param int $code
     */
    public function __construct(?string $message, int $code = 500)
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
            'message' => $this->message ?? trans('cpcash::exception.internal-error'),
        ], $this->code);
    }
}
