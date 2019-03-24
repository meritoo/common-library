<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Test\Common\Exception\ValueObject;

use Meritoo\Common\Exception\ValueObject\InvalidSizeDimensionsException;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Type\OopVisibilityType;

/**
 * Test case of an exception used while dimensions of size, passed to the instance of Size class, are invalid
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class InvalidSizeDimensionsExceptionTest extends BaseTestCase
{
    public function testConstructorVisibilityAndArguments()
    {
        static::assertConstructorVisibilityAndArguments(
            InvalidSizeDimensionsException::class,
            OopVisibilityType::IS_PUBLIC,
            3
        );
    }

    /**
     * @param int    $width           The width
     * @param int    $height          The height
     * @param string $expectedMessage Expected exception's message
     *
     * @dataProvider provideWidthAndHeight
     */
    public function testCreate($width, $height, $expectedMessage)
    {
        $exception = InvalidSizeDimensionsException::create($width, $height);
        static::assertSame($expectedMessage, $exception->getMessage());
    }

    public function provideWidthAndHeight()
    {
        $template = 'Dimensions of size should be positive, but they are not: %d, %d. Is there everything ok?';

        yield[
            0,
            0,
            sprintf($template, 0, 0),
        ];

        yield[
            -1,
            -1,
            sprintf($template, -1, -1),
        ];

        yield[
            200,
            100,
            sprintf($template, 200, 100),
        ];
    }
}
