<?php

namespace Tests\Feature\Event;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\CreatesModels;
use Tests\TestCase;

class UpdateEventTest extends TestCase
{
    use CreatesModels;
    use RefreshDatabase;
    use WithFaker;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->generateUser()->generateEvents();
    }

    /**
     * A basic feature test example.
     */
    public function testRequestIsSuccessfulAndDataIsChanged(): void
    {
        $eventTitle = $this->faker->sentence;
        $eventStartDate = "2023-07-19 12:00:00";
        $eventEndDate = "2023-07-19 16:00:00";

        $response = $this->actingAs($this->user)
            ->put(route("events.update", ["event" => $this->event->id]), [
                "event_title" => $eventTitle,
                "event_start_date" => $eventStartDate,
                "event_end_date" => $eventEndDate,
            ]);

        $response->assertOk()
            ->assertJson([
                "id" => $this->event->id,
                "organization_id" => $this->user->id,
                "event_title" => $eventTitle,
                "event_start_date" => $eventStartDate,
                "event_end_date" => $eventEndDate,
            ]);

        $this->assertDatabaseHas('events', [
            "id" => $this->event->id,
            "event_title" => $eventTitle,
            "event_start_date" => $eventStartDate,
            "event_end_date" => $eventEndDate,
        ])
            ->assertDatabaseMissing('events', [
                "id" => $this->event->id,
                "event_title" => $this->event->event_title,
                "event_start_date" => $this->event->event_start_date,
                "event_end_date" => $this->event->event_end_date,
            ]);
    }

    /**
     * A basic feature test example.
     */
    public function testRequestReturnsUnauthorized(): void
    {
        $response = $this->put(
            route("events.update", ["event" => $this->event->id]),
            [
                "event_title" => $this->faker->sentence,
                "event_start_date" => "2023-07-19 12:00:00",
                "event_end_date" => "2023-07-19 16:00:00",
            ]
        );

        $response->assertUnauthorized();
    }

    /**
     * A basic feature test example.
     */
    public function testRequestReturnsForbidden(): void
    {
        // This overwrites our user with a new one and this one currently has no events.
        $this->generateUser();

        $response = $this->actingAs($this->user)->put(
            route("events.update", ["event" => $this->event->id]),
            [
                "event_title" => $this->faker->sentence,
                "event_start_date" => "2023-07-19 12:00:00",
                "event_end_date" => "2023-07-19 16:00:00",
            ]
        );

        $response->assertForbidden()
            ->assertJsonPath("message", __("exceptions.events.policies.update"));
    }

    /**
     * A basic feature test example.
     */
    public function testRequestValidationFailsWhenDatesAreInvalid(): void
    {
        $response = $this->actingAs($this->user)
            ->put(route("events.update", ["event" => $this->event->id]), [
                "event_title" => $this->faker->sentence,
                "event_start_date" => "2023-07-19",
                "event_end_date" => "tomorrow",
            ]);

        $response->assertUnprocessable()
            ->assertJson([
                "errors" => [
                    "event_start_date" => [
                        __(
                            "validation.date_format",
                            ["attribute" => "event start date", "format" => "Y-m-d H:i:s"]
                        )
                    ],
                    "event_end_date" => [
                        __(
                            "validation.date_format",
                            ["attribute" => "event end date", "format" => "Y-m-d H:i:s"]
                        )
                    ]
                ]
            ]);
    }

    /**
     * A basic feature test example.
     */
    public function testRequestValidationFailsWhenEndDateIsBeforeStartDate(): void
    {
        $response = $this->actingAs($this->user)
            ->put(route("events.update", ["event" => $this->event->id]), [
                "event_title" => $this->faker->sentence,
                "event_start_date" => "2023-07-19 12:00:00",
                "event_end_date" => "2023-07-18 12:00:00",
            ]);

        $response->assertBadRequest()
            ->assertJson([
                "message" => __("exceptions.events.end_date_precedes_start_date"),
            ]);
    }

    /**
     * A basic feature test example.
     */
    public function testRequestValidationFailsWhenEndDateExceedsTwelveHours(): void
    {
        $response = $this->actingAs($this->user)
            ->put(route("events.update", ["event" => $this->event->id]), [
                "event_title" => $this->faker->sentence,
                "event_start_date" => "2023-07-19 06:00:00",
                "event_end_date" => "2023-07-19 20:00:00",
            ]);

        $response->assertBadRequest()
            ->assertJson([
                "message" => __("exceptions.events.end_date_exceeds_limit"),
            ]);
    }

    /**
     * A basic feature test example.
     */
    public function testEventIdRemainsUnchangedWhenValueIsSent(): void
    {
        $eventTitle = $this->faker->sentence;
        $eventStartDate = "2023-07-19 12:00:00";
        $eventEndDate = "2023-07-19 16:00:00";

        $response = $this->actingAs($this->user)
            ->put(route("events.update", ["event" => $this->event->id]), [
                "id" => 125,
                "event_title" => $eventTitle,
                "event_start_date" => $eventStartDate,
                "event_end_date" => $eventEndDate,
            ]);

        $response->assertOk()
            ->assertJson([
                "id" => $this->event->id,
                "event_title" => $eventTitle,
                "event_start_date" => $eventStartDate,
                "event_end_date" => $eventEndDate,
            ]);

        $this->assertDatabaseHas('events', [
            "id" => $this->event->id,
            "event_title" => $eventTitle,
            "event_start_date" => $eventStartDate,
            "event_end_date" => $eventEndDate,
        ])
            ->assertDatabaseMissing('events', [
                "id" => 125,
            ]);
    }

    /**
     * A basic feature test example.
     */
    public function testOrganizationIdRemainsUnchangedWhenValueIsSent(): void
    {
        $eventTitle = $this->faker->sentence;
        $eventStartDate = "2023-07-19 12:00:00";
        $eventEndDate = "2023-07-19 16:00:00";

        $response = $this->actingAs($this->user)
            ->put(route("events.update", ["event" => $this->event->id]), [
                "event_title" => $eventTitle,
                "event_start_date" => $eventStartDate,
                "event_end_date" => $eventEndDate,
                "organization_id" => 200,
            ]);

        $response->assertOk()
            ->assertJson([
                "id" => $this->event->id,
                "event_title" => $eventTitle,
                "event_start_date" => $eventStartDate,
                "event_end_date" => $eventEndDate,
                "organization_id" => $this->user->id,
            ]);

        $this->assertDatabaseHas('events', [
            "id" => $this->event->id,
            "event_title" => $eventTitle,
            "event_start_date" => $eventStartDate,
            "event_end_date" => $eventEndDate,
            "organization_id" => $this->user->id,
        ])
            ->assertDatabaseMissing('events', [
                "organization_id" => 200,
            ]);
    }
}
