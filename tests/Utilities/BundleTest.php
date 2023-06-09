<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Test\Common\Utilities;

use Generator;
use Meritoo\Common\Exception\Bundle\IncorrectBundleNameException;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Traits\Test\Base\BaseTestCaseTrait;
use Meritoo\Common\Utilities\Bundle;
use Meritoo\Common\Utilities\Regex;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(Bundle::class)]
#[UsesClass(Regex::class)]
#[UsesClass(BaseTestCaseTrait::class)]
#[UsesClass(IncorrectBundleNameException::class)]
class BundleTest extends BaseTestCase
{
    /**
     * Provides empty path of the view / template and/or name of bundle
     *
     * @return Generator
     */
    public static function provideEmptyViewPathAndBundle(): Generator
    {
        yield [
            '',
            '',
        ];

        yield [
            'test',
            '',
        ];

        yield [
            '',
            'test',
        ];
    }

    /**
     * Provides full and short name of bundle
     *
     * @return Generator
     */
    public static function provideFullAndShortBundleName(): Generator
    {
        yield [
            'MyExtraBundle',
            'MyExtra',
        ];

        yield [
            'MySuperExtraGorgeousBundle',
            'MySuperExtraGorgeous',
        ];
    }

    /**
     * Provides incorrect name of bundle
     *
     * @return Generator
     */
    public static function provideIncorrectBundleName(): Generator
    {
        yield [
            'myExtra',
        ];

        yield [
            'MyExtra',
        ];

        yield [
            'MySuperExtraGorgeous',
        ];
    }

    /**
     * Provides path of the view / template and name of bundle
     *
     * @return Generator
     */
    public static function provideViewPathAndBundle(): Generator
    {
        yield [
            'User',
            'MyExtraBundle',
            '@MyExtra/User.html.twig',
        ];

        yield [
            'User:Active',
            'MyExtraBundle',
            '@MyExtra/User/Active.html.twig',
        ];

        yield [
            'User:Active',
            'MySuperExtraGorgeousBundle',
            '@MySuperExtraGorgeous/User/Active.html.twig',
        ];
    }

    /**
     * Provides path of the view / template, name of bundle and extension of the view / template
     *
     * @return Generator
     */
    public static function provideViewPathAndBundleAndExtension(): Generator
    {
        yield [
            'User:Active',
            'MyExtraBundle',
            '',
            null,
        ];

        yield [
            'User:Active',
            'MyExtraBundle',
            'js.twig',
            '@MyExtra/User/Active.js.twig',
        ];
    }

    /**
     * Provides path of the view / template and incorrect name of bundle
     *
     * @return Generator
     */
    public static function provideViewPathAndIncorrectBundleName(): Generator
    {
        yield [
            'User:Active',
            'myExtra',
        ];

        yield [
            'User:Active',
            'MyExtra',
        ];

        yield [
            'User:Active',
            'MySuperExtraGorgeous',
        ];
    }

    public function testConstructor()
    {
        static::assertHasNoConstructor(Bundle::class);
    }

    /**
     * @param string $viewPath   Path of the view / template, e.g. "MyDirectory/my-template"
     * @param string $bundleName Full name of the bundle, e.g. "MyExtraBundle"
     * @param string $extension  (optional) Extension of the view / template
     * @param string $expected   Expected path to view / template
     *
     * @throws IncorrectBundleNameException
     * @dataProvider provideViewPathAndBundleAndExtension
     */
    public function testGetBundleViewPathUsingCustomExtension($viewPath, $bundleName, $extension, $expected)
    {
        self::assertEquals($expected, Bundle::getBundleViewPath($viewPath, $bundleName, $extension));
    }

    /**
     * @param string $viewPath   Path of the view / template, e.g. "MyDirectory/my-template"
     * @param string $bundleName Full name of the bundle, e.g. "MyExtraBundle"
     * @param string $expected   Expected path to view / template
     *
     * @throws IncorrectBundleNameException
     * @dataProvider provideViewPathAndBundle
     */
    public function testGetBundleViewPathUsingDefaultExtension($viewPath, $bundleName, $expected)
    {
        self::assertEquals($expected, Bundle::getBundleViewPath($viewPath, $bundleName));
    }

    /**
     * @param string $viewPath   Path of the view / template, e.g. "MyDirectory/my-template"
     * @param string $bundleName Full name of the bundle, e.g. "MyExtraBundle"
     *
     * @throws IncorrectBundleNameException
     * @dataProvider provideEmptyViewPathAndBundle
     */
    public function testGetBundleViewPathUsingEmptyPathAndBundle($viewPath, $bundleName)
    {
        self::assertNull(Bundle::getBundleViewPath($viewPath, $bundleName));
    }

    /**
     * @param string $viewPath   Path of the view / template, e.g. "MyDirectory/my-template"
     * @param string $bundleName Full name of the bundle, e.g. "MyExtraBundle"
     *
     * @dataProvider provideViewPathAndIncorrectBundleName
     */
    public function testGetBundleViewPathUsingIncorrectBundleName($viewPath, $bundleName)
    {
        $template = 'Name of bundle \'%s\' is incorrect. It should start with big letter and end with "Bundle". Is'
            .' there everything ok?';

        $this->expectException(IncorrectBundleNameException::class);
        $this->expectExceptionMessage(sprintf($template, $bundleName));

        Bundle::getBundleViewPath($viewPath, $bundleName);
    }

    /**
     * @param string $fullBundleName  Full name of the bundle, e.g. "MyExtraBundle"
     * @param string $shortBundleName Short name of bundle (without "Bundle")
     *
     * @throws IncorrectBundleNameException
     * @dataProvider provideFullAndShortBundleName
     */
    public function testGetShortBundleName($fullBundleName, $shortBundleName)
    {
        self::assertEquals($shortBundleName, Bundle::getShortBundleName($fullBundleName));
    }

    public function testGetShortBundleNameUsingEmptyValue(): void
    {
        $this->expectException(IncorrectBundleNameException::class);
        Bundle::getShortBundleName('');
    }

    /**
     * @param string $bundleName Full name of the bundle, e.g. "MyExtraBundle"
     *
     * @throws IncorrectBundleNameException
     * @dataProvider provideIncorrectBundleName
     */
    public function testGetShortBundleNameUsingIncorrectBundleName($bundleName)
    {
        $this->expectException(IncorrectBundleNameException::class);
        Bundle::getShortBundleName($bundleName);
    }
}
