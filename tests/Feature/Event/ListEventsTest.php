<?php

namespace Tests\Feature\Event;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesModels;
use Tests\TestCase;

class ListEventsTest extends TestCase
{
    use CreatesModels;
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function testRequestIsSuccessful(): void
    {
        $this->generateUser()->generateEvents(5);

        $response = $this->actingAs($this->user)->get(route('events.index'));

        $response->assertOk()
            ->assertJson(['data' => $this->events->toArray(), 'current_page' => 1]);
    }

    public function testRequestPaginationWorks(): void
    {
        $this->generateUser()->generateEvents(25);

        $response = $this->actingAs($this->user)
            ->get(route('events.index', ['page' => 2, 'limit' => 10]));

        $response->assertOk()
            ->assertJson([
                'data' => array_slice($this->events->toArray(), 10, 10),
                'current_page' => 2,
                'total' => 25,
                'per_page' => 10,
                'last_page' => 3,
            ]);
    }

    public function testRequestReturnsUnauthorized(): void
    {
        $response = $this->get(route('events.index'));

        $response->assertUnauthorized();
    }
}
