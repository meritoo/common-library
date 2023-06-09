<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Test\Common\Utilities;

use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Traits\Test\Base\BaseTestCaseTrait;
use Meritoo\Common\Utilities\GeneratorUtility;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(GeneratorUtility::class)]
#[UsesClass(BaseTestCaseTrait::class)]
class GeneratorUtilityTest extends BaseTestCase
{
    public function testConstructor()
    {
        static::assertHasNoConstructor(GeneratorUtility::class);
    }

    public function testGetGeneratorElements()
    {
        // Generator that provides boolean value
        $elements = [
            [false],
            [true],
        ];

        $generator = $this->provideBooleanValue();
        self::assertEquals($elements, GeneratorUtility::getGeneratorElements($generator));

        $elements = [
            [''],
            ['   '],
            ['0'],
            [0],
            [false],
            [null],
            [[]],
        ];

        // Generator that provides an empty value
        $generator = $this->provideEmptyValue();
        self::assertEquals($elements, GeneratorUtility::getGeneratorElements($generator));

        // Generator that provides instance of DateTime class
        $generator = $this->provideDateTimeInstance();
        self::assertCount(4, GeneratorUtility::getGeneratorElements($generator));
    }
}
