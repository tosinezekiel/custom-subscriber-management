<?php

namespace Database\Factories;

use App\Models\Field;
use Illuminate\Database\Eloquent\Factories\Factory;

class FieldFactory extends Factory
{

    protected $model = Field::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = ['date', 'number', 'string', 'boolean'];

        return [
            'title' => $this->faker->unique()->word(),
            'type' => $this->faker->randomElement($types),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
