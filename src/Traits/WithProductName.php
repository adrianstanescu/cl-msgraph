<?php

declare(strict_types=1);

namespace Adrian\CLMSGraph\Traits;

use Adrian\CLMSGraph\Utils\ProductNames;

trait WithProductName {
    public function name(): ?string {
        return ProductNames::getProductName($this->getSkuId());
    }
}
