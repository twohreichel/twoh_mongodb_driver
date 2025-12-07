<?php

declare(strict_types=1);

namespace TWOH\TwohMongodbDriver\Tests\Unit\Adapter;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use TWOH\TwohMongodbDriver\Adapter\MongodbConnectionPoolAdapter;
use TWOH\TwohMongodbDriver\Database\Connection\MongoDbConnection;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Unit tests for MongodbConnectionPoolAdapter
 *
 * Note: Tests that require a real MongoDbConnection instance are tested via
 * reflection since the class is final and the method has a typed return value.
 */
#[CoversClass(MongodbConnectionPoolAdapter::class)]
final class MongodbConnectionPoolAdapterTest extends UnitTestCase
{
    protected bool $resetSingletonInstances = true;

    private MongodbConnectionPoolAdapter $subject;

    protected function setUp(): void
    {
        parent::setUp();
        $this->subject = new MongodbConnectionPoolAdapter();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->subject);
        if (isset($GLOBALS['TYPO3_CONF_VARS']['DRIVER']['MongoDB']['ConnectionPool'])) {
            unset($GLOBALS['TYPO3_CONF_VARS']['DRIVER']['MongoDB']['ConnectionPool']);
        }
    }

    #[Test]
    public function constructorCreatesInstance(): void
    {
        $adapter = new MongodbConnectionPoolAdapter();

        self::assertInstanceOf(MongodbConnectionPoolAdapter::class, $adapter);
    }

    #[Test]
    public function getConnectionPoolMethodExists(): void
    {
        $reflection = new \ReflectionClass(MongodbConnectionPoolAdapter::class);

        self::assertTrue($reflection->hasMethod('getConnectionPool'));

        $method = $reflection->getMethod('getConnectionPool');
        self::assertTrue($method->isPublic());
    }

    #[Test]
    public function getConnectionPoolMethodHasCorrectReturnType(): void
    {
        $reflection = new \ReflectionClass(MongodbConnectionPoolAdapter::class);
        $method = $reflection->getMethod('getConnectionPool');
        $returnType = $method->getReturnType();

        self::assertNotNull($returnType);
        self::assertSame(MongoDbConnection::class, $returnType->getName());
    }

    #[Test]
    public function getConnectionPoolMethodHasNoParameters(): void
    {
        $reflection = new \ReflectionClass(MongodbConnectionPoolAdapter::class);
        $method = $reflection->getMethod('getConnectionPool');
        $parameters = $method->getParameters();

        self::assertCount(0, $parameters);
    }

    #[Test]
    public function classHasNoProperties(): void
    {
        $reflection = new \ReflectionClass(MongodbConnectionPoolAdapter::class);
        $properties = $reflection->getProperties();

        // Adapter is a simple accessor, no internal state expected
        self::assertCount(0, $properties);
    }

    #[Test]
    public function classIsNotFinal(): void
    {
        $reflection = new \ReflectionClass(MongodbConnectionPoolAdapter::class);

        // Can be extended if needed
        self::assertFalse($reflection->isFinal());
    }

    #[Test]
    public function classIsNotAbstract(): void
    {
        $reflection = new \ReflectionClass(MongodbConnectionPoolAdapter::class);

        self::assertFalse($reflection->isAbstract());
    }

    #[Test]
    public function methodAccessesGlobalConfigurationPath(): void
    {
        // Verify the method accesses the correct global path
        // by reading the method implementation
        $reflection = new \ReflectionClass(MongodbConnectionPoolAdapter::class);
        $method = $reflection->getMethod('getConnectionPool');

        // Get the method source using reflection
        $filename = $reflection->getFileName();
        $startLine = $method->getStartLine();
        $endLine = $method->getEndLine();

        $source = file_get_contents($filename);
        $lines = explode("\n", $source);
        $methodSource = implode("\n", array_slice($lines, $startLine - 1, $endLine - $startLine + 1));

        // Verify it accesses the correct global path
        self::assertStringContainsString("TYPO3_CONF_VARS", $methodSource);
        self::assertStringContainsString("DRIVER", $methodSource);
        self::assertStringContainsString("MongoDB", $methodSource);
        self::assertStringContainsString("ConnectionPool", $methodSource);
    }
}
