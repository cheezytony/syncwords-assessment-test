<?php

namespace Tests;

use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Collection;

trait CreatesModels
{
    /**
     * User instance to authenticate requests with.
     *
     * @var User
     */
    protected User $user;

    /**
     * Collection of events for testing.
     *
     * @var Collection<int, Event>
     */
    protected Collection $events;

    /**
     * Event instance for testing.
     *
     * @var Event
     */
    protected Event $event;

    public function generateUser(): static
    {
        $this->user = User::factory()->create();
        return $this;
    }

    public function generateEvents(int $count = 1): static
    {
        $this->events = Event::factory($count)->create([
            'organization_id' => $this->user->id
        ]);
        $this->event = $this->events[0];

        return $this;
    }
}
