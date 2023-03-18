<?php

declare(strict_types=1);

namespace Adrian\CLMSGraph;

use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class SubscribedSKUTest extends TestCase {
    public static function setUpBeforeClass(): void {
        Graph::configure(getenv('AZURE_CLIENT_ID'), getenv('AZURE_CLIENT_SECRET'), getenv('AZURE_TENANT_ID'));
    }

    public function testList(): void {
        $licenses = SubscribedSKU::all();
        $this->assertGreaterThan(0, count($licenses));
        foreach ($licenses as $license) {
            $licenseName = $license->name();
            // print $licenseName . "\n";
            // print_r($license->stats());
            $this->assertIsString($licenseName);
            $this->assertNotEmpty($licenseName);
            $servicePlans = $license->servicePlans();
            $this->assertGreaterThan(0, count($servicePlans));
        }
    }
}
