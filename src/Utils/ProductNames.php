<?php

declare(strict_types=1);

namespace Adrian\CLMSGraph\Utils;

class ProductNames {
    private static $products = [];
    private static $servicePlans = [];
    private static $loaded = false;

    public static function getProductName(string $partNumberOrID): ?string {
        static::load();

        return static::$products[$partNumberOrID] ?? null;
    }

    public static function getServicePlanName(string $planNameOrID): ?string {
        static::load();

        return static::$servicePlans[$planNameOrID] ?? null;
    }

    private static function load() {
        if (static::$loaded) {
            return;
        }
        static::$loaded = true;
        $fh = fopen(__DIR__ . '/../../data/product-names.csv', 'r');
        while (!feof($fh)) {
            $row = fgetcsv($fh);
            if (!is_array($row)) {
                continue;
            }
            static::$products[$row[1]] = $row[0];
            static::$products[$row[2]] = $row[0];
            static::$servicePlans[$row[3]] = $row[5];
            static::$servicePlans[$row[4]] = $row[5];
        }
        fclose($fh);
    }
}
