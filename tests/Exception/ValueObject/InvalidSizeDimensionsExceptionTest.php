<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Test\Common\Exception\ValueObject;

use Meritoo\Common\Enums\OopVisibility;
use Meritoo\Common\Exception\ValueObject\InvalidSizeDimensionsException;
use Meritoo\Common\Test\Base\BaseTestCase;

/**
 * Test case of an exception used while dimensions of size, passed to the instance of Size class, are invalid
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 *
 * @internal
 * @covers    \Meritoo\Common\Exception\ValueObject\InvalidSizeDimensionsException
 */
class InvalidSizeDimensionsExceptionTest extends BaseTestCase
{
    public function provideWidthAndHeight()
    {
        $template = 'Dimensions of size should be positive, but they are not: %d, %d. Is there everything ok?';

        yield [
            0,
            0,
            sprintf($template, 0, 0),
        ];

        yield [
            -1,
            -1,
            sprintf($template, -1, -1),
        ];

        yield [
            200,
            100,
            sprintf($template, 200, 100),
        ];
    }

    public function testConstructorVisibilityAndArguments()
    {
        static::assertConstructorVisibilityAndArguments(
            InvalidSizeDimensionsException::class,
            OopVisibility::Public,
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
}
