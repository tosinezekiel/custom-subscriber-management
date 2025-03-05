<?php

namespace App\Services;

use App\DTOs\Field\CreateFieldDTO;
use App\DTOs\Field\UpdateFieldDTO;
use App\DTOs\Field\FieldFilterDTO;
use App\Exceptions\FieldInUseException;
use App\Models\Field;
use Illuminate\Support\Facades\DB;

class FieldService
{
    /**
     * Get all fields with optional filtering.
     *
     * @param FieldFilterDTO $filterDTO
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getAllFields(FieldFilterDTO $filterDTO)
    {
        $query = Field::query();

        $query->when($filterDTO->search, function($q) use ($filterDTO) {
            return $q->where('title', 'like', "%{$filterDTO->search}%");
        });

        $query->when($filterDTO->type, function($q) use ($filterDTO) {
            return $q->where('type', $filterDTO->type);
        });

        return $query->paginate($filterDTO->perPage);
    }

    /**
     * Find a fields by ID.
     *
     * @param int $id
     * @return Field
     */
    public function findField($id)
    {
        return Field::findOrFail($id);
    }

    /**
     * Create a new fields.
     *
     * @param CreateFieldDTO $dto
     * @return Field
     */
    public function createField(CreateFieldDTO $dto): Field
    {
        $field = new Field();
        $field->title = $dto->title;
        $field->type = $dto->type;
        $field->save();

        return $field->fresh();
    }

    /**
     * Update a fields.
     *
     * @param Field $field
     * @param UpdateFieldDTO $dto
     * @return Field
     */
    public function updateField(Field $field, UpdateFieldDTO $dto): Field
    {
        if ($dto->title !== null) {
            $field->title = $dto->title;
        }

        if ($dto->type !== null) {
            $field->type = $dto->type;
        }

        $field->save();

        return $field->fresh();
    }

    /**
     * Delete a fields if it's not in use.
     *
     * @param Field $field
     * @return bool|string
     * @throws FieldInUseException
     */
    public function deleteField(Field $field): bool|string
    {
        if ($field->fieldValues()->exists()) {
            throw new FieldInUseException();
        }

        return $field->delete();
    }
}
