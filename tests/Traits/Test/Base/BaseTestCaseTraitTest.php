<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Test\Common\Traits\Test\Base;

use DateTime;
use Meritoo\Common\Exception\Base\UnknownTypeException;
use Meritoo\Common\Exception\Reflection\ClassWithoutConstructorException;
use Meritoo\Common\Exception\Type\UnknownOopVisibilityTypeException;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Traits\Test\Base\BaseTestCaseTrait;
use Meritoo\Common\Type\Base\BaseType;
use Meritoo\Common\Type\OopVisibilityType;
use Meritoo\Common\Utilities\Arrays;
use Meritoo\Common\Utilities\Reflection;
use Meritoo\Common\Utilities\Regex;
use Meritoo\Test\Common\Traits\Test\Base\BaseTestCaseTrait\SimpleTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use ReflectionMethod;
use stdClass;

#[CoversClass(BaseTestCaseTrait::class)]
#[UsesClass(ClassWithoutConstructorException::class)]
#[UsesClass(UnknownTypeException::class)]
#[UsesClass(UnknownOopVisibilityTypeException::class)]
#[UsesClass(BaseType::class)]
#[UsesClass(Arrays::class)]
#[UsesClass(Reflection::class)]
#[UsesClass(Regex::class)]
class BaseTestCaseTraitTest extends BaseTestCase
{
    use BaseTestCaseTrait;

    private SimpleTestCase $instance;

    public function testAssertConstructorVisibilityAndArgumentsUsingClassWithoutConstructor(): void
    {
        $this->expectException(ClassWithoutConstructorException::class);
        static::assertConstructorVisibilityAndArguments(SimpleTestCase::class, OopVisibilityType::IS_PUBLIC);
    }

    public function testAssertHasNoConstructor(): void
    {
        static::assertHasNoConstructor(SimpleTestCase::class);
    }

    public function testAssertMethodVisibility(): void
    {
        $method = new ReflectionMethod(SimpleTestCase::class, 'assertMethodVisibility');
        static::assertMethodVisibility($method, OopVisibilityType::IS_PROTECTED);
    }

    public function testAssertMethodVisibilityUsingIncorrectVisibility(): void
    {
        $this->expectException(UnknownOopVisibilityTypeException::class);

        $method = new ReflectionMethod(SimpleTestCase::class, 'assertMethodVisibility');
        static::assertMethodVisibility($method, '4');
    }

    public function testAssertMethodVisibilityUsingPrivate(): void
    {
        $method = new ReflectionMethod(SimpleTestCase::class, 'thePrivateMethod');
        static::assertMethodVisibility($method, OopVisibilityType::IS_PRIVATE);
    }

    public function testProvideBooleanValue(): void
    {
        $values = $this->instance->provideBooleanValue();

        $expected = [
            [false],
            [true],
        ];

        foreach ($values as $index => $value) {
            static::assertSame($expected[$index], $value);
        }
    }

    public function testProvideDateTimeInstance(): void
    {
        $instances = $this->instance->provideDateTimeInstance();

        $expected = [
            [new DateTime()],
            [new DateTime('yesterday')],
            [new DateTime('now')],
            [new DateTime('tomorrow')],
        ];

        foreach ($instances as $index => $instance) {
            /** @var DateTime $expectedInstance */
            $expectedInstance = $expected[$index][0];

            /** @var DateTime $instance */
            $instance = $instance[0];

            static::assertInstanceOf(DateTime::class, $instance);
            static::assertEquals($expectedInstance->getTimestamp(), $instance->getTimestamp());
        }
    }

    public function testProvideDateTimeRelativeFormatInstance(): void
    {
        $formats = $this->instance->provideDateTimeRelativeFormat();

        $expected = [
            ['now'],
            ['yesterday'],
            ['tomorrow'],
            ['back of 10'],
            ['front of 10'],
            ['last day of February'],
            ['first day of next month'],
            ['last day of previous month'],
            ['last day of next month'],
            ['Y-m-d'],
            ['Y-m-d 10:00'],
        ];

        foreach ($formats as $index => $format) {
            static::assertSame($expected[$index], $format);
        }
    }

    public function testProvideEmptyScalarValue(): void
    {
        $values = $this->instance->provideEmptyScalarValue();

        $expected = [
            [''],
            ['   '],
            ['0'],
            [0],
            [false],
        ];

        foreach ($values as $index => $value) {
            static::assertSame($expected[$index], $value);
        }
    }

    public function testProvideEmptyValue(): void
    {
        $values = $this->instance->provideEmptyValue();

        $expected = [
            [''],
            ['   '],
            ['0'],
            [0],
            [false],
            [null],
            [[]],
        ];

        foreach ($values as $index => $value) {
            static::assertSame($expected[$index], $value);
        }
    }

    public function testProvideNonScalarValue(): void
    {
        $values = $this->instance->provideNonScalarValue();

        $expected = [
            [[]],
            [null],
            [new stdClass()],
        ];

        foreach ($values as $index => $value) {
            static::assertEquals($expected[$index], $value);
        }
    }

    public function testProvideNotExistingFilePath(): void
    {
        $paths = $this->instance->provideNotExistingFilePath();

        $expected = [
            ['lets-test.doc'],
            ['lorem/ipsum.jpg'],
            ['surprise/me/one/more/time.txt'],
        ];

        foreach ($paths as $index => $path) {
            static::assertSame($expected[$index], $path);
        }
    }

    public function testSetTestsDataDirPath(): void
    {
        $this->instance->changeTestsDataDirPath();

        $value = Reflection::getPropertyValue($this->instance, 'testsDataDirPath');
        self::assertSame('just testing', $value);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->instance = new SimpleTestCase();
    }
}
