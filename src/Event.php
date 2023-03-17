<?php

declare(strict_types=1);

namespace Adrian\CLMSGraph;

use Adrian\CLMSGraph\Utils\DateTimeTimeZoneExtensions;
use DateTime;
use Microsoft\Graph\Model\Attendee;
use Microsoft\Graph\Model\EmailAddress;
use Microsoft\Graph\Model\Event as MSEvent;
use Microsoft\Graph\Model\ItemBody;
use Microsoft\Graph\Model\Location;

class Event extends MSEvent {
    /**
     * @return Event
     */
    public static function create(string $userID, DateTime $start, DateTime $end, string $subject, string $body = '', array $attendees = [], ?string $location = null) {
        $eventAttendees = array_map(function (string $a) {
            return new Attendee([
                'emailAddress' => new EmailAddress([
                    'address' => $a,
                ]),
                'type' => 'required',
            ]);
        }, $attendees);
        $eventLocation = null;
        if ($location !== null) {
            $eventLocation = new Location([
                'displayName' => $location,
            ]);
            if (strpos($location, '@') !== false) {
                $eventLocation->setLocationEmailAddress($location);
                $eventAttendees[] = new Attendee([
                    'emailAddress' => new EmailAddress([
                        'address' => $location,
                    ]),
                    'type' => 'resource',
                ]);
            }
        }
        $event = new MSEvent([
            'start' => DateTimeTimeZoneExtensions::fromDateTime($start),
            'end' => DateTimeTimeZoneExtensions::fromDateTime($end),
            'subject' => $subject,
            'body' => new ItemBody([
                'content' => $body,
                'contentType' => 'text',
            ]),
            'attendees' => $eventAttendees,
            'location' => $eventLocation,
        ]);
        $request = Graph::instance()->createRequest('POST', "/users/{$userID}/events")->attachBody($event);

        return $request->setReturnType(Event::class)->execute();
    }

    public function startDateTime(): DateTime {
        return DateTimeTimeZoneExtensions::toDateTime($this->getStart());
    }

    public function endDateTime(): DateTime {
        return DateTimeTimeZoneExtensions::toDateTime($this->getEnd());
    }
}
