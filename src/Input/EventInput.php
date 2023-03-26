<?php

declare(strict_types=1);

namespace Adrian\CLMSGraph\Input;

use Adrian\CLMSGraph\Utils\DateTimeTimeZoneExtensions;
use DateTimeInterface;
use Microsoft\Graph\Model\Event;

class EventInput {
    public string $userID;
    public DateTimeInterface $start;
    public DateTimeInterface $end;
    public string $subject;

    public ?BodyInput $body = null;

    /**
     * @var AttendeeInput[]
     */
    public array $attendees = [];
    public ?LocationInput $location = null;
    public bool $isOnlineMeeting = false;
    public ?string $onlineMeetingProvider = null;

    public function __construct(string $userID, DateTimeInterface $start, DateTimeInterface $end, string $subject) {
        $this->userID = $userID;
        $this->start = $start;
        $this->end = $end;
        $this->subject = $subject;
    }

    public function withBody(?BodyInput $body): EventInput {
        $clone = clone $this;
        $clone->body = $body;

        return $clone;
    }

    public function withAttendees(array $attendees): EventInput {
        $clone = clone $this;
        $clone->attendees = $attendees;

        return $clone;
    }

    public function withLocation(LocationInput $location): EventInput {
        $clone = clone $this;
        $clone->location = $location;

        return $clone;
    }

    public function withIsOnlineMeeting(bool $isOnlineMeeting): EventInput {
        $clone = clone $this;
        $clone->isOnlineMeeting = $isOnlineMeeting;

        return $clone;
    }

    public function toMSGraph(): Event {
        $event = new Event([
            'start' => DateTimeTimeZoneExtensions::fromDateTime($this->start),
            'end' => DateTimeTimeZoneExtensions::fromDateTime($this->end),
            'subject' => $this->subject,
            'attendees' => array_map(function (AttendeeInput $a) {
                return $a->toMSGraph();
            }, $this->attendees),
            'isOnlineMeeting' => $this->isOnlineMeeting,
        ]);
        if ($this->body !== null) {
            $event->setBody($this->body->toMSGraph());
        }
        if ($this->location !== null) {
            $event->setLocation($this->location->toMSGraph());
        }

        if ($this->isOnlineMeeting) {
            $event->setOnlineMeetingProvider($this->onlineMeetingProvider ?? getenv('DEFAULT_ONLINE_MEETING_PROVIDER'));
        }

        return $event;
    }
}
