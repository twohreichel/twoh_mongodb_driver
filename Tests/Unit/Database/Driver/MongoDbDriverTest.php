<?php

declare(strict_types=1);

namespace TWOH\TwohMongodbDriver\Tests\Unit\Database\Driver;

use Generator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use ReflectionClass;
use RuntimeException;
use TWOH\TwohMongodbDriver\Database\Driver\MongoDbDriver;
use TWOH\TwohMongodbDriver\Domain\Model\MongodbConfiguration;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

#[CoversClass(MongoDbDriver::class)]
final class MongoDbDriverTest extends UnitTestCase
{
    private MongoDbDriver $subject;

    protected function setUp(): void
    {
        parent::setUp();
        $this->subject = new MongoDbDriver();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->subject);
    }

    #[Test]
    public function isConfigurationValidReturnsTrueForValidConfiguration(): void
    {
        $params = ['user' => 'testUser'];

        $result = $this->subject->isConfigurationValid($params, 'user');

        self::assertTrue($result);
    }

    #[Test]
    public function isConfigurationValidThrowsExceptionForEmptyValue(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('[user] Configuration is empty!');

        $params = ['user' => ''];

        $this->subject->isConfigurationValid($params, 'user');
    }

    #[Test]
    public function isConfigurationValidThrowsExceptionForMissingKey(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('[password] Configuration is empty!');

        $params = ['user' => 'testUser'];

        $this->subject->isConfigurationValid($params, 'password');
    }

    #[Test]
    public function isConfigurationValidThrowsExceptionForNullValue(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('[host] Configuration is empty!');

        $params = ['host' => null];

        $this->subject->isConfigurationValid($params, 'host');
    }

    #[Test]
    #[DataProvider('validConfigurationDataProvider')]
    public function isConfigurationValidAcceptsVariousValidValues(string $field, mixed $value): void
    {
        $params = [$field => $value];

        $result = $this->subject->isConfigurationValid($params, $field);

        self::assertTrue($result);
    }

    public static function validConfigurationDataProvider(): Generator
    {
        yield 'user with string' => ['user', 'admin'];
        yield 'password with string' => ['password', 'secret123'];
        yield 'host with localhost' => ['host', 'localhost'];
        yield 'host with ip' => ['host', '127.0.0.1'];
        yield 'port with integer as string' => ['port', '27017'];
        yield 'dbname with string' => ['dbname', 'testdb'];
        yield 'field with whitespace' => ['field', '  value  '];
        // Note: '0' as string is considered empty by empty(), so it's invalid
    }

    #[Test]
    #[DataProvider('invalidConfigurationDataProvider')]
    public function isConfigurationValidThrowsExceptionForInvalidValues(string $field, mixed $value): void
    {
        $this->expectException(RuntimeException::class);

        $params = [$field => $value];

        $this->subject->isConfigurationValid($params, $field);
    }

    public static function invalidConfigurationDataProvider(): Generator
    {
        yield 'empty string' => ['field', ''];
        yield 'null value' => ['field', null];
        yield 'false value' => ['field', false];
        yield 'zero integer' => ['field', 0];
        yield 'empty array' => ['field', []];
    }

    #[Test]
    public function getDatabasePlatformReturnsMongodbString(): void
    {
        $result = $this->subject->getDatabasePlatform();

        self::assertSame('mongodb', $result);
    }

    #[Test]
    public function getClientReturnsNullBeforeConnection(): void
    {
        $result = $this->subject->getClient();

        self::assertNull($result);
    }

    #[Test]
    public function getDatabaseReturnsNullBeforeConnection(): void
    {
        $result = $this->subject->getDatabase();

        self::assertNull($result);
    }

    #[Test]
    public function connectThrowsExceptionWithMissingUserConfiguration(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('[user] Configuration is empty!');

        $params = [
            'password' => 'testPassword',
            'host' => 'localhost',
            'port' => '27017',
            'dbname' => 'testdb',
        ];

        $this->subject->connect($params);
    }

    #[Test]
    public function connectThrowsExceptionWithMissingPasswordConfiguration(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('[password] Configuration is empty!');

        $params = [
            'user' => 'testUser',
            'host' => 'localhost',
            'port' => '27017',
            'dbname' => 'testdb',
        ];

        $this->subject->connect($params);
    }

    #[Test]
    public function connectThrowsExceptionWithMissingHostConfiguration(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('[host] Configuration is empty!');

        $params = [
            'user' => 'testUser',
            'password' => 'testPassword',
            'port' => '27017',
            'dbname' => 'testdb',
        ];

        $this->subject->connect($params);
    }

    #[Test]
    public function connectThrowsExceptionWithMissingPortConfiguration(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('[port] Configuration is empty!');

        $params = [
            'user' => 'testUser',
            'password' => 'testPassword',
            'host' => 'localhost',
            'dbname' => 'testdb',
        ];

        $this->subject->connect($params);
    }

    #[Test]
    public function connectThrowsExceptionWithMissingDbnameConfiguration(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('[dbname] Configuration is empty!');

        $params = [
            'user' => 'testUser',
            'password' => 'testPassword',
            'host' => 'localhost',
            'port' => '27017',
        ];

        $this->subject->connect($params);
    }

    #[Test]
    public function creatConfigurationReturnsMongodbConfigurationObject(): void
    {
        // Using reflection to test the protected method
        $reflection = new ReflectionClass($this->subject);
        $method = $reflection->getMethod('creatConfiguration');
        $method->setAccessible(true);

        $params = [
            'user' => 'testUser',
            'password' => 'testPassword',
            'host' => 'localhost',
            'port' => '27017',
            'dbname' => 'testdb',
        ];

        $result = $method->invoke($this->subject, $params);

        self::assertInstanceOf(MongodbConfiguration::class, $result);
        self::assertSame('testUser', $result->getUser());
        self::assertSame('testPassword', $result->getPassword());
        self::assertSame('localhost', $result->getHost());
        self::assertSame(27017, $result->getPort());
        self::assertSame('testdb', $result->getDbname());
    }

    #[Test]
    public function creatConfigurationConvertsPortToInteger(): void
    {
        $reflection = new ReflectionClass($this->subject);
        $method = $reflection->getMethod('creatConfiguration');
        $method->setAccessible(true);

        $params = [
            'user' => 'testUser',
            'password' => 'testPassword',
            'host' => 'localhost',
            'port' => '27017',
            'dbname' => 'testdb',
        ];

        $result = $method->invoke($this->subject, $params);

        self::assertIsInt($result->getPort());
        self::assertSame(27017, $result->getPort());
    }

    #[Test]
    public function driverIsInstanceOfMongoDbDriver(): void
    {
        self::assertInstanceOf(MongoDbDriver::class, $this->subject);
    }

    #[Test]
    public function multipleValidationCallsWorkCorrectly(): void
    {
        $params = [
            'user' => 'testUser',
            'password' => 'testPassword',
            'host' => 'localhost',
        ];

        self::assertTrue($this->subject->isConfigurationValid($params, 'user'));
        self::assertTrue($this->subject->isConfigurationValid($params, 'password'));
        self::assertTrue($this->subject->isConfigurationValid($params, 'host'));
    }
}
