<?php

namespace CredPal\CPCash\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class NotFoundException extends Exception
{
    /**
     * NotFoundException constructor.
     * @param string|null $message
     * @param int $code
     */
    public function __construct(?string $message, int $code = Response::HTTP_NOT_FOUND)
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
