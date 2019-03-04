<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Test\Common\Exception\Regex;

use Generator;
use Meritoo\Common\Exception\Regex\IncorrectColorHexLengthException;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Type\OopVisibilityType;

/**
 * Test case of an exception used while length of given hexadecimal value of color is incorrect
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class IncorrectColorHexLengthExceptionTest extends BaseTestCase
{
    public function testConstructorVisibilityAndArguments()
    {
        static::assertConstructorVisibilityAndArguments(IncorrectColorHexLengthException::class, OopVisibilityType::IS_PUBLIC, 3);
    }

    /**
     * @param string $color           Incorrect hexadecimal value of color
     * @param string $expectedMessage Expected exception's message
     *
     * @dataProvider provideColor
     */
    public function testConstructorMessage($color, $expectedMessage)
    {
        $exception = IncorrectColorHexLengthException::create($color);
        static::assertSame($expectedMessage, $exception->getMessage());
    }

    /**
     * Provides incorrect hexadecimal value of color and expected exception's message
     *
     * @return Generator
     */
    public function provideColor()
    {
        $template = 'Length of hexadecimal value of color \'%s\' is incorrect. It\'s %d, but it should be 3 or 6. Is there everything ok?';

        yield[
            '',
            sprintf($template, '', strlen('')),
        ];

        yield[
            'aa-bb-cc',
            sprintf($template, 'aa-bb-cc', strlen('aa-bb-cc')),
        ];
    }
}
