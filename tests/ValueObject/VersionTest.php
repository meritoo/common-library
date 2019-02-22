<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Test\ValueObject;

use Generator;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Type\OopVisibilityType;
use Meritoo\Common\Utilities\Reflection;
use Meritoo\Common\ValueObject\Version;

/**
 * Test case for the version of software
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class VersionTest extends BaseTestCase
{
    public function testConstructor()
    {
        static::assertConstructorVisibilityAndArguments(Version::class, OopVisibilityType::IS_PUBLIC, 3, 3);
    }

    public function testNewInstance()
    {
        $version = new Version(1, 0, 2);

        static::assertInstanceOf(Version::class, $version);
        static::assertSame(1, Reflection::getPropertyValue($version, 'majorPart'));
        static::assertSame(0, Reflection::getPropertyValue($version, 'minorPart'));
        static::assertSame(2, Reflection::getPropertyValue($version, 'patchPart'));
    }

    /**
     * @param Version $version  The version
     * @param string  $expected Expected string
     *
     * @dataProvider provideConvertedToString
     */
    public function testToString(Version $version, $expected)
    {
        static::assertSame($expected, (string)$version);
    }

    /**
     * @param string  $version  The version
     * @param Version $expected (optional) Expected version
     *
     * @dataProvider provideAsString
     */
    public function testFromString($version, Version $expected = null)
    {
        static::assertEquals($expected, Version::fromString($version));
    }

    /**
     * @param array   $version  The version
     * @param Version $expected (optional) Expected version
     *
     * @dataProvider  provideAsArray
     */
    public function testFromArray(array $version, Version $expected = null)
    {
        static::assertEquals($expected, Version::fromArray($version));
    }

    /**
     * Provide instance of version and expected version converted to string
     *
     * @return Generator
     */
    public function provideConvertedToString()
    {
        yield[
            new Version(0, 0, 0),
            '0.0.0',
        ];

        yield[
            new Version(1, 0, 2),
            '1.0.2',
        ];

        yield[
            new Version(10, 5, 41),
            '10.5.41',
        ];
    }

    /**
     * Provide version as string and expected instance of version
     *
     * @return Generator
     */
    public function provideAsString()
    {
        yield[
            '',
            null,
        ];

        yield[
            '1.0',
            null,
        ];

        yield[
            '10',
            null,
        ];

        yield[
            '0.0.0',
            new Version(0, 0, 0),
        ];

        yield[
            '1.0.2',
            new Version(1, 0, 2),
        ];

        yield[
            '10.5.41',
            new Version(10, 5, 41),
        ];
    }

    /**
     * Provide version as array and expected instance of version
     *
     * @return Generator
     */
    public function provideAsArray()
    {
        yield[
            [],
            null,
        ];

        yield[
            [
                1,
                0,
            ],
            null,
        ];

        yield[
            [
                10,
            ],
            null,
        ];

        yield[
            [
                0,
                0,
                0,
            ],
            new Version(0, 0, 0),
        ];

        yield[
            [
                1,
                0,
                2,
            ],
            new Version(1, 0, 2),
        ];

        yield[
            [
                10,
                5,
                41,
            ],
            new Version(10, 5, 41),
        ];
    }
}
