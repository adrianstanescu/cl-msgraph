<?php

declare(strict_types=1);

namespace Adrian\CLMSGraph\Input;

use Microsoft\Graph\Model\Location;

class LocationInput {
    public string $name;

    public ?string $emailAddress = null;
    public ?CoordinatesInput $coordinates = null;

    public function __construct(string $name) {
        $this->name = $name;
    }

    public function withEmailAddress(?string $emailAddress): LocationInput {
        $clone = clone $this;
        $clone->emailAddress = $emailAddress;

        return $clone;
    }

    public function withCoordinates(CoordinatesInput $coordinates): LocationInput {
        $clone = clone $this;
        $clone->coordinates = $coordinates;

        return $clone;
    }

    public function type(): string {
        if ($this->emailAddress !== null) {
            return 'conferenceRoom';
        }

        return 'default';
    }

    public function toMSGraph(): Location {
        $location = new Location([
            'displayName' => $this->name,
        ]);
        if ($this->emailAddress !== null) {
            $location->setLocationEmailAddress($this->emailAddress);
        }
        if ($this->coordinates !== null) {
            $location->setCoordinates($this->coordinates->toMSGraph());
        }

        return $location;
    }
}
