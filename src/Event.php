<?php

declare(strict_types=1);

namespace Adrian\CLMSGraph;

use Adrian\CLMSGraph\Input\EventInput;
use Adrian\CLMSGraph\Utils\DateTimeTimeZoneExtensions;
use DateTime;
use Microsoft\Graph\Model\Event as MSEvent;

class Event extends MSEvent {
    /**
     * @return Event
     */
    public static function create(EventInput $input) {
        $event = $input->toMSGraph();

        $request = Graph::instance()->createRequest('POST', "/users/{$input->userID}/events")->attachBody($event);

        return $request->setReturnType(Event::class)->execute();
    }

    public function startDateTime(): DateTime {
        return DateTimeTimeZoneExtensions::toDateTime($this->getStart());
    }

    public function endDateTime(): DateTime {
        return DateTimeTimeZoneExtensions::toDateTime($this->getEnd());
    }
}
