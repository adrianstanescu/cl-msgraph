<?php

declare(strict_types=1);

namespace Adrian\CLMSGraph;

use Microsoft\Graph\Model\User;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class CollectionTest extends TestCase {
    // TODO: find collection with mostly static content for tests
    public function testSpecificUsersCollection(): void {
        Graph::configure(getenv('AZURE_CLIENT_ID'), getenv('AZURE_CLIENT_SECRET'), getenv('AZURE_TENANT_ID'), 1);
        $collection = new Collection('/users', User::class);
        $this->assertInstanceOf(
            Collection::class,
            $collection
        );
        $this->assertEquals(2, count($collection));
        foreach ($collection as $i => $item) {
            $this->assertInstanceOf(User::class, $item);
            $this->assertEquals($item, $collection[$i]);
        }
    }
}
