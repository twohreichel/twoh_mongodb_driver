<?php

declare(strict_types=1);

namespace TWOH\TwohMongodbDriver\Tests\Unit\Domain\Model;

use PHPUnit\Framework\TestCase;
use TWOH\TwohMongodbDriver\Domain\Model\MongodbConfiguration;

final class MongodbConfigurationTest extends TestCase
{
    public function testConfigurationSettersAndGetters(): void
    {
        $config = new MongodbConfiguration();

        $config->setUser('root');
        $config->setPassword('secret');
        $config->setHost('localhost');
        $config->setPort(27017);
        $config->setDbname('testdb');

        self::assertSame('root', $config->getUser());
        self::assertSame('secret', $config->getPassword());
        self::assertSame('localhost', $config->getHost());
        self::assertSame(27017, $config->getPort());
        self::assertSame('testdb', $config->getDbname());
    }
}

