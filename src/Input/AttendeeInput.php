<?php

declare(strict_types=1);

namespace Adrian\CLMSGraph\Input;

use Microsoft\Graph\Model\Attendee;
use Microsoft\Graph\Model\EmailAddress;
use ValueError;

class AttendeeInput {
    public const VALID_TYPES = ['required', 'optional', 'resource'];

    public string $emailAddress;

    public ?string $type = null;
    public ?string $name = null;

    public function __construct(string $emailAddress) {
        $this->emailAddress = $emailAddress;
    }

    public function withName(?string $name): AttendeeInput {
        $clone = clone $this;
        $clone->name = $name;

        return $clone;
    }

    public function withType(?string $type): AttendeeInput {
        if ($type !== null && !in_array($type, AttendeeInput::VALID_TYPES)) {
            throw new ValueError('Invalid type');
        }
        $clone = clone $this;
        $clone->type = $type;

        return $clone;
    }

    public function toMSGraph(): Attendee {
        $emailAddress = new EmailAddress([
            'address' => $this->emailAddress,
        ]);
        if ($this->name !== null) {
            $emailAddress->setName($this->name);
        }

        return new Attendee([
            'emailAddress' => $emailAddress,
            'type' => $this->type ?? 'required',
        ]);
    }
}
