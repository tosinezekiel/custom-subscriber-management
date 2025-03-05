<?php

namespace Tests\Feature;

use App\Models\Field;
use App\Models\FieldValue;
use App\Models\Subscriber;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FieldControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function it_can_fetch_all_fields()
    {
        Field::factory()->count(3)->create();

        $response = $this->getJson('/api/fields');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data'
            ])
            ->assertJson(['status' => 'Success']);

        $this->assertCount(3, $response->json('data'));
    }

    /** @test */
    public function it_can_create_a_new_field()
    {
        $fieldData = [
            'title' => $this->faker->word(),
            'type' => 'string'
        ];

        $response = $this->postJson('/api/fields', $fieldData);

        $response->assertStatus(201)
            ->assertJson([
                'status' => 'Success',
                'data' => $fieldData
            ]);

        $this->assertDatabaseHas('fields', $fieldData);
    }

    /** @test */
    public function it_can_fetch_a_specific_field()
    {
        $field = Field::factory()->create();

        $response = $this->getJson("/api/fields/{$field->id}");

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'Success',
                'data' => [
                    'id' => $field->id,
                    'title' => $field->title,
                    'type' => $field->type,
                ]
            ]);
    }

    /** @test */
    public function it_can_update_an_existing_field()
    {
        $field = Field::factory()->create();
        $updatedTitle = $this->faker->word();

        $updateData = [
            'title' => $updatedTitle,
            'type' => $field->type
        ];

        $response = $this->putJson("/api/fields/{$field->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'Success',
                'data' => $updateData
            ]);

        $this->assertDatabaseHas('fields', $updateData);
    }

    /** @test */
    public function it_can_delete_a_field()
    {
        $field = Field::factory()->create();

        $response = $this->deleteJson("/api/fields/{$field->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('fields', ['id' => $field->id]);
    }

    /** @test */
    public function it_returns_404_for_non_existent_field()
    {
        $response = $this->getJson('/api/fields/999');

        $response->assertStatus(404)
            ->assertJson([
                'status' => 'Error',
            ]);
    }

    /** @test */
    public function it_validates_field_type_is_required()
    {
        $fieldData = [
            'title' => $this->faker->word(),
            'type' => ''
        ];

        $response = $this->postJson('/api/fields', $fieldData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['type']);
    }

    /** @test */
public function it_can_update_subscriber_field_values()
{
    $subscriber = Subscriber::factory()->create();
    $field = Field::factory()->create(['title' => 'Company', 'type' => 'string']);

    $newCompany = $this->faker->company();

    FieldValue::create([
        'subscriber_id' => $subscriber->id,
        'field_id' => $field->id,
        'value' => $this->faker->company(),
    ]);

    $updateData = [
        'fields' => [
            [
                'field_id' => $field->id,
                'value' => $newCompany,
            ]
        ]
    ];

    $response = $this->putJson("/api/subscribers/{$subscriber->id}", $updateData);

    $this->assertDatabaseHas('field_values', [
        'subscriber_id' => $subscriber->id,
        'field_id' => $field->id,
        'value' => $newCompany,
    ]);
}

/** @test */
public function it_validates_fields_based_on_type_when_updating()
{
    $subscriber = Subscriber::factory()->create();
    $field = Field::factory()->create(['title' => 'Age', 'type' => 'number']);

    $invalidData = [
        'fields' => [
            [
                'field_id' => $field->id,
                'value' => 'invalid-age',
            ]
        ]
    ];

    $response = $this->putJson("/api/subscribers/{$subscriber->id}", $invalidData);

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['fields.0.value']);
}
}
