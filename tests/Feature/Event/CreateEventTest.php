<?php

namespace Tests\Feature\Event;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\CreatesModels;
use Tests\TestCase;

class CreateEventTest extends TestCase
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
        $this->generateUser();
    }

    /**
     * A basic feature test example.
     */
    public function testRequestIsSuccessful(): void
    {
        $eventTitle = $this->faker->sentence;
        $eventStartDate = "2023-07-19 12:00:00";
        $eventEndDate = "2023-07-19 16:00:00";

        $response = $this->actingAs($this->user)
            ->post(route('events.store'), [
                'event_title' => $eventTitle,
                'event_start_date' => $eventStartDate,
                'event_end_date' => $eventEndDate,
            ]);

        $response->assertCreated()
            ->assertJson([
                'id' => 1,
                'organization_id' => $this->user->id,
                'event_title' => $eventTitle,
                'event_start_date' => $eventStartDate,
                'event_end_date' => $eventEndDate,
            ]);

        $this->assertDatabaseHas('events', [
            "event_title" => $eventTitle,
            "event_start_date" => $eventStartDate,
            "event_end_date" => $eventEndDate,
        ]);
    }

    /**
     * A basic feature test example.
     */
    public function testRequestReturnsUnauthorized(): void
    {
        $response = $this->post(route('events.store'), [
                'event_title' => $this->faker->sentence,
                'event_start_date' => "2023-07-19 12:00:00",
                'event_end_date' => "2023-07-19 16:00:00",
            ]);

        $response->assertUnauthorized();
    }

    /**
     * A basic feature test example.
     */
    public function testRequestValidationFailsWhenParamsAreEmpty(): void
    {
        $response = $this->actingAs($this->user)
            ->post(route('events.store'), []);

        $response->assertUnprocessable()
            ->assertJson([
                'errors' => [
                    "event_title" => [
                        __("validation.required", ["attribute" => "event title"])
                    ],
                    "event_start_date" => [
                        __("validation.required", ["attribute" => "event start date"])
                    ],
                    "event_end_date" => [
                        __("validation.required", ["attribute" => "event end date"])
                    ]
                ]
            ]);
    }

    /**
     * A basic feature test example.
     */
    public function testRequestValidationFailsWhenDatesAreInvalid(): void
    {
        $response = $this->actingAs($this->user)
            ->post(route('events.store'), [
                'event_title' => $this->faker->sentence,
                'event_start_date' => "2023-07-19",
                'event_end_date' => "tomorrow",
            ]);

        $response->assertUnprocessable()
            ->assertJson([
                'errors' => [
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
            ->post(route('events.store'), [
                'event_title' => $this->faker->sentence,
                'event_start_date' => "2023-07-19 12:00:00",
                'event_end_date' => "2023-07-18 12:00:00",
            ]);

        $response->assertBadRequest()
            ->assertJson([
                'message' => __("exceptions.events.end_date_precedes_start_date"),
            ]);
    }

    /**
     * A basic feature test example.
     */
    public function testRequestValidationFailsWhenEndDateExceedsTwelveHours(): void
    {
        $response = $this->actingAs($this->user)
            ->post(route('events.store'), [
                'event_title' => $this->faker->sentence,
                'event_start_date' => "2023-07-19 06:00:00",
                'event_end_date' => "2023-07-19 20:00:00",
            ]);

        $response->assertBadRequest()
            ->assertJson([
                'message' => __("exceptions.events.end_date_exceeds_limit"),
            ]);
    }
}
