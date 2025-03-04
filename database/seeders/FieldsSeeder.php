<?php

namespace Database\Seeders;

use App\Models\Field;
use Illuminate\Database\Seeder;

class FieldsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $predefinedFields = [
            ['title' => 'Company', 'type' => 'string'],
            ['title' => 'Country', 'type' => 'string'],
            ['title' => 'Birthday', 'type' => 'date'],
            ['title' => 'Subscribed to newsletter', 'type' => 'boolean'],
        ];

        foreach ($predefinedFields as $fieldData) {
            Field::factory()->state($fieldData)->create();
        }


    }
}
