<?php

declare(strict_types=1);

namespace TWOH\TwohMongodbDriver\Tests\Unit\Database\Driver;

use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use RuntimeException;
use TWOH\TwohMongodbDriver\Database\Driver\MongoDbDriver;
use TWOH\TwohMongodbDriver\Domain\Model\MongodbConfiguration;

final class MongoDbDriverTest extends TestCase
{
    public function testIsConfigurationValid(): void
    {
        $driver = new MongoDbDriver();

        $params = [
            'user' => 'root',
            'password' => '123',
        ];

        self::assertTrue($driver->isConfigurationValid($params, 'user'));
    }

    public function testIsConfigurationValidThrowsException(): void
    {
        $this->expectException(RuntimeException::class);

        $driver = new MongoDbDriver();
        $driver->isConfigurationValid([], 'user');
    }

    public function testCreateConfiguration(): void
    {
        $driver = new MongoDbDriver();

        $params = [
            'user' => 'root',
            'password' => '123',
            'host' => 'localhost',
            'port' => 27017,
            'dbname' => 'testdb',
        ];

        $reflection = new ReflectionMethod($driver, 'creatConfiguration');
        $reflection->setAccessible(true);

        /** @var MongodbConfiguration $config */
        $config = $reflection->invoke($driver, $params);

        self::assertSame('root', $config->getUser());
        self::assertSame('123', $config->getPassword());
        self::assertSame('localhost', $config->getHost());
        self::assertSame(27017, $config->getPort());
        self::assertSame('testdb', $config->getDbname());
    }
}
