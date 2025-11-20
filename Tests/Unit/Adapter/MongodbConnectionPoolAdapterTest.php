<?php

declare(strict_types=1);

namespace TWOH\TwohMongodbDriver\Tests\Unit\Adapter;

use PHPUnit\Framework\TestCase;
use TWOH\TwohMongodbDriver\Adapter\MongodbConnectionPoolAdapter;
use TWOH\TwohMongodbDriver\Database\Connection\MongoDbConnection;

final class MongodbConnectionPoolAdapterTest extends TestCase
{
    public function testGetConnectionPool(): void
    {
        $dummyConnection = $this->createMock(MongoDbConnection::class);

        $GLOBALS['TYPO3_CONF_VARS']['DRIVER']['MongoDB']['ConnectionPool'] = $dummyConnection;

        $adapter = new MongodbConnectionPoolAdapter();

        self::assertSame(
            $dummyConnection,
            $adapter->getConnectionPool()
        );
    }
}
