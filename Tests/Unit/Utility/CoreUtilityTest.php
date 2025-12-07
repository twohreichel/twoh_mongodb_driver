<?php

declare(strict_types=1);

namespace TWOH\TwohMongodbDriver\Tests\Unit\Utility;

use Generator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TWOH\TwohMongodbDriver\Utility\CoreUtility;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

#[CoversClass(CoreUtility::class)]
final class CoreUtilityTest extends UnitTestCase
{
    #[Test]
    public function toUpperCamelCaseConvertsSnakeCaseToUpperCamelCase(): void
    {
        $result = CoreUtility::toUpperCamelCase('hello_world');
        self::assertSame('HelloWorld', $result);
    }

    #[Test]
    #[DataProvider('snakeCaseToUpperCamelCaseDataProvider')]
    public function toUpperCamelCaseConvertsVariousFormats(string $input, string $expected): void
    {
        $result = CoreUtility::toUpperCamelCase($input);
        self::assertSame($expected, $result);
    }

    public static function snakeCaseToUpperCamelCaseDataProvider(): Generator
    {
        yield 'empty string' => ['', ''];
        yield 'single word lowercase' => ['hello', 'Hello'];
        yield 'single word uppercase' => ['HELLO', 'HELLO'];
        yield 'single word mixed case' => ['HeLLo', 'HeLLo'];
        yield 'two words snake case' => ['hello_world', 'HelloWorld'];
        yield 'three words snake case' => ['hello_beautiful_world', 'HelloBeautifulWorld'];
        yield 'multiple underscores' => ['a_b_c_d_e', 'ABCDE'];
        yield 'already camelCase' => ['helloWorld', 'HelloWorld'];
        yield 'with numbers' => ['hello_world_123', 'HelloWorld123'];
        yield 'leading underscore' => ['_hello', 'Hello'];
        yield 'trailing underscore' => ['hello_', 'Hello'];
        yield 'multiple consecutive underscores' => ['hello__world', 'HelloWorld'];
        yield 'single character parts' => ['a_b_c', 'ABC'];
        yield 'admin_mongodb identifier' => ['admin_mongodb', 'AdminMongodb'];
        yield 'browse_collection identifier' => ['browse_collection', 'BrowseCollection'];
        yield 'typo3 style identifier' => ['ext_base_controller', 'ExtBaseController'];
        yield 'all uppercase parts' => ['HTML_PARSER', 'HTMLPARSER'];
    }

    #[Test]
    public function toUpperCamelCaseHandlesEdgeCases(): void
    {
        // Test with only underscores
        self::assertSame('', CoreUtility::toUpperCamelCase('_'));
        self::assertSame('', CoreUtility::toUpperCamelCase('___'));

        // Test with spaces (no conversion expected for spaces - only underscore is the separator)
        // The method uses explode('_', ...) so spaces remain as-is, only first char is ucfirst'd
        self::assertSame('Hello world', CoreUtility::toUpperCamelCase('hello world'));
    }

    #[Test]
    public function toUpperCamelCasePreservesAlreadyCapitalizedParts(): void
    {
        // When parts are already capitalized, ucfirst won't change them
        $result = CoreUtility::toUpperCamelCase('MY_CONSTANT');
        self::assertSame('MYCONSTANT', $result);
    }

    #[Test]
    public function toUpperCamelCaseReturnsStringType(): void
    {
        $result = CoreUtility::toUpperCamelCase('test_value');
        self::assertIsString($result);
    }

    #[Test]
    public function toUpperCamelCaseIsStaticMethod(): void
    {
        // Verify that the method can be called statically
        $result = CoreUtility::toUpperCamelCase('static_call');
        self::assertSame('StaticCall', $result);
    }

    #[Test]
    public function toUpperCamelCaseHandlesUnicodeCharacters(): void
    {
        // Test with unicode characters
        // Note: ucfirst doesn't handle multibyte characters by default
        $result = CoreUtility::toUpperCamelCase('über_mensch');
        self::assertSame('überMensch', $result);
    }

    #[Test]
    public function toUpperCamelCaseHandlesNumericStrings(): void
    {
        $result = CoreUtility::toUpperCamelCase('123_456');
        self::assertSame('123456', $result);
    }

    #[Test]
    public function toUpperCamelCaseHandlesMixedContent(): void
    {
        $result = CoreUtility::toUpperCamelCase('test_123_value');
        self::assertSame('Test123Value', $result);
    }
}
