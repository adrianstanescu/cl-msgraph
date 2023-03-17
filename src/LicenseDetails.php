<?php

declare(strict_types=1);

namespace Adrian\CLMSGraph;

use Adrian\CLMSGraph\Traits\WithProductName;
use Adrian\CLMSGraph\Traits\WithServicePlans;
use Microsoft\Graph\Model\LicenseDetails as MSLicenseDetails;

class LicenseDetails extends MSLicenseDetails {
    use WithProductName;
    use WithServicePlans;
}
