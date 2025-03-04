<?php

namespace App\DTOs\Subscriber;

use Illuminate\Http\Request;

final readonly class SubscriberFilterDTO
{
    public function __construct(
        public ?string $search = null,
        public ?string $state = null,
        public ?int $perPage = 10
    ) {
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            search: $request->input('search'),
            state: $request->input('state'),
            perPage: $request->input('per_page', 10)
        );
    }

    public function toArray(): array
    {
        return [
            'search' => $this->search,
            'state' => $this->state,
            'perPage' => $this->perPage
        ];
    }
}
