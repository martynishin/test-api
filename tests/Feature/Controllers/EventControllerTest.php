<?php

namespace Tests\Feature\Controllers;

use App\Models\Authorization;
use App\Models\Event;
use Illuminate\Database\Eloquent\Builder;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class EventControllerTest extends TestCase
{
    /**
     * @return void
     */
    public function test_it_returns_unauthorized_if_missed_token(): void
    {
        $this->getJson('/api/list')->assertUnauthorized();
    }

    /**
     * @return void
     */
    public function test_it_can_return_events_for_a_specific_organization(): void
    {
        $organization = Authorization::query()->whereEmail('sony@example.com')->first();

        Sanctum::actingAs($organization);

        $this->getJson('/api/list')
            ->assertOk()
            ->assertJsonCount(3)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'event_title',
                    'event_start_date',
                    'event_end_date',
                    'organization_id',
                ],
            ])
            ->assertJsonFragment([
                'organization_id' => 2
            ]);
    }

    /**
     * @return void
     */
    public function test_it_returns_access_denied_for_event_not_of_this_organization(): void
    {
        $organization = Authorization::query()->whereEmail('sony@example.com')->first();

        Sanctum::actingAs($organization);

        $event = Event::query()
            ->whereHas('organization', fn(Builder $query) => $query->whereEmail('netflix@example.com'))
            ->first();

        $this->getJson("/api/{$event->id}")->assertForbidden();
    }

    /**
     * @return void
     */
    public function test_it_can_return_event_by_id(): void
    {
        $organization = Authorization::query()->whereEmail('sony@example.com')->first();

        Sanctum::actingAs($organization);

        $event = $organization->events->first();

        $this->getJson("/api/{$event->id}")
            ->assertOk()
            ->assertExactJson($event->toArray());
    }

    /**
     * @return void
     */
    public function test_it_can_replace_event(): void
    {
        $organization = Authorization::query()->whereEmail('sony@example.com')->first();

        Sanctum::actingAs($organization);

        $event = $organization->events->first();

        $this->putJson("/api/{$event->id}", [
            'event_title' => 'replaced',
            'event_start_date' => '2023-07-26 10:00:00',
            'event_end_date' => '2023-07-26 21:00:00',
        ])
            ->assertOk()
            ->assertExactJson([
                'id' => $event->id,
                'event_title' => 'replaced',
                'event_start_date' => '2023-07-26 10:00:00',
                'event_end_date' => '2023-07-26 21:00:00',
                'organization_id' => $organization->id,
            ]);
    }

    /**
     * @return void
     */
    public function test_it_cannot_replace_event_if_required_param_missed(): void
    {
        $organization = Authorization::query()->whereEmail('sony@example.com')->first();

        Sanctum::actingAs($organization);

        $event = $organization->events->first();

        $this->putJson("/api/{$event->id}", [
            'event_start_date' => '2023-07-26 10:00:00',
            'event_end_date' => '2023-07-26 21:00:00',
        ])
            ->assertUnprocessable()
            ->assertJson([
                'message' => 'The event title field is required.',
                'errors' => [
                    'event_title' => [
                        'The event title field is required.',
                    ],
                ],
            ]);
    }

    /**
     * @return void
     */
    public function test_it_cannot_replace_event_if_end_date_before_start_date(): void
    {
        $organization = Authorization::query()->whereEmail('sony@example.com')->first();

        Sanctum::actingAs($organization);

        $event = $organization->events->first();

        $this->putJson("/api/{$event->id}", [
            'event_title' => 'replaced',
            'event_start_date' => '2023-07-26 21:00:00',
            'event_end_date' => '2023-07-26 10:00:00',
        ])
            ->assertUnprocessable()
            ->assertJson([
                'message' => 'The event start date field must be a date before event end date. (and 1 more error)',
                'errors' => [
                    'event_start_date' => [
                        'The event start date field must be a date before event end date.',
                    ],
                    'event_end_date' => [
                        'The event end date field must be a date after event start date.',
                    ],
                ],
            ]);
    }

    /**
     * @return void
     */
    public function test_it_cannot_replace_event_if_duration_between_end_date_and_start_date_exceed_12_hours(): void
    {
        $organization = Authorization::query()->whereEmail('sony@example.com')->first();

        Sanctum::actingAs($organization);

        $event = $organization->events->first();

        $this->putJson("/api/{$event->id}", [
            'event_title' => 'replaced',
            'event_start_date' => '2023-07-26 10:00:00',
            'event_end_date' => '2023-07-26 23:00:00',
        ])
            ->assertUnprocessable()
            ->assertJson([
                'message' => 'The duration between the event end date and event start date cannot exceed 12 hours.',
                'errors' => [
                    'event_end_date' => [
                        'The duration between the event end date and event start date cannot exceed 12 hours.',
                    ],
                ],
            ]);
    }

    /**
     * @return void
     */
    public function test_it_cannot_replace_field_that_cannot_be_modified(): void
    {
        $organization = Authorization::query()->whereEmail('sony@example.com')->first();

        Sanctum::actingAs($organization);

        $event = $organization->events->first();

        $this->putJson("/api/{$event->id}", [
            'event_title' => 'replaced',
            'event_start_date' => '2023-07-26 10:00:00',
            'event_end_date' => '2023-07-26 21:00:00',
            'organization_id' => 1,
        ])
            ->assertOk()
            ->assertExactJson([
                'id' => $event->id,
                'event_title' => 'replaced',
                'event_start_date' => '2023-07-26 10:00:00',
                'event_end_date' => '2023-07-26 21:00:00',
                'organization_id' => $organization->id,
            ]);
    }

    /**
     * @return void
     */
    public function test_it_can_update_one_column(): void
    {
        $organization = Authorization::query()->whereEmail('sony@example.com')->first();

        Sanctum::actingAs($organization);

        $event = $organization->events->first();

        $this->patchJson("/api/{$event->id}", [
            'event_title' => 'updated',
        ])
            ->assertOk()
            ->assertExactJson([
                'id' => $event->id,
                'event_title' => 'updated',
                'event_start_date' => $event->event_start_date,
                'event_end_date' => $event->event_end_date,
                'organization_id' => $organization->id,
            ]);
    }

    /**
     * @return void
     */
    public function test_it_can_delete_event_by_id(): void
    {
        $organization = Authorization::query()->whereEmail('sony@example.com')->first();

        Sanctum::actingAs($organization);

        $event = $organization->events->first();

        $this->deleteJson("/api/{$event->id}")
            ->assertOk()
            ->assertExactJson([
                'message' => 'Event successfully deleted.'
            ]);
    }
}
