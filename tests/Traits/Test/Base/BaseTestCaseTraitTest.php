<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Test\Common\Traits\Test\Base;

use DateTime;
use Meritoo\Common\Exception\Reflection\ClassWithoutConstructorException;
use Meritoo\Common\Exception\Type\UnknownOopVisibilityTypeException;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Traits\Test\Base\BaseTestCaseTrait;
use Meritoo\Common\Type\OopVisibilityType;
use Meritoo\Test\Common\Traits\Test\Base\BaseTestCaseTrait\SimpleTestCase;
use ReflectionMethod;
use stdClass;

/**
 * Test case of the trait for the base test case
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 *
 * @internal
 * @covers    \Meritoo\Common\Traits\Test\Base\BaseTestCaseTrait
 */
class BaseTestCaseTraitTest extends BaseTestCase
{
    use BaseTestCaseTrait;

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

    public function testAssertConstructorVisibilityAndArgumentsUsingClassWithoutConstructor(): void
    {
        $this->expectException(ClassWithoutConstructorException::class);
        static::assertConstructorVisibilityAndArguments(SimpleTestCase::class, OopVisibilityType::IS_PUBLIC);
    }

    public function testAssertHasNoConstructor(): void
    {
        static::assertHasNoConstructor(SimpleTestCase::class);
    }

    public function testProvideEmptyValue(): void
    {
        $testCase = new SimpleTestCase();
        $values = $testCase->provideEmptyValue();

        $expected = [
            [''],
            ['   '],
            [null],
            [0],
            [false],
            [[]],
        ];

        foreach ($values as $index => $value) {
            static::assertSame($expected[$index], $value);
        }
    }

    public function testProvideEmptyScalarValue(): void
    {
        $testCase = new SimpleTestCase();
        $values = $testCase->provideEmptyScalarValue();

        $expected = [
            [''],
            ['   '],
            [null],
            [0],
            [false],
        ];

        foreach ($values as $index => $value) {
            static::assertSame($expected[$index], $value);
        }
    }

    public function testProvideBooleanValue(): void
    {
        $testCase = new SimpleTestCase();
        $values = $testCase->provideBooleanValue();

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
        $testCase = new SimpleTestCase();
        $instances = $testCase->provideDateTimeInstance();

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
        $testCase = new SimpleTestCase();
        $formats = $testCase->provideDateTimeRelativeFormat();

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

    public function testProvideNotExistingFilePath(): void
    {
        $testCase = new SimpleTestCase();
        $paths = $testCase->provideNotExistingFilePath();

        $expected = [
            ['lets-test.doc'],
            ['lorem/ipsum.jpg'],
            ['surprise/me/one/more/time.txt'],
        ];

        foreach ($paths as $index => $path) {
            static::assertSame($expected[$index], $path);
        }
    }

    public function testProvideNonScalarValue(): void
    {
        $testCase = new SimpleTestCase();
        $values = $testCase->provideNonScalarValue();

        $expected = [
            [[]],
            [null],
            [new stdClass()],
        ];

        foreach ($values as $index => $value) {
            static::assertEquals($expected[$index], $value);
        }
    }
}
