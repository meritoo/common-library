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
use Meritoo\Common\Type\OopVisibilityType;

/**
 * Test case of an exception used while method cannot be called, because is disabled
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class DisabledMethodExceptionTest extends BaseTestCase
{
    public function testConstructorVisibilityAndArguments()
    {
        static::assertConstructorVisibilityAndArguments(DisabledMethodException::class, OopVisibilityType::IS_PUBLIC, 3);
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

    /**
     * Provides name of the disabled method, name of the alternative method and expected exception's message
     *
     * @return Generator
     */
    public function provideMethodsNames()
    {
        $templateShort = 'Method %s() cannot be called, because is disabled.';
        $templateLong = $templateShort . ' Use %s() instead.';

        yield[
            'FooBar::loremIpsum',
            '',
            sprintf($templateShort, 'FooBar::loremIpsum'),
        ];

        yield[
            'FooBar::loremIpsum',
            'AnotherClass::alternativeMethod',
            sprintf($templateLong, 'FooBar::loremIpsum', 'AnotherClass::alternativeMethod'),
        ];
    }
}
