<?php

declare(strict_types=1);

namespace TWOH\TwohMongodbDriver\Tests\Unit\Converter;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use TWOH\TwohMongodbDriver\Converter\ArrayToModelConverter;
use TWOH\TwohMongodbDriver\Domain\Model\MongodbConfiguration;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

#[CoversClass(ArrayToModelConverter::class)]
final class ArrayToModelConverterTest extends UnitTestCase
{
    #[Test]
    public function mapArrayReturnsObjectOfSameType(): void
    {
        $object = new MongodbConfiguration();
        $array = [];

        $result = ArrayToModelConverter::mapArray($array, $object);

        self::assertInstanceOf(MongodbConfiguration::class, $result);
    }

    #[Test]
    public function mapArraySetsPropertiesFromArrayToObject(): void
    {
        $object = new MongodbConfiguration();
        $array = [
            'user' => 'testUser',
            'password' => 'testPassword',
            'host' => 'localhost',
            'port' => 27017,
            'dbname' => 'testDb',
        ];

        $result = ArrayToModelConverter::mapArray($array, $object);

        self::assertSame('testUser', $result->getUser());
        self::assertSame('testPassword', $result->getPassword());
        self::assertSame('localhost', $result->getHost());
        self::assertSame(27017, $result->getPort());
        self::assertSame('testDb', $result->getDbname());
    }

    #[Test]
    public function mapArrayIgnoresNonExistingSetters(): void
    {
        $object = new MongodbConfiguration();
        $array = [
            'user' => 'testUser',
            'nonExistingProperty' => 'someValue',
        ];

        $result = ArrayToModelConverter::mapArray($array, $object);

        self::assertSame('testUser', $result->getUser());
        // No exception should be thrown for non-existing property
    }

    #[Test]
    public function mapArrayIgnoresEmptyValues(): void
    {
        $object = new MongodbConfiguration();
        $object->setUser('initialUser');

        $array = [
            'user' => '',
        ];

        $result = ArrayToModelConverter::mapArray($array, $object);

        // Empty value should not overwrite initial value
        self::assertSame('initialUser', $result->getUser());
    }

    #[Test]
    public function mapArrayIgnoresNullValues(): void
    {
        $object = new MongodbConfiguration();
        $object->setUser('initialUser');

        $array = [
            'user' => null,
        ];

        $result = ArrayToModelConverter::mapArray($array, $object);

        // Null value should not overwrite initial value
        self::assertSame('initialUser', $result->getUser());
    }

    #[Test]
    public function mapArrayHandlesEmptyArray(): void
    {
        $object = new MongodbConfiguration();
        $array = [];

        $result = ArrayToModelConverter::mapArray($array, $object);

        // Object should remain unchanged
        self::assertSame('', $result->getUser());
        self::assertSame('', $result->getPassword());
        self::assertSame('', $result->getHost());
        self::assertSame(0, $result->getPort());
        self::assertSame('', $result->getDbname());
    }

    #[Test]
    public function mapArrayPartiallyMapsProperties(): void
    {
        $object = new MongodbConfiguration();
        $array = [
            'user' => 'partialUser',
            'host' => 'partialHost',
        ];

        $result = ArrayToModelConverter::mapArray($array, $object);

        self::assertSame('partialUser', $result->getUser());
        self::assertSame('', $result->getPassword());
        self::assertSame('partialHost', $result->getHost());
        self::assertSame(0, $result->getPort());
        self::assertSame('', $result->getDbname());
    }

    #[Test]
    public function mapArrayIsStaticMethod(): void
    {
        // Verify that the method can be called statically
        $object = new MongodbConfiguration();
        $result = ArrayToModelConverter::mapArray(['user' => 'test'], $object);

        self::assertInstanceOf(MongodbConfiguration::class, $result);
    }

    #[Test]
    public function mapArrayWithCustomObject(): void
    {
        $customObject = new class {
            private string $name = '';
            private int $age = 0;

            public function getName(): string
            {
                return $this->name;
            }

            public function setName(string $name): void
            {
                $this->name = $name;
            }

            public function getAge(): int
            {
                return $this->age;
            }

            public function setAge(int $age): void
            {
                $this->age = $age;
            }
        };

        $array = [
            'name' => 'TestName',
            'age' => 25,
        ];

        $result = ArrayToModelConverter::mapArray($array, $customObject);

        self::assertSame('TestName', $result->getName());
        self::assertSame(25, $result->getAge());
    }

    #[Test]
    public function mapArrayIgnoresFalseyValuesLikeZero(): void
    {
        $object = new MongodbConfiguration();
        $object->setPort(27017);

        $array = [
            'port' => 0,
        ];

        $result = ArrayToModelConverter::mapArray($array, $object);

        // Zero is a falsey value, so it should not overwrite (based on empty() check)
        self::assertSame(27017, $result->getPort());
    }

    #[Test]
    public function mapArrayHandlesCamelCasePropertyNames(): void
    {
        $object = new MongodbConfiguration();

        // The method uses strtolower on first character, so 'Dbname' becomes 'dbname'
        $array = [
            'dbname' => 'myDatabase',
        ];

        $result = ArrayToModelConverter::mapArray($array, $object);

        self::assertSame('myDatabase', $result->getDbname());
    }

    #[Test]
    public function mapArrayPreservesObjectReference(): void
    {
        $object = new MongodbConfiguration();
        $array = ['user' => 'testUser'];

        $result = ArrayToModelConverter::mapArray($array, $object);

        // The returned object should be the same instance
        self::assertSame($object, $result);
    }
}
