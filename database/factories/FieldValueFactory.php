<?php

namespace Database\Factories;

use App\Models\Field;
use App\Models\FieldValue;
use App\Models\Subscriber;
use Illuminate\Database\Eloquent\Factories\Factory;
class FieldValueFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FieldValue::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'subscriber_id' => Subscriber::factory(),
            'field_id' => Field::factory(),
            'value' => $this->faker->word(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Set a specific value for the field value.
     */
    public function withValue(string $value): self
    {
        return $this->state(function (array $attributes) use ($value) {
            return [
                'value' => $value
            ];
        });
    }

    /**
     * Set a specific field for the field value.
     */
    public function forField(Field $field): self
    {
        return $this->state(function (array $attributes) use ($field) {
            return [
                'field_id' => $field->id
            ];
        });
    }

    /**
     * Set a specific subscriber for the field value.
     */
    public function forSubscriber(Subscriber $subscriber): self
    {
        return $this->state(function (array $attributes) use ($subscriber) {
            return [
                'subscriber_id' => $subscriber->id
            ];
        });
    }
}
