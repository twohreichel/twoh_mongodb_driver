<?php

declare(strict_types=1);

namespace TWOH\TwohMongodbDriver\Tests\Unit\Database\Connection;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use ReflectionClass;
use TWOH\TwohMongodbDriver\Database\Connection\MongoDbConnection;
use TWOH\TwohMongodbDriver\Domain\Model\MongodbConfiguration;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Unit tests for MongoDbConnection
 *
 * Note: These tests use reflection-based testing since the class is final
 * and cannot be mocked. Integration tests with a real MongoDB instance
 * would provide more comprehensive coverage.
 */
#[CoversClass(MongoDbConnection::class)]
final class MongoDbConnectionTest extends UnitTestCase
{
    #[Test]
    public function classIsFinal(): void
    {
        $reflection = new ReflectionClass(MongoDbConnection::class);

        self::assertTrue($reflection->isFinal());
    }

    #[Test]
    public function constructorAcceptsDatabaseAndConfiguration(): void
    {
        $reflection = new ReflectionClass(MongoDbConnection::class);
        $constructor = $reflection->getConstructor();
        $parameters = $constructor->getParameters();

        self::assertCount(2, $parameters);
        self::assertSame('connection', $parameters[0]->getName());
        self::assertSame('mongodbConfiguration', $parameters[1]->getName());
    }

    #[Test]
    public function hasSelectDocumentsMethod(): void
    {
        $reflection = new ReflectionClass(MongoDbConnection::class);

        self::assertTrue($reflection->hasMethod('selectDocuments'));

        $method = $reflection->getMethod('selectDocuments');
        self::assertTrue($method->isPublic());
        self::assertSame('array', $method->getReturnType()->getName());
    }

    #[Test]
    public function hasCountDocumentsMethod(): void
    {
        $reflection = new ReflectionClass(MongoDbConnection::class);

        self::assertTrue($reflection->hasMethod('countDocuments'));

        $method = $reflection->getMethod('countDocuments');
        self::assertTrue($method->isPublic());
        self::assertSame('int', $method->getReturnType()->getName());
    }

    #[Test]
    public function hasUpdateOneDocumentMethod(): void
    {
        $reflection = new ReflectionClass(MongoDbConnection::class);

        self::assertTrue($reflection->hasMethod('updateOneDocument'));

        $method = $reflection->getMethod('updateOneDocument');
        self::assertTrue($method->isPublic());
    }

    #[Test]
    public function hasUpdateManyDocumentsMethod(): void
    {
        $reflection = new ReflectionClass(MongoDbConnection::class);

        self::assertTrue($reflection->hasMethod('updateManyDocuments'));

        $method = $reflection->getMethod('updateManyDocuments');
        self::assertTrue($method->isPublic());
    }

    #[Test]
    public function hasDeleteOneDocumentMethod(): void
    {
        $reflection = new ReflectionClass(MongoDbConnection::class);

        self::assertTrue($reflection->hasMethod('deleteOneDocument'));

        $method = $reflection->getMethod('deleteOneDocument');
        self::assertTrue($method->isPublic());
    }

    #[Test]
    public function hasDeleteManyDocumentsMethod(): void
    {
        $reflection = new ReflectionClass(MongoDbConnection::class);

        self::assertTrue($reflection->hasMethod('deleteManyDocuments'));

        $method = $reflection->getMethod('deleteManyDocuments');
        self::assertTrue($method->isPublic());
    }

    #[Test]
    public function hasInsertOneDocumentMethod(): void
    {
        $reflection = new ReflectionClass(MongoDbConnection::class);

        self::assertTrue($reflection->hasMethod('insertOneDocument'));

        $method = $reflection->getMethod('insertOneDocument');
        self::assertTrue($method->isPublic());
    }

    #[Test]
    public function hasInsertManyDocumentsMethod(): void
    {
        $reflection = new ReflectionClass(MongoDbConnection::class);

        self::assertTrue($reflection->hasMethod('insertManyDocuments'));

        $method = $reflection->getMethod('insertManyDocuments');
        self::assertTrue($method->isPublic());
        self::assertSame('array', $method->getReturnType()->getName());
    }

    #[Test]
    public function hasSelectCollectionMethod(): void
    {
        $reflection = new ReflectionClass(MongoDbConnection::class);

        self::assertTrue($reflection->hasMethod('selectCollection'));

        $method = $reflection->getMethod('selectCollection');
        self::assertTrue($method->isPublic());
    }

    #[Test]
    public function hasModifyCollectionMethod(): void
    {
        $reflection = new ReflectionClass(MongoDbConnection::class);

        self::assertTrue($reflection->hasMethod('modifyCollection'));

        $method = $reflection->getMethod('modifyCollection');
        self::assertTrue($method->isPublic());
    }

    #[Test]
    public function hasDropCollectionMethod(): void
    {
        $reflection = new ReflectionClass(MongoDbConnection::class);

        self::assertTrue($reflection->hasMethod('dropCollection'));

        $method = $reflection->getMethod('dropCollection');
        self::assertTrue($method->isPublic());
    }

    #[Test]
    public function hasCreateCollectionMethod(): void
    {
        $reflection = new ReflectionClass(MongoDbConnection::class);

        self::assertTrue($reflection->hasMethod('createCollection'));

        $method = $reflection->getMethod('createCollection');
        self::assertTrue($method->isPublic());
    }

    #[Test]
    public function hasListCollectionsMethod(): void
    {
        $reflection = new ReflectionClass(MongoDbConnection::class);

        self::assertTrue($reflection->hasMethod('listCollections'));

        $method = $reflection->getMethod('listCollections');
        self::assertTrue($method->isPublic());
    }

    #[Test]
    public function hasGetConnectionMethod(): void
    {
        $reflection = new ReflectionClass(MongoDbConnection::class);

        self::assertTrue($reflection->hasMethod('getConnection'));

        $method = $reflection->getMethod('getConnection');
        self::assertTrue($method->isPublic());
    }

    #[Test]
    public function hasSetConnectionMethod(): void
    {
        $reflection = new ReflectionClass(MongoDbConnection::class);

        self::assertTrue($reflection->hasMethod('setConnection'));

        $method = $reflection->getMethod('setConnection');
        self::assertTrue($method->isPublic());
        self::assertSame('void', $method->getReturnType()->getName());
    }

    #[Test]
    public function hasGetMongodbConfigurationMethod(): void
    {
        $reflection = new ReflectionClass(MongoDbConnection::class);

        self::assertTrue($reflection->hasMethod('getMongodbConfiguration'));

        $method = $reflection->getMethod('getMongodbConfiguration');
        self::assertTrue($method->isPublic());
    }

    #[Test]
    public function hasSetMongodbConfigurationMethod(): void
    {
        $reflection = new ReflectionClass(MongoDbConnection::class);

        self::assertTrue($reflection->hasMethod('setMongodbConfiguration'));

        $method = $reflection->getMethod('setMongodbConfiguration');
        self::assertTrue($method->isPublic());
        self::assertSame('void', $method->getReturnType()->getName());
    }

    #[Test]
    public function selectDocumentsMethodHasCorrectParameters(): void
    {
        $reflection = new ReflectionClass(MongoDbConnection::class);
        $method = $reflection->getMethod('selectDocuments');
        $parameters = $method->getParameters();

        self::assertCount(3, $parameters);
        self::assertSame('collectionName', $parameters[0]->getName());
        self::assertSame('filter', $parameters[1]->getName());
        self::assertSame('options', $parameters[2]->getName());
        self::assertTrue($parameters[2]->isDefaultValueAvailable());
        self::assertSame([], $parameters[2]->getDefaultValue());
    }

    #[Test]
    public function countDocumentsMethodHasCorrectParameters(): void
    {
        $reflection = new ReflectionClass(MongoDbConnection::class);
        $method = $reflection->getMethod('countDocuments');
        $parameters = $method->getParameters();

        self::assertCount(3, $parameters);
        self::assertSame('collectionName', $parameters[0]->getName());
        self::assertSame('filter', $parameters[1]->getName());
        self::assertSame('options', $parameters[2]->getName());
    }

    #[Test]
    public function insertOneDocumentMethodHasCorrectParameters(): void
    {
        $reflection = new ReflectionClass(MongoDbConnection::class);
        $method = $reflection->getMethod('insertOneDocument');
        $parameters = $method->getParameters();

        self::assertCount(3, $parameters);
        self::assertSame('collectionName', $parameters[0]->getName());
        self::assertSame('data', $parameters[1]->getName());
        self::assertSame('options', $parameters[2]->getName());
    }

    #[Test]
    public function insertManyDocumentsMethodHasCorrectParameters(): void
    {
        $reflection = new ReflectionClass(MongoDbConnection::class);
        $method = $reflection->getMethod('insertManyDocuments');
        $parameters = $method->getParameters();

        self::assertCount(3, $parameters);
        self::assertSame('collectionName', $parameters[0]->getName());
        self::assertSame('data', $parameters[1]->getName());
        self::assertSame('options', $parameters[2]->getName());
    }

    #[Test]
    public function updateOneDocumentMethodHasCorrectParameters(): void
    {
        $reflection = new ReflectionClass(MongoDbConnection::class);
        $method = $reflection->getMethod('updateOneDocument');
        $parameters = $method->getParameters();

        self::assertCount(4, $parameters);
        self::assertSame('collectionName', $parameters[0]->getName());
        self::assertSame('filter', $parameters[1]->getName());
        self::assertSame('update', $parameters[2]->getName());
        self::assertSame('options', $parameters[3]->getName());
    }

    #[Test]
    public function deleteOneDocumentMethodHasCorrectParameters(): void
    {
        $reflection = new ReflectionClass(MongoDbConnection::class);
        $method = $reflection->getMethod('deleteOneDocument');
        $parameters = $method->getParameters();

        self::assertCount(3, $parameters);
        self::assertSame('collectionName', $parameters[0]->getName());
        self::assertSame('filter', $parameters[1]->getName());
        self::assertSame('options', $parameters[2]->getName());
    }

    #[Test]
    public function connectionPropertyIsDatabaseType(): void
    {
        $reflection = new ReflectionClass(MongoDbConnection::class);
        $property = $reflection->getProperty('connection');

        self::assertTrue($property->hasType());
        self::assertSame('MongoDB\Database', $property->getType()->getName());
    }

    #[Test]
    public function mongodbConfigurationPropertyIsCorrectType(): void
    {
        $reflection = new ReflectionClass(MongoDbConnection::class);
        $property = $reflection->getProperty('mongodbConfiguration');

        self::assertTrue($property->hasType());
        self::assertSame(MongodbConfiguration::class, $property->getType()->getName());
    }
}
