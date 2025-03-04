<?php

namespace App\DTOs\Field;

use App\Http\Requests\StoreFieldRequest;

final readonly class CreateFieldDTO
{
    public function __construct(
        public readonly string $title,
        public readonly string $type
    ) {
    }

    public static function fromRequest(StoreFieldRequest $request): self
    {
        return new self(
            title: $request->input('title'),
            type: $request->input('type')
        );
    }
}
