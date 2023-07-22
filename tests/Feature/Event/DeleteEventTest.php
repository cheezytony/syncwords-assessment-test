<?php

namespace Tests\Feature\Event;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\CreatesModels;
use Tests\TestCase;

class DeleteEventTest extends TestCase
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
    public function testRequestIsSuccessfulAndDataIsRemoved(): void
    {
        $response = $this->actingAs($this->user)
            ->delete(route("events.destroy", ["event" => $this->event->id]));

        $response->assertNoContent();

        $this->assertDatabaseMissing('events', [
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
        $response = $this->delete(
            route("events.destroy", ["event" => $this->event->id])
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

        $response = $this->actingAs($this->user)->delete(
            route("events.destroy", ["event" => $this->event->id]),
        );

        $response->assertForbidden()
            ->assertJsonPath("message", __("exceptions.events.policies.delete"));
    }
}
