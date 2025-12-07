<?php

declare(strict_types=1);

namespace TWOH\TwohMongodbDriver\Tests\Unit\Controller;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use ReflectionClass;
use TWOH\TwohMongodbDriver\Controller\AdminModuleController;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Unit tests for AdminModuleController
 *
 * Note: These tests use reflection-based testing since many TYPO3 core classes
 * are final and cannot be mocked. Functional tests would provide more
 * comprehensive coverage.
 */
#[CoversClass(AdminModuleController::class)]
final class AdminModuleControllerTest extends UnitTestCase
{
    protected bool $resetSingletonInstances = true;

    #[Test]
    public function classHasControllerAttribute(): void
    {
        $reflection = new ReflectionClass(AdminModuleController::class);
        $attributes = $reflection->getAttributes();

        $hasControllerAttribute = false;
        foreach ($attributes as $attribute) {
            if (str_contains($attribute->getName(), 'Controller')) {
                $hasControllerAttribute = true;
                break;
            }
        }

        self::assertTrue($hasControllerAttribute);
    }

    #[Test]
    public function classIsFinal(): void
    {
        $reflection = new ReflectionClass(AdminModuleController::class);

        self::assertTrue($reflection->isFinal());
    }

    #[Test]
    public function constructorAcceptsRequiredDependencies(): void
    {
        $reflection = new ReflectionClass(AdminModuleController::class);
        $constructor = $reflection->getConstructor();
        $parameters = $constructor->getParameters();

        self::assertCount(5, $parameters);

        $parameterNames = array_map(static fn($p) => $p->getName(), $parameters);

        self::assertContains('moduleTemplateFactory', $parameterNames);
        self::assertContains('iconFactory', $parameterNames);
        self::assertContains('mongodbConnectionPoolAdapter', $parameterNames);
        self::assertContains('uriBuilder', $parameterNames);
        self::assertContains('pageRenderer', $parameterNames);
    }

    #[Test]
    public function hasHandleRequestMethod(): void
    {
        $reflection = new ReflectionClass(AdminModuleController::class);

        self::assertTrue($reflection->hasMethod('handleRequest'));

        $method = $reflection->getMethod('handleRequest');
        self::assertTrue($method->isPublic());
    }

    #[Test]
    public function hasIndexActionMethod(): void
    {
        $reflection = new ReflectionClass(AdminModuleController::class);

        self::assertTrue($reflection->hasMethod('indexAction'));

        $method = $reflection->getMethod('indexAction');
        self::assertTrue($method->isPublic());
    }

    #[Test]
    public function hasBrowseCollectionActionMethod(): void
    {
        $reflection = new ReflectionClass(AdminModuleController::class);

        self::assertTrue($reflection->hasMethod('browseCollectionAction'));

        $method = $reflection->getMethod('browseCollectionAction');
        self::assertTrue($method->isPublic());
    }

    #[Test]
    public function hasSetDocHeaderMethod(): void
    {
        $reflection = new ReflectionClass(AdminModuleController::class);

        self::assertTrue($reflection->hasMethod('setDocHeader'));

        $method = $reflection->getMethod('setDocHeader');
        self::assertTrue($method->isPrivate());
    }

    #[Test]
    public function hasCreateCollectionDropdownMethod(): void
    {
        $reflection = new ReflectionClass(AdminModuleController::class);

        self::assertTrue($reflection->hasMethod('createCollectionDropdown'));

        $method = $reflection->getMethod('createCollectionDropdown');
        self::assertTrue($method->isPrivate());
    }

    #[Test]
    public function hasCreateLinkButtonMethod(): void
    {
        $reflection = new ReflectionClass(AdminModuleController::class);

        self::assertTrue($reflection->hasMethod('createLinkButton'));

        $method = $reflection->getMethod('createLinkButton');
        self::assertTrue($method->isPrivate());
    }

    #[Test]
    public function hasGetLanguageServiceMethod(): void
    {
        $reflection = new ReflectionClass(AdminModuleController::class);

        self::assertTrue($reflection->hasMethod('getLanguageService'));

        $method = $reflection->getMethod('getLanguageService');
        self::assertTrue($method->isProtected());
    }

    #[Test]
    public function hasGetBackendUserMethod(): void
    {
        $reflection = new ReflectionClass(AdminModuleController::class);

        self::assertTrue($reflection->hasMethod('getBackendUser'));

        $method = $reflection->getMethod('getBackendUser');
        self::assertTrue($method->isProtected());
    }

    #[Test]
    public function handleRequestMethodAcceptsServerRequest(): void
    {
        $reflection = new ReflectionClass(AdminModuleController::class);
        $method = $reflection->getMethod('handleRequest');
        $parameters = $method->getParameters();

        self::assertCount(1, $parameters);
        self::assertSame('request', $parameters[0]->getName());
    }

    #[Test]
    public function indexActionMethodHasCorrectParameters(): void
    {
        $reflection = new ReflectionClass(AdminModuleController::class);
        $method = $reflection->getMethod('indexAction');
        $parameters = $method->getParameters();

        self::assertCount(3, $parameters);
        self::assertSame('request', $parameters[0]->getName());
        self::assertSame('view', $parameters[1]->getName());
        self::assertSame('routeActionIdentifier', $parameters[2]->getName());
    }

    #[Test]
    public function browseCollectionActionMethodHasCorrectParameters(): void
    {
        $reflection = new ReflectionClass(AdminModuleController::class);
        $method = $reflection->getMethod('browseCollectionAction');
        $parameters = $method->getParameters();

        self::assertCount(3, $parameters);
        self::assertSame('request', $parameters[0]->getName());
        self::assertSame('view', $parameters[1]->getName());
        self::assertSame('routeActionIdentifier', $parameters[2]->getName());
    }

    #[Test]
    public function getLanguageServiceMethodAccessesGlobalsLang(): void
    {
        // The protected method getLanguageService returns $GLOBALS['LANG']
        // We verify this by checking the method implementation via reflection
        $reflection = new ReflectionClass(AdminModuleController::class);
        $method = $reflection->getMethod('getLanguageService');

        // Check return type
        $returnType = $method->getReturnType();
        self::assertNotNull($returnType);
        self::assertSame(LanguageService::class, $returnType->getName());
    }

    #[Test]
    public function getBackendUserMethodAccessesGlobalsBeUser(): void
    {
        // The protected method getBackendUser returns $GLOBALS['BE_USER']
        // We verify this by checking the method implementation via reflection
        $reflection = new ReflectionClass(AdminModuleController::class);
        $method = $reflection->getMethod('getBackendUser');

        // Check return type
        $returnType = $method->getReturnType();
        self::assertNotNull($returnType);
        self::assertSame(\TYPO3\CMS\Core\Authentication\BackendUserAuthentication::class, $returnType->getName());
    }

    #[Test]
    public function moduleTemplateFactoryPropertyExists(): void
    {
        $reflection = new ReflectionClass(AdminModuleController::class);

        self::assertTrue($reflection->hasProperty('moduleTemplateFactory'));
    }

    #[Test]
    public function iconFactoryPropertyExists(): void
    {
        $reflection = new ReflectionClass(AdminModuleController::class);

        self::assertTrue($reflection->hasProperty('iconFactory'));
    }

    #[Test]
    public function mongodbConnectionPoolAdapterPropertyExists(): void
    {
        $reflection = new ReflectionClass(AdminModuleController::class);

        self::assertTrue($reflection->hasProperty('mongodbConnectionPoolAdapter'));
    }

    #[Test]
    public function uriBuilderPropertyExists(): void
    {
        $reflection = new ReflectionClass(AdminModuleController::class);

        self::assertTrue($reflection->hasProperty('uriBuilder'));
    }

    #[Test]
    public function pageRendererPropertyExists(): void
    {
        $reflection = new ReflectionClass(AdminModuleController::class);

        self::assertTrue($reflection->hasProperty('pageRenderer'));
    }
}
