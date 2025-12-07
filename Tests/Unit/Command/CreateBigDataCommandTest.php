<?php

declare(strict_types=1);

namespace TWOH\TwohMongodbDriver\Tests\Unit\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use TWOH\TwohMongodbDriver\Command\CreateBigDataCommand;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

#[CoversClass(CreateBigDataCommand::class)]
final class CreateBigDataCommandTest extends UnitTestCase
{
    protected bool $resetSingletonInstances = true;

    private CreateBigDataCommand $subject;

    protected function setUp(): void
    {
        parent::setUp();
        $this->subject = new CreateBigDataCommand();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->subject);
    }

    #[Test]
    public function constructorCreatesInstance(): void
    {
        self::assertInstanceOf(CreateBigDataCommand::class, $this->subject);
    }

    #[Test]
    public function commandExtendsSymfonyCommand(): void
    {
        self::assertInstanceOf(Command::class, $this->subject);
    }

    #[Test]
    public function commandImplementsLoggerAwareInterface(): void
    {
        self::assertInstanceOf(\Psr\Log\LoggerAwareInterface::class, $this->subject);
    }

    #[Test]
    public function configureSetshelpText(): void
    {
        // Use reflection to invoke protected configure method
        $reflection = new \ReflectionClass($this->subject);
        $method = $reflection->getMethod('configure');
        $method->setAccessible(true);

        $method->invoke($this->subject);

        self::assertSame('A command to create big test data.', $this->subject->getHelp());
    }

    #[Test]
    public function setLoggerSetsLogger(): void
    {
        $loggerMock = $this->createMock(LoggerInterface::class);

        $this->subject->setLogger($loggerMock);

        // Use reflection to verify logger was set
        $reflection = new \ReflectionClass($this->subject);
        $property = $reflection->getProperty('logger');
        $property->setAccessible(true);

        self::assertSame($loggerMock, $property->getValue($this->subject));
    }

    #[Test]
    public function commandNameCanBeSet(): void
    {
        $this->subject->setName('test:bigdata:create');

        self::assertSame('test:bigdata:create', $this->subject->getName());
    }

    #[Test]
    public function commandDescriptionCanBeSet(): void
    {
        $this->subject->setDescription('Creates big test data for MongoDB');

        self::assertSame('Creates big test data for MongoDB', $this->subject->getDescription());
    }

    #[Test]
    public function executeMethodExists(): void
    {
        $reflection = new \ReflectionClass(CreateBigDataCommand::class);

        self::assertTrue($reflection->hasMethod('execute'));

        $method = $reflection->getMethod('execute');
        self::assertTrue($method->isProtected());
    }

    #[Test]
    public function executeMethodHasCorrectParameters(): void
    {
        $reflection = new \ReflectionClass(CreateBigDataCommand::class);
        $method = $reflection->getMethod('execute');
        $parameters = $method->getParameters();

        self::assertCount(2, $parameters);
        self::assertSame('input', $parameters[0]->getName());
        self::assertSame('output', $parameters[1]->getName());
    }

    #[Test]
    public function executeMethodReturnsInteger(): void
    {
        $reflection = new \ReflectionClass(CreateBigDataCommand::class);
        $method = $reflection->getMethod('execute');
        $returnType = $method->getReturnType();

        self::assertNotNull($returnType);
        self::assertSame('int', $returnType->getName());
    }

    #[Test]
    public function configureMethodExists(): void
    {
        $reflection = new \ReflectionClass(CreateBigDataCommand::class);

        self::assertTrue($reflection->hasMethod('configure'));

        $method = $reflection->getMethod('configure');
        self::assertTrue($method->isProtected());
    }

    #[Test]
    public function loggerPropertyExists(): void
    {
        // LoggerAwareTrait adds a logger property
        $reflection = new \ReflectionClass(CreateBigDataCommand::class);

        self::assertTrue($reflection->hasProperty('logger'));
    }

    #[Test]
    public function usesLoggerAwareTrait(): void
    {
        $reflection = new \ReflectionClass(CreateBigDataCommand::class);
        $traits = $reflection->getTraitNames();

        self::assertContains('Psr\Log\LoggerAwareTrait', $traits);
    }
}
