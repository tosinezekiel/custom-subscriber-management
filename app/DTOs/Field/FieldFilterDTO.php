<?php

namespace App\DTOs\Field;

use Illuminate\Http\Request;

final readonly class FieldFilterDTO
{
    public function __construct(
        public ?string $search = null,
        public ?string $type = null,
        public int $perPage = 10
    ) {
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            search: $request->input('search'),
            type: $request->input('type'),
            perPage: $request->input('per_page', 10)
        );
    }
}
