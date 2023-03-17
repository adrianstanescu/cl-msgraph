<?php

declare(strict_types=1);

namespace Adrian\CLMSGraph\Traits;

use Adrian\CLMSGraph\ServicePlanInfo;

trait WithServicePlans {
    /**
     * @return ServicePlanInfo[]
     */
    public function servicePlans(): array {
        return array_map(function ($servicePlan) {
            return new ServicePlanInfo($servicePlan);
        }, $this->getServicePlans());
    }
}
