<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Test\Utilities;

use Meritoo\Common\Utilities\Bundle;
use PHPUnit_Framework_TestCase;

/**
 * Tests of the useful methods for bundle
 *
 * @author    Krzysztof Niziol <krzysztof.niziol@meritoo.pl>
 * @copyright Meritoo.pl
 */
class BundleTest extends PHPUnit_Framework_TestCase
{
    public function testGetBundleViewPathEmptyPathAndBundle()
    {
        self::assertNull(Bundle::getBundleViewPath('', ''));
        self::assertNull(Bundle::getBundleViewPath('test', ''));
        self::assertNull(Bundle::getBundleViewPath('', 'test'));
    }

    public function testGetBundleViewPathWithDefaultExtension()
    {
        self::assertEquals('Lorem:Ipsum.html.twig', Bundle::getBundleViewPath('Ipsum', 'Lorem'));
        self::assertEquals('LobortisTincidunt:FusceElementum.html.twig', Bundle::getBundleViewPath('FusceElementum', 'LobortisTincidunt'));
    }

    public function testGetBundleViewPathWithCustomExtension()
    {
        self::assertNull(Bundle::getBundleViewPath('Ipsum', 'Lorem', ''));
        self::assertEquals('Lorem:Ipsum.js.twig', Bundle::getBundleViewPath('Ipsum', 'Lorem', 'js.twig'));
    }
}
