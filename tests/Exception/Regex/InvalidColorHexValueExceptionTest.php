<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Test\Common\Exception\Regex;

use Generator;
use Meritoo\Common\Enums\OopVisibility;
use Meritoo\Common\Exception\Regex\InvalidColorHexValueException;
use Meritoo\Common\Test\Base\BaseTestCase;

/**
 * Test case of an exception used while given hexadecimal value of color is invalid
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 *
 * @internal
 * @covers    \Meritoo\Common\Exception\Regex\InvalidColorHexValueException
 */
class InvalidColorHexValueExceptionTest extends BaseTestCase
{
    /**
     * Provides invalid hexadecimal value of color and expected exception's message
     *
     * @return Generator
     */
    public function provideColor()
    {
        $template = 'Hexadecimal value of color \'%s\' is invalid. Is there everything ok?';

        yield [
            '',
            sprintf($template, ''),
        ];

        yield [
            'aa-bb-cc',
            sprintf($template, 'aa-bb-cc'),
        ];
    }

    /**
     * @param string $color           Invalid hexadecimal value of color
     * @param string $expectedMessage Expected exception's message
     *
     * @dataProvider provideColor
     */
    public function testConstructorMessage($color, $expectedMessage)
    {
        $exception = InvalidColorHexValueException::create($color);
        static::assertSame($expectedMessage, $exception->getMessage());
    }

    public function testConstructorVisibilityAndArguments()
    {
        static::assertConstructorVisibilityAndArguments(
            InvalidColorHexValueException::class,
            OopVisibility::Public,
            3,
        );
    }
}
