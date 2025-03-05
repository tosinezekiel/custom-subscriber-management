<?php

namespace App\Services;

use App\DTOs\Subscriber\CreateSubscriberDTO;
use App\DTOs\Subscriber\SubscriberFilterDTO;
use App\DTOs\Subscriber\UpdateSubscriberDTO;
use App\Models\FieldValue;
use App\Models\Subscriber;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class SubscriberService
{
    /**
     * Get all subscribers with optional filtering.
     *
     * @param SubscriberFilterDTO $filterDTO
     * @return LengthAwarePaginator
     */
    public function getAllSubscribers(SubscriberFilterDTO $filterDTO): LengthAwarePaginator
    {
        $query = Subscriber::query();

        $query->when($filterDTO->search, function($q) use ($filterDTO) {
            return $q->where(function($subQuery) use ($filterDTO) {
                $subQuery->where('email', 'like', "%{$filterDTO->search}%")
                    ->orWhere('name', 'like', "%{$filterDTO->search}%");
            });
        });

        $query->when($filterDTO->state, function($q) use ($filterDTO) {
            return $q->where('state', $filterDTO->state);
        });

        return $query->paginate($filterDTO->perPage);
    }

    /**
     * Create a new subscribers with fields values.
     *
     * @param CreateSubscriberDTO $dto
     * @return Subscriber
     */
    public function createSubscriber(CreateSubscriberDTO $dto): Subscriber
    {
        return DB::transaction(function () use ($dto) {
            $subscriber = Subscriber::create([
                'email' => $dto->email,
                'name' => $dto->name,
                'state' => $dto->state,
            ]);

            if ($dto->fields->isNotEmpty()) {
                $this->saveFieldValues($subscriber, $dto->fields);
            }

            return $subscriber->fresh();
        });
    }

    /**
     * Update a subscribers with fields values.
     *
     * @param Subscriber $subscriber
     * @param UpdateSubscriberDTO $dto
     * @return Subscriber
     */
    public function updateSubscriber(Subscriber $subscriber, UpdateSubscriberDTO $dto): Subscriber
    {
        return DB::transaction(function () use ($subscriber, $dto) {
            $subscriber->update(array_filter([
                'email' => $dto->email,
                'name' => $dto->name,
                'state' => $dto->state,
            ]));

            if ($dto->fields->isNotEmpty()) {
                $this->saveFieldValues($subscriber, $dto->fields);
            }

            return $subscriber->fresh();
        });
    }

    /**
     * Delete a subscribers.
     *
     * @param Subscriber $subscriber
     * @return bool
     */
    public function deleteSubscriber(Subscriber $subscriber): bool
    {
        return $subscriber->delete();
    }

    /**
     * Save fields values for a subscribers.
     *
     * @param Subscriber $subscriber
     * @param array $fields
     * @return void
     */
    private function saveFieldValues(Subscriber $subscriber, Collection $fields): void
    {
        collect($fields)->filter(function ($field) {
            return isset($field['value']) && $field['value'] !== '';
        })->each(function ($field) use ($subscriber) {
            FieldValue::updateOrCreate(
                [
                    'subscriber_id' => $subscriber->id,
                    'field_id' => $field['field_id'],
                ],
                ['value' => $field['value']]
            );
        });
    }
}
