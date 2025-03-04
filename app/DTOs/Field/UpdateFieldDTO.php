<?php

namespace App\DTOs\Field;

use App\Http\Requests\UpdateFieldRequest;
use Illuminate\Http\Request;

final readonly class UpdateFieldDTO
{
    public function __construct(
        public readonly ?string $title = null,
        public readonly ?string $type = null
    ) {
    }

    public static function fromRequest(UpdateFieldRequest $request): self
    {
        return new self(
            title: $request->input('title'),
            type: $request->input('type')
        );
    }

    public function hasUpdates(): bool
    {
        return $this->title !== null || $this->type !== null;
    }
}
