<?php

namespace Tests\Feature;

use App\Models\Subscriber;
use App\Models\Field;
use App\Models\FieldValue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SubscriberControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function it_can_fetch_all_subscribers()
    {
        Subscriber::factory()->count(3)->create();

        $response = $this->getJson('/api/subscribers');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data'
            ])
            ->assertJson(['status' => 'Success']);

        $responseData = $response->json('data');

        $this->assertCount(3, $responseData['data']);
    }

    /** @test */
    public function it_can_create_a_new_subscriber()
    {
        $field = Field::factory()->create(['title' => 'Company', 'type' => 'string']);
        [$email, $name, $state] = $this->makeActiveSubscriber();

        $company = $this->faker->company();

        $subscriberData = [
            'email' => $email,
            'name' => $name,
            'state' => $state,
            'fields' => [
                [
                    'field_id' => $field->id,
                    'value' => $company,
                ]
            ]
        ];

        $response = $this->postJson('/api/subscribers', $subscriberData);

        $response->assertStatus(201)
            ->assertJson([
                'status' => 'Success',
                'data' => [
                    'email' => $email,
                    'name' => $name,
                    'state' => $state,
                ]
            ]);

        $this->assertDatabaseHas('subscribers', [
            'email' => $email,
            'name' => $name,
            'state' => $state,
        ]);

        $subscriber = Subscriber::where('email', $email)->first();

        $this->assertDatabaseHas('field_values', [
            'subscriber_id' => $subscriber->id,
            'field_id' => $field->id,
            'value' => $company,
        ]);
    }

    /** @test */
    public function it_can_fetch_a_specific_subscriber_with_field_values()
    {
        [$email, $name, $state] = $this->makeActiveSubscriber();
        $subscriber = Subscriber::factory()->create([
            'email' => $email,
            'name' => $name,
            'state' => $state,
        ]);

        $field = Field::factory()->create(['title' => 'Company', 'type' => 'string']);

        FieldValue::create([
            'subscriber_id' => $subscriber->id,
            'field_id' => $field->id,
            'value' => $this->faker->company(),
        ]);

        $response = $this->getJson("/api/subscribers/{$subscriber->id}");

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'Success',
                'data' => [
                    'id' => $subscriber->id,
                    'email' => $email,
                    'name' => $name,
                    'state' => $state,
                ]
            ]);

        $responseData = $response->json('data');
        $this->assertArrayHasKey('field_values', $responseData);
    }

    /** @test */
    public function it_returns_404_for_non_existent_subscriber()
    {
        $response = $this->getJson('/api/subscribers/999');

        $response->assertStatus(404)
            ->assertJson([
                'status' => 'Error',
            ]);
    }

    /** @test */
    public function it_can_update_an_existing_subscriber()
    {
        [$email, $name] = $this->makeActiveSubscriber();

        $unsubscribed = 'unsubscribed';

        $subscriber = Subscriber::factory()->create([
            'email' => $this->faker->unique()->safeEmail(),
            'name' => $this->faker->name(),
            'state' => 'active'
        ]);

        $updateData = [
            'email' => $email,
            'name' => $name,
            'state' => $unsubscribed,
        ];

        $response = $this->putJson("/api/subscribers/{$subscriber->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'Success',
                'data' => [
                    'email' => $email,
                    'name' => $name,
                    'state' => $unsubscribed,
                ]
            ]);

        $this->assertDatabaseHas('subscribers', [
            'id' => $subscriber->id,
            'email' => $email,
            'name' => $name,
            'state' => $unsubscribed,
        ]);
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
    public function it_can_delete_a_subscriber()
    {
        $subscriber = Subscriber::factory()->create();

        $response = $this->deleteJson("/api/subscribers/{$subscriber->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('subscribers', ['id' => $subscriber->id]);
    }

    /** @test */
    public function it_deletes_field_values_when_subscriber_is_deleted()
    {
        $subscriber = Subscriber::factory()->create();
        $field = Field::factory()->create();

        $fieldValue = FieldValue::create([
            'subscriber_id' => $subscriber->id,
            'field_id' => $field->id,
            'value' => $this->faker->company(),
        ]);

        $this->deleteJson("/api/subscribers/{$subscriber->id}");

        $this->assertDatabaseMissing('field_values', [
            'id' => $fieldValue->id
        ]);
    }

    /** @test */
    public function it_validates_subscriber_state_must_be_valid()
    {
        [$email, $name] = $this->makeActiveSubscriber();
        $subscriberData = [
            'email' => $email,
            'name' => $name,
            'state' => 'invalid-state'
        ];

        $response = $this->postJson('/api/subscribers', $subscriberData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['state']);
    }

    /** @test */
    public function it_can_filter_subscribers_by_state()
    {
        Subscriber::factory()->active()->count(2)->create();
        Subscriber::factory()->unsubscribed()->count(2)->create();

        $response = $this->getJson('/api/subscribers?state=active');

        $response->assertStatus(200);
        $responseData = $response->json('data');
        $this->assertCount(2, $responseData['data']);
    }

    /**
     * @return array
     */
    public function makeActiveSubscriber(): array
    {
        $email = $this->faker->unique()->safeEmail();
        $name = $this->faker->name();
        $state = 'active';
        return [$email, $name, $state];
    }
}
