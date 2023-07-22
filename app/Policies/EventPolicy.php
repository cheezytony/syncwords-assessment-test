<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EventPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Event $event): Response
    {
        return $user->id === $event->organization_id
            ? Response::allow()
            : Response::deny(__("exceptions.events.policies.view"));
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Event $event): Response
    {
        return $user->id === $event->organization_id
            ? Response::allow()
            : Response::deny(__("exceptions.events.policies.update"));
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Event $event): Response
    {
        return $user->id === $event->organization_id
            ? Response::allow()
            : Response::deny(__("exceptions.events.policies.delete"));
    }
}
