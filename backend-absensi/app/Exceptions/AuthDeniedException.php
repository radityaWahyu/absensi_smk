<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AuthDeniedException extends Exception
{
    /**
     * Render the exception as an HTTP response.
     */
    public function render(Request $request): JsonResponse
    {
        return response()->json([
            'error' => true,
            'message' => $this->getMessage(),
        ], 403); // Unprocessable Entity status code
    }
}
