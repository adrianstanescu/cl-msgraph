<?php

declare(strict_types=1);

namespace Adrian\CLMSGraph;

use Adrian\CLMSGraph\Utils\ProductNames;
use Microsoft\Graph\Model\ServicePlanInfo as MSServicePlanInfo;

class ServicePlanInfo extends MSServicePlanInfo {
    public function name(): ?string {
        return ProductNames::getServicePlanName($this->getServicePlanId());
    }
}
