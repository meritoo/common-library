<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Traits\Test\Base;

use Generator;
use Meritoo\Common\Type\Base\BaseType;

/**
 * Trait for the base test case for the type of something
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
trait BaseTypeTestCaseTrait
{
    /**
     * Verifies availability of all types
     */
    public function testAvailabilityOfAllTypes(): void
    {
        $available = $this->getTestedTypeInstance()->getAll();
        $all = $this->getAllExpectedTypes();

        static::assertEquals($all, $available);
    }

    /**
     * Verifies whether given type is correct or not
     *
     * @param bool $isCorrect Information if processed type is correct
     * @param bool $expected  Expected information if processed type is correct
     *
     * @dataProvider provideTypeToVerify
     */
    public function testIfGivenTypeIsCorrect(bool $isCorrect, bool $expected): void
    {
        static::assertEquals($expected, $isCorrect);
    }

    /**
     * Provides type to verify and information if it's correct
     *
     * @return Generator
     */
    abstract public function provideTypeToVerify(): Generator;

    /**
     * Returns instance of the tested type
     *
     * @return BaseType
     */
    abstract protected function getTestedTypeInstance(): BaseType;

    /**
     * Returns all expected types of the tested type
     *
     * @return array
     */
    abstract protected function getAllExpectedTypes(): array;
}
