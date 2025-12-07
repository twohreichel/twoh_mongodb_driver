<?php

declare(strict_types=1);

namespace TWOH\TwohMongodbDriver\Tests\Unit\Domain\Model;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TWOH\TwohMongodbDriver\Domain\Model\MongodbConfiguration;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

#[CoversClass(MongodbConfiguration::class)]
final class MongodbConfigurationTest extends UnitTestCase
{
    private MongodbConfiguration $subject;

    protected function setUp(): void
    {
        parent::setUp();
        $this->subject = new MongodbConfiguration();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->subject);
    }

    #[Test]
    public function constructorInitializesWithDefaultValues(): void
    {
        self::assertSame('', $this->subject->getUser());
        self::assertSame('', $this->subject->getPassword());
        self::assertSame('', $this->subject->getHost());
        self::assertSame(0, $this->subject->getPort());
        self::assertSame('', $this->subject->getDbname());
    }

    #[Test]
    public function getUserReturnsInitialValueForString(): void
    {
        self::assertSame('', $this->subject->getUser());
    }

    #[Test]
    public function setUserSetsUser(): void
    {
        $this->subject->setUser('testUser');
        self::assertSame('testUser', $this->subject->getUser());
    }

    #[Test]
    #[DataProvider('userDataProvider')]
    public function setUserForStringAcceptsVariousValues(string $user): void
    {
        $this->subject->setUser($user);
        self::assertSame($user, $this->subject->getUser());
    }

    public static function userDataProvider(): \Generator
    {
        yield 'empty string' => [''];
        yield 'simple username' => ['admin'];
        yield 'username with numbers' => ['admin123'];
        yield 'username with special chars' => ['admin_test-user'];
        yield 'email as username' => ['admin@example.com'];
        yield 'unicode username' => ['äöüß'];
    }

    #[Test]
    public function getPasswordReturnsInitialValueForString(): void
    {
        self::assertSame('', $this->subject->getPassword());
    }

    #[Test]
    public function setPasswordSetsPassword(): void
    {
        $this->subject->setPassword('secretPassword123');
        self::assertSame('secretPassword123', $this->subject->getPassword());
    }

    #[Test]
    #[DataProvider('passwordDataProvider')]
    public function setPasswordForStringAcceptsVariousValues(string $password): void
    {
        $this->subject->setPassword($password);
        self::assertSame($password, $this->subject->getPassword());
    }

    public static function passwordDataProvider(): \Generator
    {
        yield 'empty string' => [''];
        yield 'simple password' => ['password'];
        yield 'complex password' => ['P@ssw0rd!#$%'];
        yield 'very long password' => [str_repeat('a', 256)];
        yield 'password with spaces' => ['pass word with spaces'];
        yield 'unicode password' => ['пароль密码'];
    }

    #[Test]
    public function getHostReturnsInitialValueForString(): void
    {
        self::assertSame('', $this->subject->getHost());
    }

    #[Test]
    public function setHostSetsHost(): void
    {
        $this->subject->setHost('localhost');
        self::assertSame('localhost', $this->subject->getHost());
    }

    #[Test]
    #[DataProvider('hostDataProvider')]
    public function setHostForStringAcceptsVariousValues(string $host): void
    {
        $this->subject->setHost($host);
        self::assertSame($host, $this->subject->getHost());
    }

    public static function hostDataProvider(): \Generator
    {
        yield 'localhost' => ['localhost'];
        yield 'ip address' => ['127.0.0.1'];
        yield 'ipv6 address' => ['::1'];
        yield 'domain' => ['mongodb.example.com'];
        yield 'subdomain' => ['db.server.example.com'];
        yield 'docker service name' => ['mongodb-container'];
    }

    #[Test]
    public function getPortReturnsInitialValueForInt(): void
    {
        self::assertSame(0, $this->subject->getPort());
    }

    #[Test]
    public function setPortSetsPort(): void
    {
        $this->subject->setPort(27017);
        self::assertSame(27017, $this->subject->getPort());
    }

    #[Test]
    #[DataProvider('portDataProvider')]
    public function setPortForIntAcceptsVariousValues(int $port): void
    {
        $this->subject->setPort($port);
        self::assertSame($port, $this->subject->getPort());
    }

    public static function portDataProvider(): \Generator
    {
        yield 'zero' => [0];
        yield 'default mongodb port' => [27017];
        yield 'alternative port' => [27018];
        yield 'high port' => [65535];
        yield 'low port' => [1];
    }

    #[Test]
    public function getDbnameReturnsInitialValueForString(): void
    {
        self::assertSame('', $this->subject->getDbname());
    }

    #[Test]
    public function setDbnameSetsDbname(): void
    {
        $this->subject->setDbname('testDatabase');
        self::assertSame('testDatabase', $this->subject->getDbname());
    }

    #[Test]
    #[DataProvider('dbnameDataProvider')]
    public function setDbnameForStringAcceptsVariousValues(string $dbname): void
    {
        $this->subject->setDbname($dbname);
        self::assertSame($dbname, $this->subject->getDbname());
    }

    public static function dbnameDataProvider(): \Generator
    {
        yield 'empty string' => [''];
        yield 'simple name' => ['mydb'];
        yield 'name with underscore' => ['my_database'];
        yield 'name with hyphen' => ['my-database'];
        yield 'name with numbers' => ['db123'];
        yield 'camelCase name' => ['myDatabaseName'];
    }

    #[Test]
    public function fluentSettersAllowChaining(): void
    {
        // Test that all properties can be set independently
        $this->subject->setUser('testUser');
        $this->subject->setPassword('testPassword');
        $this->subject->setHost('localhost');
        $this->subject->setPort(27017);
        $this->subject->setDbname('testDb');

        self::assertSame('testUser', $this->subject->getUser());
        self::assertSame('testPassword', $this->subject->getPassword());
        self::assertSame('localhost', $this->subject->getHost());
        self::assertSame(27017, $this->subject->getPort());
        self::assertSame('testDb', $this->subject->getDbname());
    }

    #[Test]
    public function multipleSetCallsOverwritePreviousValues(): void
    {
        $this->subject->setUser('firstUser');
        $this->subject->setUser('secondUser');

        self::assertSame('secondUser', $this->subject->getUser());
    }

    #[Test]
    public function fullConfigurationCanBeSet(): void
    {
        $this->subject->setUser('mongoUser');
        $this->subject->setPassword('mongoPass');
        $this->subject->setHost('mongo.example.com');
        $this->subject->setPort(27017);
        $this->subject->setDbname('production');

        self::assertSame('mongoUser', $this->subject->getUser());
        self::assertSame('mongoPass', $this->subject->getPassword());
        self::assertSame('mongo.example.com', $this->subject->getHost());
        self::assertSame(27017, $this->subject->getPort());
        self::assertSame('production', $this->subject->getDbname());
    }
}
