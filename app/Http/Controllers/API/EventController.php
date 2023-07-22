<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use App\Services\EventService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as SymphonyResponse;

class EventController extends Controller
{
    public function __construct(private readonly EventService $eventService)
    {
    }

    public function index(): Response
    {
        return new Response($this->eventService->findAll());
    }

    /**
     * @throws AuthorizationException
     */
    public function store(CreateEventRequest $request): Response
    {
        return new Response(
            $this->eventService->create($request->validated()),
            SymphonyResponse::HTTP_CREATED
        );
    }

    /**
     * @throws AuthorizationException
     */
    public function show(Event $event): Response
    {
        $this->authorize('view', $event);

        return new Response($this->eventService->findOne($event));
    }

    /**
     * @throws AuthorizationException
     */
    public function update(Event $event, UpdateEventRequest $request): Response
    {
        $this->authorize('update', $event);

        return new Response(
            $this->eventService->update($event, $request->validated())
        );
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy(Event $event): Response
    {
        $this->authorize('delete', $event);

        $this->eventService->delete($event);

        return new Response(
            status: SymphonyResponse::HTTP_NO_CONTENT
        );
    }
}
