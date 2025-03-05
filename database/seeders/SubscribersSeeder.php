<?php

namespace Database\Seeders;

use App\Models\Field;
use App\Models\Subscriber;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class SubscribersSeeder extends Seeder
{
    const AVAILABLE_FIELDS = [
        'company' => 1,
        'country' => 2,
        'birthday' => 3,
        'newsletter' => 4,
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $companyField = Field::where('id', self::AVAILABLE_FIELDS['company'])->first();
        $countryField = Field::where('id', self::AVAILABLE_FIELDS['country'])->first();
        $birthdayField = Field::where('id', self::AVAILABLE_FIELDS['birthday'])->first();
        $newsletterField = Field::where('id', self::AVAILABLE_FIELDS['newsletter'])->first();

        Subscriber::factory()
            ->count(5)
            ->withState('active')
            ->withFieldValues([
                ['fields' => $companyField, 'value' => $faker->company()],
                ['fields' => $countryField, 'value' => $faker->country()],
                ['fields' => $birthdayField, 'value' => $faker->date('Y-m-d', '-20 years')],
                ['fields' => $newsletterField, 'value' => $faker->boolean()],
            ])->create();
    }
}
