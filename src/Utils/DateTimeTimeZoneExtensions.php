<?php

declare(strict_types=1);

namespace Adrian\CLMSGraph\Utils;

use DateTime;
use DateTimeZone;
use Microsoft\Graph\Model\DateTimeTimeZone;

class DateTimeTimeZoneExtensions {
    public static function toDateTime(DateTimeTimeZone $d): DateTime {
        return new DateTime($d->getDateTime(), new DateTimeZone($d->getTimeZone()));
    }

    public static function fromDateTime(DateTime $d): DateTimeTimeZone {
        // from https://github.com/microsoftgraph/msgraph-sdk-dotnet/blob/dev/src/Microsoft.Graph/Extensions/DateTimeTimeZoneExtensions.cs
        // TODO: try to move timezone to appropriate prop
        return new DateTimeTimeZone([
            'dateTime' => $d->format('Y-m-d\TH:i:s.uP'),
            'timeZone' => '',
        ]);
    }
}
