<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Test\Common\Utilities;

use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Utilities\GeneratorUtility;

/**
 * Test case of the useful methods for the Generator class
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class GeneratorUtilityTest extends BaseTestCase
{
    public function testConstructor()
    {
        static::assertHasNoConstructor(GeneratorUtility::class);
    }

    public function testGetGeneratorElements()
    {
        /*
         * Generator that provides boolean value
         */
        $elements = [
            [false],
            [true],
        ];

        $generator = $this->provideBooleanValue();
        self::assertEquals($elements, GeneratorUtility::getGeneratorElements($generator));

        $elements = [
            [''],
            ['   '],
            [null],
            [0],
            [false],
            [[]],
        ];

        /*
         * Generator that provides an empty value
         */
        $generator = $this->provideEmptyValue();
        self::assertEquals($elements, GeneratorUtility::getGeneratorElements($generator));

        /*
         * Generator that provides instance of DateTime class
         */
        $generator = $this->provideDateTimeInstance();
        self::assertCount(4, GeneratorUtility::getGeneratorElements($generator));
    }
}
