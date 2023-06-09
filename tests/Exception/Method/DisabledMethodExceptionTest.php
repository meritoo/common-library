<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Test\Common\Exception\Method;

use Generator;
use Meritoo\Common\Exception\Method\DisabledMethodException;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Traits\Test\Base\BaseTestCaseTrait;
use Meritoo\Common\Type\Base\BaseType;
use Meritoo\Common\Type\OopVisibilityType;
use Meritoo\Common\Utilities\Reflection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(DisabledMethodException::class)]
#[UsesClass(BaseTestCaseTrait::class)]
#[UsesClass(BaseType::class)]
#[UsesClass(Reflection::class)]
class DisabledMethodExceptionTest extends BaseTestCase
{
    /**
     * Provides name of the disabled method, name of the alternative method and expected exception's message
     *
     * @return Generator
     */
    public static function provideMethodsNames(): Generator
    {
        $templateShort = 'Method %s() cannot be called, because is disabled.';
        $templateLong = $templateShort.' Use %s() instead.';

        yield [
            'FooBar::loremIpsum',
            '',
            sprintf($templateShort, 'FooBar::loremIpsum'),
        ];

        yield [
            'FooBar::loremIpsum',
            'AnotherClass::alternativeMethod',
            sprintf($templateLong, 'FooBar::loremIpsum', 'AnotherClass::alternativeMethod'),
        ];
    }

    /**
     * @param string $disabledMethod    Name of the disabled method
     * @param string $alternativeMethod Name of the alternative method
     * @param string $expectedMessage   Expected exception's message
     *
     * @internal     param string $emptyFilePath Path of the empty file
     * @dataProvider provideMethodsNames
     */
    public function testConstructorMessage($disabledMethod, $alternativeMethod, $expectedMessage)
    {
        $exception = DisabledMethodException::create($disabledMethod, $alternativeMethod);
        static::assertSame($expectedMessage, $exception->getMessage());
    }

    public function testConstructorVisibilityAndArguments()
    {
        static::assertConstructorVisibilityAndArguments(DisabledMethodException::class, OopVisibilityType::IS_PUBLIC, 3);
    }
}
