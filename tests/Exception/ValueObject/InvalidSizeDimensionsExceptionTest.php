<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Test\Common\Exception\ValueObject;

use Generator;
use Meritoo\Common\Exception\ValueObject\InvalidSizeDimensionsException;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Traits\Test\Base\BaseTestCaseTrait;
use Meritoo\Common\Type\Base\BaseType;
use Meritoo\Common\Type\OopVisibilityType;
use Meritoo\Common\Utilities\Reflection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(InvalidSizeDimensionsException::class)]
#[UsesClass(BaseTestCaseTrait::class)]
#[UsesClass(BaseType::class)]
#[UsesClass(Reflection::class)]
class InvalidSizeDimensionsExceptionTest extends BaseTestCase
{
    public static function provideWidthAndHeight(): Generator
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
}
