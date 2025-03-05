<?php

namespace App\Http\Controllers\Api;

use App\DTOs\Field\CreateFieldDTO;
use App\DTOs\Field\FieldFilterDTO;
use App\DTOs\Field\UpdateFieldDTO;
use App\Exceptions\FieldInUseException;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFieldRequest;
use App\Http\Requests\UpdateFieldRequest;
use App\Http\Resources\FieldResource;
use App\Models\Field;
use App\Services\FieldService;
use App\Traits\JsonResponses;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FieldController extends Controller
{
    use JsonResponses;

    public function __construct(public FieldService $fieldService)
    {
    }

    /**
     * Display a listing of fields.
     */
    public function index(Request $request): JsonResponse
    {
        $filterDTO = FieldFilterDTO::fromRequest($request);
        $fields = $this->fieldService->getAllFields($filterDTO);
        return $this->success($fields, "Fields retrieved successfully.");
    }

    /**
     * Store a newly created fields.
     */
    public function store(StoreFieldRequest $request): JsonResponse
    {
        $fieldDTO = CreateFieldDTO::fromRequest($request);
        $field = $this->fieldService->createField($fieldDTO);
        return $this->success(
            new FieldResource($field), 
            "Field created successfully.", 
            201
        );
    }

    /**
     * Display the specified fields.
     * @throws ModelNotFoundException
     */
    public function show(Field $field): JsonResponse
    {
        return $this->success(new FieldResource($field));
    }

    /**
     * Update the specified fields.
     */
    public function update(UpdateFieldRequest $request, Field $field): JsonResponse
    {
        $fieldDTO = UpdateFieldDTO::fromRequest($request);

        if (!$fieldDTO->hasUpdates()) {
            return $this->error('No updates provided', 422);
        }

        $updatedField = $this->fieldService->updateField($field, $fieldDTO);
        return $this->success(new FieldResource($updatedField));
    }

    /**
     * Remove the specified fields.
     * @throws FieldInUseException
     */
    public function destroy(Field $field): JsonResponse
    {
        $this->fieldService->deleteField($field);

        return $this->success(
            NULL, 
            message: "Field deleted successfully.", 
            code: 204);
    }
}
