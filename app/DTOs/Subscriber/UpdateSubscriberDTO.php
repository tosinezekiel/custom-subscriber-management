<?php

namespace App\DTOs\Subscriber;

use App\Http\Requests\UpdateSubscriberRequest;
use Illuminate\Support\Collection;

final readonly class UpdateSubscriberDTO
{
    /**
     * @param string|null $email
     * @param string|null $name
     * @param string|null $state
     * @param Collection<array{field_id: int, value: string}>|null $fields
     */
    public function __construct(
        public ?string $email = null,
        public ?string $name = null,
        public ?string $state = null,
        public Collection $fields = new Collection()
    ) {
    }

    public static function fromRequest(UpdateSubscriberRequest $request): self
    {
        return new self(
            email: $request->input('email'),
            name: $request->input('name'),
            state: $request->input('state'),
            fields: $request->has('fields') ? collect($request->input('fields')) : collect(),
        );
    }

    public function hasUpdates(): bool
    {
        return $this->email !== null ||
            $this->name !== null ||
            $this->state !== null ||
            $this->fields !== null;
    }
}
