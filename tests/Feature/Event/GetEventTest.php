<?php

namespace Tests\Feature\Event;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\CreatesModels;
use Tests\TestCase;

class GetEventTest extends TestCase
{
    use CreatesModels;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->generateUser()->generateEvents();
    }

    /**
     * A basic feature test example.
     */
    public function testRequestIsSuccessful(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('events.show', ['event' => $this->event->id]));

        $response->assertOk()
            ->assertJson($this->event->toArray());
    }

    /**
     * A basic feature test example.
     */
    public function testReturnsUnauthorized(): void
    {
        $response = $this->get(route('events.show', ['event' => $this->event->id]));

        $response->assertUnauthorized();
    }

    /**
     * A basic feature test example.
     */
    public function testRequestReturnsForbidden(): void
    {
        // This overwrites our user with a new one and this one currently has no events.
        $this->generateUser();

        $response = $this->actingAs($this->user)
            ->get(route('events.show', ['event' => $this->event->id]));

        $response->assertForbidden()
            ->assertJsonPath("message", __("exceptions.events.policies.view"));
    }
}
