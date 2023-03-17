<?php

declare(strict_types=1);

namespace Adrian\CLMSGraph;

use DateTimeInterface;
use Microsoft\Graph\Model\Calendar as MSCalendar;
use RuntimeException;

class Calendar extends MSCalendar {
    /**
     * @return Event[]
     */
    public function events(?DateTimeInterface $start = null, ?DateTimeInterface $end = null) {
        $ownerId = $this->getOwner()->getId() ?? $this->getOwner()->getAddress();
        if ($start === null && $end === null) {
            return new Collection("/users/{$ownerId}/calendars/{$this->getId()}/events", Event::class);
        }
        if ($start === null) {
            throw new RuntimeException('Start must be provided if end is used');
        }
        if ($end === null) {
            throw new RuntimeException('End must be provided if start is used');
        }
        $filters = [
            'startDateTime' => $start->format(DateTimeInterface::ATOM),
            'endDateTime' => $end->format(DateTimeInterface::ATOM),
        ];
        $query = http_build_query($filters);

        return new Collection("/users/{$ownerId}/calendars/{$this->getId()}/calendarView?{$query}", Event::class);
    }
}
