<?php

namespace App\Services;

use App\Http\Resources\EventResource;
use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class EventService
{
    /**
     * @return LengthAwarePaginator<Event>
     */
    public function findAll(): LengthAwarePaginator
    {
        return $this->getAuthenticatedUser()
            ->events()
            ->paginate((int) request()->query("limit", "15"));
    }

    /**
     * @param Event $event
     * @return EventResource
     */
    public function findOne(Event $event): EventResource
    {
        return new EventResource($event);
    }

    /**
     * @param array $params
     * @return EventResource
     */
    public function create(array $params): EventResource
    {
        $this->abortIfEndDateIsInvalid(
            $params["event_start_date"],
            $params["event_end_date"]
        );

        return new EventResource(
            $this->getAuthenticatedUser()
            ->events()
            ->create($params)
        );
    }

    /**
     * @param Event $event
     * @param array $params
     * @return EventResource
     */
    public function update(Event $event, array $params): EventResource
    {
        $this->abortIfEndDateIsInvalid(
            $params["event_start_date"] ?? $event->event_start_date,
            $params["event_end_date"] ?? $event->event_end_date
        );

        $event->forceFill($params);
        $event->save();

        return new EventResource($event);
    }

    /**
     * @param Event $event
     * @return void
     */
    public function delete(Event $event): void
    {
        $event->delete();
    }

    /**
     * @param string|Carbon $startDate
     * @param string|Carbon $endDate
     * @return void
     */
    protected function abortIfEndDateIsInvalid(
        string|Carbon $startDate,
        string|Carbon $endDate
    ): void {
        $startDate = Carbon::create($startDate);
        if ($startDate->isAfter($endDate)) {
            throw new BadRequestException(
                __("exceptions.events.end_date_precedes_start_date"),
                400
            );
        }

        if (Carbon::create($startDate)->diffInHours($endDate) > 12) {
            throw new BadRequestException(
                __("exceptions.events.end_date_exceeds_limit"),
                400
            );
        }
    }

    /**
     * @return User
     */
    protected function getAuthenticatedUser(): User
    {
        /**
         * @var User $user
         */
        $user = auth()->user();

        return $user;
    }
}
