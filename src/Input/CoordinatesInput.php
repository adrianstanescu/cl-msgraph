<?php

declare(strict_types=1);

namespace Adrian\CLMSGraph\Input;

use Microsoft\Graph\Model\GeoCoordinates;
use ValueError;

class CoordinatesInput {
    public float $latitude;
    public float $longitude;

    public function __construct(string $latitude, string $longitude) {
        $lat = filter_var($latitude, FILTER_VALIDATE_FLOAT, ['options' => ['min_range' => -90, 'max_range' => 90], 'flags' => FILTER_NULL_ON_FAILURE]);
        $lng = filter_var($longitude, FILTER_VALIDATE_FLOAT, ['options' => ['min_range' => -180, 'max_range' => 180], 'flags' => FILTER_NULL_ON_FAILURE]);

        if ($lat === null || $lng === null) {
            throw new ValueError('Invalid value');
        }
        $this->latitude = $lat;
        $this->longitude = $lng;
    }

    public function toMSGraph(): GeoCoordinates {
        return new GeoCoordinates([
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
        ]);
    }
}
