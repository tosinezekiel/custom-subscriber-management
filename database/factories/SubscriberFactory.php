<?php

namespace Database\Factories;

use App\Models\FieldValue;
use App\Models\Subscriber;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriberFactory extends Factory
{
    protected $model = Subscriber::class;

    public function definition(): array
    {
        return [
            'email' => $this->faker->unique()->safeEmail(),
            'name' => $this->faker->name(),
        ];
    }

    /**
     * Configure subscribers with fields values.
     *
     * @param array $fieldValues Array of ['fields' => Field, 'value' => string]
     */
    public function withFieldValues(array $fieldValues): self
    {
        return $this->afterCreating(function (Subscriber $subscriber) use ($fieldValues) {
            foreach ($fieldValues as $fieldValue) {
                FieldValue::create([
                    'subscriber_id' => $subscriber->id,
                    'field_id' => $fieldValue['fields']->id,
                    'value' => $fieldValue['value'],
                ]);
            }
        });
    }

    /**
     * Configure subscribers with a specific state.
     */
    public function withState(string $state): self
    {
        return $this->state(function (array $attributes) use ($state) {
            return [
                'state' => $state
            ];
        });
    }

    /**
     * Configure the subscribers as active.
     */
    public function active(): self
    {
        return $this->withState('active');
    }

    /**
     * Configure the subscribers as unsubscribed.
     */
    public function unsubscribed(): self
    {
        return $this->withState('unsubscribed');
    }

    /**
     * Configure the subscribers as junk.
     */
    public function junk(): self
    {
        return $this->withState('junk');
    }

    /**
     * Configure the subscribers as bounced.
     */
    public function bounced(): self
    {
        return $this->withState('bounced');
    }

    /**
     * Configure the subscribers as unconfirmed.
     */
    public function unconfirmed(): self
    {
        return $this->withState('unconfirmed');
    }
}
