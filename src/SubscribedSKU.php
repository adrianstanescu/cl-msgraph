<?php

declare(strict_types=1);

namespace Adrian\CLMSGraph;

use Adrian\CLMSGraph\Traits\WithProductName;
use Adrian\CLMSGraph\Traits\WithServicePlans;
use Microsoft\Graph\Model\SubscribedSku as MSSubscribedSku;

class SubscribedSKUStats {
    public int $consumed;
    public int $enabled;
    public int $suspended;
    public int $warning;

    public function __construct(?int $consumed, ?int $enabled, ?int $suspended, ?int $warning) {
        $this->consumed = $consumed ?? 0;
        $this->enabled = $enabled ?? 0;
        $this->suspended = $suspended ?? 0;
        $this->warning = $warning ?? 0;
    }
}

class SubscribedSKU extends MSSubscribedSku {
    use WithProductName;
    use WithServicePlans;

    public function __toString() {
        $stats = $this->stats();

        return sprintf(
            '%s (%s) - %s [consumed: %d, enabled: %d, suspended: %d, warning: %d]',
            $this->name(),
            $this->getSkuId(),
            $this->getCapabilityStatus(),
            $stats->consumed,
            $stats->enabled,
            $stats->suspended,
            $stats->warning
        );
    }

    /**
     * @return SubscribedSKU[]
     */
    public static function all(): array {
        return iterator_to_array(new Collection('/subscribedSkus', SubscribedSKU::class, Collection::NO_PAGINATION));
    }

    public function stats(): SubscribedSKUStats {
        $prepaidUnits = $this->getPrepaidUnits();

        return new SubscribedSKUStats(
            $this->getConsumedUnits(),
            $prepaidUnits ? $prepaidUnits->getEnabled() : null,
            $prepaidUnits ? $prepaidUnits->getSuspended() : null,
            $prepaidUnits ? $prepaidUnits->getWarning() : null,
        );
    }
}
