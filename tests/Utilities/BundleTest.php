<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Test\Utilities;

use Generator;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Utilities\Bundle;

/**
 * Test case of the useful methods for bundle
 *
 * @author    Krzysztof Niziol <krzysztof.niziol@meritoo.pl>
 * @copyright Meritoo.pl
 */
class BundleTest extends BaseTestCase
{
    public function testConstructor()
    {
        static::assertHasNoConstructor(Bundle::class);
    }

    /**
     * @param string $viewPath   Path of the view / template, e.g. "MyDirectory/my-template"
     * @param string $bundleName Full name of the bundle, e.g. "MyExtraBundle"
     *
     * @dataProvider provideEmptyViewPathAndBundle
     */
    public function testGetBundleViewPathUsingEmptyPathAndBundle($viewPath, $bundleName)
    {
        self::assertNull(Bundle::getBundleViewPath($viewPath, $bundleName));
    }

    /**
     * @param string $viewPath   Path of the view / template, e.g. "MyDirectory/my-template"
     * @param string $bundleName Full name of the bundle, e.g. "MyExtraBundle"
     * @param string $expected   Expected path to view / template
     *
     * @dataProvider provideViewPathAndBundle
     */
    public function testGetBundleViewPathUsingDefaultExtension($viewPath, $bundleName, $expected)
    {
        self::assertEquals($expected, Bundle::getBundleViewPath($viewPath, $bundleName));
    }

    /**
     * @param string $viewPath   Path of the view / template, e.g. "MyDirectory/my-template"
     * @param string $bundleName Full name of the bundle, e.g. "MyExtraBundle"
     * @param string $extension  (optional) Extension of the view / template
     * @param string $expected   Expected path to view / template
     *
     * @dataProvider provideViewPathAndBundleAndExtension
     */
    public function testGetBundleViewPathUsingCustomExtension($viewPath, $bundleName, $extension, $expected)
    {
        self::assertEquals($expected, Bundle::getBundleViewPath($viewPath, $bundleName, $extension));
    }

    /**
     * Provides empty path of the view / template and/or name of bundle
     *
     * @return Generator
     */
    public function provideEmptyViewPathAndBundle()
    {
        yield[
            '',
            '',
        ];

        yield[
            'test',
            '',
        ];

        yield[
            '',
            'test',
        ];
    }

    /**
     * Provides path of the view / template and name of bundle
     *
     * @return Generator
     */
    public function provideViewPathAndBundle()
    {
        yield[
            'Ipsum',
            'Lorem',
            'Lorem:Ipsum.html.twig',
        ];

        yield[
            'FusceElementum',
            'LobortisTincidunt',
            'LobortisTincidunt:FusceElementum.html.twig',
        ];
    }

    /**
     * Provides path of the view / template, name of bundle and extension of the view / template
     *
     * @return Generator
     */
    public function provideViewPathAndBundleAndExtension()
    {
        yield[
            'Ipsum',
            'Lorem',
            '',
            null,
        ];

        yield[
            'Ipsum',
            'Lorem',
            'js.twig',
            'Lorem:Ipsum.js.twig',
        ];
    }
}
