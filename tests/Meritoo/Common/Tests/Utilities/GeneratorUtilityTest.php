<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Tests\Utilities;

use Meritoo\Common\Utilities\GeneratorUtility;
use Meritoo\Common\Utilities\TestCase;

/**
 * Tests of the useful methods for the Generator class
 *
 * @author    Krzysztof Niziol <krzysztof.niziol@meritoo.pl>
 * @copyright Meritoo.pl
 */
class GeneratorUtilityTest extends TestCase
{
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
