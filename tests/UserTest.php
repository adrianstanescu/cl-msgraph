<?php

declare(strict_types=1);

namespace Adrian\CLMSGraph;

use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class UserTest extends TestCase {
    public static function setUpBeforeClass(): void {
        Graph::configure(getenv('AZURE_CLIENT_ID'), getenv('AZURE_CLIENT_SECRET'), getenv('AZURE_TENANT_ID'));
    }

    public function testUserByID(): void {
        $user = User::byID(getenv('TEST_USER_ID'));
        $this->assertInstanceOf(
            User::class,
            $user
        );
        $this->assertEquals(getenv('TEST_USER_EMAIL'), $user->getMail());
    }

    public function testUserByEmailAddress(): void {
        $user = User::byEmailAddress(getenv('TEST_USER_EMAIL'));
        $this->assertInstanceOf(
            User::class,
            $user
        );
        $this->assertEquals(getenv('TEST_USER_ID'), $user->getId());
    }

    public function testUserLicenses(): void {
        $user = User::byID(getenv('TEST_USER_ID'));
        $licenses = $user->licenses();
        $this->assertGreaterThan(0, count($licenses));
        foreach ($licenses as $license) {
            $licenseName = $license->name();
            // print $licenseName . "\n";
            $this->assertIsString($licenseName);
            $this->assertNotEmpty($licenseName);
            $servicePlans = $license->servicePlans();
            $this->assertGreaterThan(0, count($servicePlans));
            // foreach ($servicePlans as $servicePlan) {
            //     print "    " . $servicePlan->name() . "\n";
            // }
        }
    }
}
