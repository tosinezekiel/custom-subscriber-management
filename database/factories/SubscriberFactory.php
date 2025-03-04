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
     * Configure subscriber with field values.
     *
     * @param array $fieldValues Array of ['field' => Field, 'value' => string]
     */
    public function withFieldValues(array $fieldValues): self
    {
        return $this->afterCreating(function (Subscriber $subscriber) use ($fieldValues) {
            foreach ($fieldValues as $fieldValue) {
                FieldValue::create([
                    'subscriber_id' => $subscriber->id,
                    'field_id' => $fieldValue['field']->id,
                    'value' => $fieldValue['value'],
                ]);
            }
        });
    }

    /**
     * Configure subscriber with a specific state.
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
     * Configure the subscriber as active.
     */
    public function active(): self
    {
        return $this->withState('active');
    }

    /**
     * Configure the subscriber as unsubscribed.
     */
    public function unsubscribed(): self
    {
        return $this->withState('unsubscribed');
    }

    /**
     * Configure the subscriber as junk.
     */
    public function junk(): self
    {
        return $this->withState('junk');
    }

    /**
     * Configure the subscriber as bounced.
     */
    public function bounced(): self
    {
        return $this->withState('bounced');
    }

    /**
     * Configure the subscriber as unconfirmed.
     */
    public function unconfirmed(): self
    {
        return $this->withState('unconfirmed');
    }
}
