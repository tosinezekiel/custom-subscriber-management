<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait JsonResponses
{
    /**
     * Return a success JSON response.
     *
     * @param array $data
     * @param string|null $message
     * @param int $code
     * @return JsonResponse
     */
    protected function success(array $data = [], string $message = NULL, int $code = 200): JsonResponse
    {
        return response()->json([
            'status' => 'Success',
            'message' => $message,
            'data' => $data
        ], $code);
    }

    /**
     * Return an error JSON response.
     *
     * @param string|null $message
     * @param int $code
     * @param array|null $data
     * @return JsonResponse
     */
    protected function error(
        string       $message = null,
        int          $code = 400,
        ?array $data = NULL): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'data' => $data
        ], $code);
    }
}
