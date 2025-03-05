<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

trait JsonResponses
{
    /**
     * Return a success JSON response.
     *
     * @param JsonResource|LengthAwarePaginator|NULL $data
     * @param string|null $message
     * @param int $code
     * @return JsonResponse
     */
    protected function success(JsonResource|LengthAwarePaginator|NULL $data, ?string $message = null, int $code = 200): JsonResponse
    {
        if ($data instanceof LengthAwarePaginator) {
            return response()->json([
                'status' => 'Success',
                'message' => $message,
                'pagination' => [
                    'current_page' => $data->currentPage(),
                    'per_page' => $data->perPage(),
                    'total' => $data->total(),
                    'last_page' => $data->lastPage(),
                    'next_page_url' => $data->nextPageUrl(),
                    'prev_page_url' => $data->previousPageUrl(),
                ],
                'data' => $data->items(),
            ], $code);
        }

        return response()->json([
            'status' => 'Success',
            'message' => $message,
            'data' => $data,
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
    protected function error(?string $message = null, int $code = 400, ?array $data = null): JsonResponse
    {
        return response()->json([
            'status' => 'Error',
            'message' => $message,
            'data' => $data
        ], $code);
    }
}
