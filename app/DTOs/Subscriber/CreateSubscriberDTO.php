<?php

namespace App\DTOs\Subscriber;

use App\Http\Requests\StoreSubscriberRequest;
use Illuminate\Support\Collection;

final readonly class CreateSubscriberDTO
{
    /**
     * @param string $email
     * @param string $name
     * @param string $state
     * @param Collection<array{field_id: int, value: string}> $fields
     */
    public function __construct(
        public string     $email,
        public string     $name,
        public string     $state,
        public Collection $fields = new Collection()
    ) {
    }

    public static function fromRequest(StoreSubscriberRequest $request): self
    {
        return new self(
            email: $request->input('email'),
            name: $request->input('name'),
            state: $request->input('state'),
            fields: $request->has('fields') ? collect($request->input('fields')) : collect(),
        );
    }

    public function toArray(): array
    {
        return [
            'email' => $this->email,
            'name' => $this->name,
            'state' => $this->state,
            'fields' => $this->fields->toArray()
        ];
    }
}
