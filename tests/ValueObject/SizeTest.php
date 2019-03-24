<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Test\Common\ValueObject;

use Meritoo\Common\Exception\ValueObject\InvalidSizeDimensionsException;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Type\OopVisibilityType;
use Meritoo\Common\ValueObject\Size;

/**
 * Test of the Size class
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class SizeTest extends BaseTestCase
{
    public function testConstructor()
    {
        static::assertConstructorVisibilityAndArguments(
            Size::class,
            OopVisibilityType::IS_PRIVATE,
            3
        );
    }

    /**
     * @param string    $description Description of test
     * @param Size|null $size        Size to convert
     * @param string    $expected    Expected result
     *
     * @dataProvider provideSizeForConvertingToString
     */
    public function test__toString($description, $size, $expected)
    {
        static::assertEquals($expected, (string)$size, $description);
    }

    public function testSetSeparator()
    {
        $size = Size::fromArray([
            200,
            100,
        ]);

        static::assertInstanceOf(Size::class, $size->setSeparator(' / '));
        static::assertSame('200 / 100', $size->toString());
    }

    /**
     * @param string     $description Description of test
     * @param Size       $size        Size to get width
     * @param bool       $withUnit    If is set to true, width is returned with unit ("px"). Otherwise - without.
     * @param string|int $expected    Expected width
     *
     * @dataProvider provideSizeToGetWidth
     */
    public function testGetWidth($description, Size $size, $withUnit, $expected)
    {
        static::assertSame($expected, $size->getWidth($withUnit), $description);
    }

    /**
     * @param string     $description Description of test
     * @param Size       $size        Size to set width
     * @param int|string $width       The width
     * @param string|int $expected    Expected width
     *
     * @dataProvider provideSizeToSetWidth
     */
    public function testSetWidth($description, Size $size, $width, $expected)
    {
        $result = $size->setWidth($width);

        static::assertInstanceOf(Size::class, $result, $description);
        static::assertSame($expected, $size->getWidth(), $description);
    }

    /**
     * @param string     $description Description of test
     * @param Size       $size        Size to get width
     * @param bool       $withUnit    If is set to true, width is returned with unit ("px"). Otherwise - without.
     * @param string|int $expected    Expected width
     *
     * @dataProvider provideSizeToGetHeight
     */
    public function testGetHeight($description, Size $size, $withUnit, $expected)
    {
        static::assertSame($expected, $size->getHeight($withUnit), $description);
    }

    /**
     * @param string     $description Description of test
     * @param Size       $size        Size to set height
     * @param int|string $height      The height
     * @param string|int $expected    Expected height
     *
     * @dataProvider provideSizeToSetHeight
     */
    public function testSetHeight($description, Size $size, $height, $expected)
    {
        $result = $size->setHeight($height);

        static::assertInstanceOf(Size::class, $result, $description);
        static::assertSame($expected, $size->getHeight(), $description);
    }

    /**
     * @param string $description    Description of test
     * @param Size   $size           Size to convert
     * @param bool   $withUnit       If is set to true, width and height are returned with unit ("px"). Otherwise -
     *                               without.
     * @param string $expected       Expected result
     *
     * @dataProvider provideSizeForToString
     */
    public function testToString($description, Size $size, $withUnit, $expected)
    {
        static::assertSame($expected, $size->toString($withUnit), $description);
    }

    /**
     * @param array $size Invalid size
     * @dataProvider provideInvalidSizeAsArray
     */
    public function testFromArrayUsingInvalidSizeAsArray(array $size)
    {
        $this->setExpectedException(InvalidSizeDimensionsException::class);
        Size::fromArray($size);
    }

    /**
     * @param string $description Description of test
     * @param array  $size        The size represented as array
     * @param string $unit        Unit used when width or height should be returned with unit
     * @param Size   $expected    Expected result
     *
     * @dataProvider provideSizeForFromArray
     */
    public function testFromArray($description, array $size, $unit, $expected)
    {
        static::assertEquals($expected, Size::fromArray($size, $unit), $description);
    }

    /**
     * @param string $description Description of test
     * @param Size   $size        Size to convert
     * @param bool   $withUnit    If is set to true, width and height are returned with unit ("px"). Otherwise -
     *                            without.
     * @param array  $expected    Expected result
     *
     * @dataProvider provideSizeForToArray
     */
    public function testToArray($description, Size $size, $withUnit, array $expected)
    {
        static::assertSame($expected, $size->toArray($withUnit), $description);
    }

    /**
     * @param string    $description Description of test
     * @param string    $size        The size represented as string (width and height separated by "x")
     * @param string    $unit        Unit used when width or height should be returned with unit
     * @param string    $separator   Separator used to split width and height
     * @param Size|null $expected    Expected result
     *
     * @dataProvider provideSizeForFromString
     */
    public function testFromString($description, $size, $unit, $separator, $expected)
    {
        static::assertEquals($expected, Size::fromString($size, $unit, $separator), $description);
    }

    /**
     * @param mixed $emptySize Empty value, e.g. ""
     * @dataProvider provideEmptyValue
     */
    public function testFromStringUsingEmptyValue($emptySize)
    {
        static::assertNull(Size::fromString($emptySize));
    }

    public function provideSizeForConvertingToString()
    {
        yield[
            'Created using an empty array',
            Size::fromArray([]),
            '',
        ];

        yield[
            'Created using an empty string',
            Size::fromString(''),
            '',
        ];

        yield[
            'Created using an array with integers',
            Size::fromArray([
                200,
                100,
            ]),
            '200 x 100',
        ];

        yield[
            'Created using an array with strings',
            Size::fromArray([
                '200',
                '100',
            ]),
            '200 x 100',
        ];

        yield[
            'Created using simple string',
            Size::fromString('200x100', '', 'x'),
            '200x100',
        ];

        yield[
            'Created using string with too much spaces everywhere',
            Size::fromString('  200 x   100 '),
            '200 x 100',
        ];
    }

    /**
     * Provides invalid size (as an array)
     *
     * @return \Generator
     */
    public function provideInvalidSizeAsArray()
    {
        yield[
            [
                10,
                -1,
            ],
        ];

        yield[
            [
                -1,
                10,
            ],
        ];

        yield[
            [
                -1,
                -1,
            ],
        ];
    }

    public function provideSizeToGetWidth()
    {
        yield[
            'Created using an array with integers',
            Size::fromArray([
                200,
                100,
            ]),
            false,
            200,
        ];

        yield[
            'Created using an array with integers (with unit)',
            Size::fromArray([
                200,
                100,
            ]),
            true,
            '200 px',
        ];

        yield[
            'Created using an array with strings',
            Size::fromArray([
                '200',
                '100',
            ]),
            false,
            200,
        ];

        yield[
            'Created using an array with strings (with unit)',
            Size::fromArray([
                '200',
                '100',
            ]),
            true,
            '200 px',
        ];

        yield[
            'Created using simple string',
            Size::fromString('200 x 100'),
            false,
            200,
        ];

        yield[
            'Created using simple string (with unit)',
            Size::fromString('200 x 100'),
            true,
            '200 px',
        ];

        yield[
            'Created using simple string and custom separator',
            Size::fromString('200 X 100', '', ' X '),
            false,
            200,
        ];

        yield[
            'Created using simple string, custom separator and custom unit (with unit)',
            Size::fromString('200 : 100', 'mm', ' : '),
            true,
            '200 mm',
        ];

        yield[
            'Created using string with too much spaces everywhere',
            Size::fromString('  200 x   100 '),
            false,
            200,
        ];

        yield[
            'Created using string with too much spaces everywhere (with unit)',
            Size::fromString('  200 x   100 '),
            true,
            '200 px',
        ];
    }

    public function provideSizeToGetHeight()
    {
        yield[
            'Created using an array with integers',
            Size::fromArray([
                200,
                100,
            ]),
            false,
            100,
        ];

        yield[
            'Created using an array with integers (with unit)',
            Size::fromArray([
                200,
                100,
            ]),
            true,
            '100 px',
        ];

        yield[
            'Created using an array with strings',
            Size::fromArray([
                '200',
                '100',
            ]),
            false,
            100,
        ];

        yield[
            'Created using an array with strings (with unit)',
            Size::fromArray([
                '200',
                '100',
            ]),
            true,
            '100 px',
        ];

        yield[
            'Created using simple string',
            Size::fromString('200 x 100'),
            false,
            100,
        ];

        yield[
            'Created using simple string (with unit)',
            Size::fromString('200 x 100'),
            true,
            '100 px',
        ];

        yield[
            'Created using simple string and custom separator',
            Size::fromString('200 X 100', '', ' X '),
            false,
            100,
        ];

        yield[
            'Created using simple string, custom separator and custom unit (with unit)',
            Size::fromString('200 : 100', 'mm', ' : '),
            true,
            '100 mm',
        ];

        yield[
            'Created using string with too much spaces everywhere',
            Size::fromString('  200 x   100 '),
            false,
            100,
        ];

        yield[
            'Created using string with too much spaces everywhere (with unit)',
            Size::fromString('  200 x   100 '),
            true,
            '100 px',
        ];
    }

    public function provideSizeToSetWidth()
    {
        yield[
            'Null as width',
            Size::fromArray([
                200,
                100,
            ]),
            null,
            0,
        ];

        yield[
            'An empty string',
            Size::fromArray([
                200,
                100,
            ]),
            '',
            0,
        ];

        yield[
            'Negative value',
            Size::fromArray([
                200,
                100,
            ]),
            -1,
            -1,
        ];

        yield[
            'Negative value as string',
            Size::fromArray([
                200,
                100,
            ]),
            '-1',
            -1,
        ];

        yield[
            '0 as width',
            Size::fromArray([
                200,
                100,
            ]),
            0,
            0,
        ];

        yield[
            'Positive value',
            Size::fromArray([
                200,
                100,
            ]),
            300,
            300,
        ];

        yield[
            'Positive value as string',
            Size::fromArray([
                200,
                100,
            ]),
            '300',
            300,
        ];
    }

    public function provideSizeToSetHeight()
    {
        yield[
            'Null as height',
            Size::fromArray([
                200,
                100,
            ]),
            null,
            0,
        ];

        yield[
            'An empty string',
            Size::fromArray([
                200,
                100,
            ]),
            '',
            0,
        ];

        yield[
            'Negative value',
            Size::fromArray([
                200,
                100,
            ]),
            -1,
            -1,
        ];

        yield[
            'Negative value as string',
            Size::fromArray([
                200,
                100,
            ]),
            '-1',
            -1,
        ];

        yield[
            '0 as height',
            Size::fromArray([
                200,
                100,
            ]),
            0,
            0,
        ];

        yield[
            'Positive value',
            Size::fromArray([
                200,
                100,
            ]),
            300,
            300,
        ];

        yield[
            'Positive value as string',
            Size::fromArray([
                200,
                100,
            ]),
            '300',
            300,
        ];
    }

    public function provideSizeForToString()
    {
        yield[
            'With unknown dimensions',
            Size::fromArray([
                null,
                null,
            ]),
            false,
            '0 x 0',
        ];

        yield[
            'With unknown dimensions (converting with unit)',
            Size::fromArray([
                null,
                null,
            ]),
            true,
            '0 px x 0 px',
        ];

        yield[
            'Created using an array with integers',
            Size::fromArray([
                200,
                100,
            ]),
            false,
            '200 x 100',
        ];

        yield[
            'Created using an array with integers (converting with unit)',
            Size::fromArray([
                200,
                100,
            ]),
            true,
            '200 px x 100 px',
        ];

        yield[
            'Created using an array with strings',
            Size::fromArray([
                '200',
                '100',
            ]),
            false,
            '200 x 100',
        ];

        yield[
            'Created using an array with strings (converting with unit)',
            Size::fromArray([
                '200',
                '100',
            ]),
            true,
            '200 px x 100 px',
        ];

        yield[
            'Created using simple string',
            Size::fromString('200 x 100'),
            false,
            '200 x 100',
        ];

        yield[
            'Created using simple string',
            Size::fromString('200 x 100'),
            false,
            '200 x 100',
        ];

        yield[
            'Created using simple string and custom separator',
            Size::fromString('200 X 100', '', ' X '),
            false,
            '200 X 100',
        ];

        yield[
            'Created using simple string, custom separator and custom unit (with unit)',
            Size::fromString('200 : 100', 'mm', ' : '),
            true,
            '200 mm : 100 mm',
        ];

        yield[
            'Created using simple string (converting with unit)',
            Size::fromString('200 x 100'),
            true,
            '200 px x 100 px',
        ];

        yield[
            'Created using string with too much spaces everywhere',
            Size::fromString('  200 x   100 '),
            false,
            '200 x 100',
        ];

        yield[
            'Created using string with too much spaces everywhere (converting with unit)',
            Size::fromString('  200 x   100 '),
            true,
            '200 px x 100 px',
        ];
    }

    public function provideSizeForToArray()
    {
        yield[
            'Created using an array with integers',
            Size::fromArray([
                200,
                100,
            ]),
            false,
            [
                200,
                100,
            ],
        ];

        yield[
            'Created using an array with integers (converting with unit)',
            Size::fromArray([
                200,
                100,
            ]),
            true,
            [
                '200 px',
                '100 px',
            ],
        ];

        yield[
            'Created using an array with strings',
            Size::fromArray([
                '200',
                '100',
            ]),
            false,
            [
                200,
                100,
            ],
        ];

        yield[
            'Created using an array with strings (converting with unit)',
            Size::fromArray([
                '200',
                '100',
            ]),
            true,
            [
                '200 px',
                '100 px',
            ],
        ];

        yield[
            'Created using simple string',
            Size::fromString('200 x 100'),
            false,
            [
                200,
                100,
            ],
        ];

        yield[
            'Created using simple string and custom separator',
            Size::fromString('200 X 100', '', ' X '),
            false,
            [
                200,
                100,
            ],
        ];

        yield[
            'Created using simple string, custom separator and custom unit (with unit)',
            Size::fromString('200 : 100', 'mm', ' : '),
            true,
            [
                '200 mm',
                '100 mm',
            ],
        ];

        yield[
            'Created using simple string (converting with unit)',
            Size::fromString('200 x 100'),
            true,
            [
                '200 px',
                '100 px',
            ],
        ];

        yield[
            'Created using string with too much spaces everywhere',
            Size::fromString('  200 x   100 '),
            false,
            [
                200,
                100,
            ],
        ];

        yield[
            'Created using string with too much spaces everywhere (converting with unit)',
            Size::fromString('  200 x   100 '),
            true,
            [
                '200 px',
                '100 px',
            ],
        ];
    }

    public function provideSizeForFromString()
    {
        yield[
            'One number only',
            200,
            '',
            ' x ',
            null,
        ];

        yield[
            'One number only as string',
            '200',
            '',
            ' x ',
            null,
        ];

        yield[
            'The " " as invalid separator',
            '200 100',
            '',
            ' x ',
            null,
        ];

        yield[
            'The "|" as separator (invalid separator)',
            '200 | 100',
            '',
            ' x ',
            null,
        ];

        yield[
            'The "|" as invalid separator and no spaces around separator',
            '200|100',
            '',
            ' x ',
            null,
        ];

        yield[
            'The "X" as invalid separator',
            '200 X 100',
            '',
            ' x ',
            null,
        ];

        yield[
            'Simple, valid size',
            '200 x 100',
            'px',
            ' x ',
            Size::fromArray([
                200,
                100,
            ]),
        ];

        yield[
            'Simple, valid size using custom separator',
            '200 X 100',
            'px',
            ' X ',
            Size::fromArray([
                200,
                100,
            ])->setSeparator(' X '),
        ];

        yield[
            'Too much spaces at the right of separator',
            '200 x   100',
            'px',
            ' x ',
            Size::fromArray([
                200,
                100,
            ]),
        ];

        yield[
            'Too much spaces at the left of separator',
            '200   x 100',
            'px',
            ' x ',
            Size::fromArray([
                200,
                100,
            ]),
        ];

        yield[
            'Too much spaces around separator',
            '200   x   100',
            'px',
            ' x ',
            Size::fromArray([
                200,
                100,
            ]),
        ];

        yield[
            'Too much spaces before width (1st)',
            '   200 x 100',
            'px',
            ' x ',
            Size::fromArray([
                200,
                100,
            ]),
        ];

        yield[
            'Too much spaces before width (2nd) and custom separator',
            '   200 X 100',
            'px',
            ' X ',
            Size::fromArray([
                200,
                100,
            ])->setSeparator(' X '),
        ];

        yield[
            'Too much spaces after height (1st)',
            '200 x 100   ',
            'px',
            ' x ',
            Size::fromArray([
                200,
                100,
            ]),
        ];

        yield[
            'Too much spaces after height (2nd) and custom separator',
            '200 X 100   ',
            'px',
            ' X ',
            Size::fromArray([
                200,
                100,
            ])->setSeparator(' X '),
        ];

        yield[
            'Too much spaces before width and after height (1st)',
            '   200 x 100   ',
            'km',
            ' x ',
            Size::fromArray(
                [
                    200,
                    100,
                ],
                'km'
            ),
        ];

        yield[
            'Too much spaces before width and after height (2nd) and custom separator',
            '   200 X 100   ',
            'px',
            ' X ',
            Size::fromArray([
                200,
                100,
            ])->setSeparator(' X '),
        ];

        yield[
            'Too much spaces everywhere (1st)',
            '   200   x 100   ',
            'px',
            ' x ',
            Size::fromArray([
                200,
                100,
            ]),
        ];

        yield[
            'Too much spaces everywhere (2nd) and custom separator',
            '   200   X 100   ',
            'px',
            ' X ',
            Size::fromArray([
                200,
                100,
            ])->setSeparator(' X '),
        ];

        yield[
            'Too much spaces everywhere (3rd)',
            '   200 x   100   ',
            'px',
            ' x ',
            Size::fromArray([
                200,
                100,
            ]),
        ];

        yield[
            'Too much spaces everywhere (4th) and custom separator',
            '   200 :   100   ',
            'px',
            ' : ',
            Size::fromArray([
                200,
                100,
            ])->setSeparator(' : '),
        ];

        yield[
            'Too much spaces everywhere (5th)',
            '   200   x   100   ',
            'mm',
            ' x ',
            Size::fromArray(
                [
                    200,
                    100,
                ],
                'mm'
            ),
        ];
    }

    public function provideSizeForFromArray()
    {
        yield[
            'An empty array',
            [],
            '',
            null,
        ];

        yield[
            'One number only',
            [
                200,
            ],
            '',
            null,
        ];

        yield[
            'One number only as string',
            [
                '200',
            ],
            '',
            null,
        ];

        yield[
            '0 as dimensions',
            [
                0,
                0,
            ],
            'px',
            Size::fromString('0 x 0'),
        ];

        yield[
            'Simple, valid size',
            [
                200,
                100,
            ],
            'px',
            Size::fromString('200 x 100'),
        ];

        yield[
            'Simple, valid size (using strings)',
            [
                '200',
                '100',
            ],
            'mm',
            Size::fromString('200 x 100', 'mm'),
        ];
    }
}
