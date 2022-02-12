<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Test\Common\Type;

use Generator;
use Meritoo\Common\Test\Base\BaseTypeTestCase;
use Meritoo\Common\Type\Base\BaseType;
use Meritoo\Common\Type\OopVisibilityType;

/**
 * Test case of the visibility of a property, a method or (as of PHP 7.1.0) a constant
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 *
 * @internal
 * @covers    \Meritoo\Common\Type\OopVisibilityType
 */
class OopVisibilityTypeTest extends BaseTypeTestCase
{
    /**
     * {@inheritdoc}
     */
    public function provideTypeToVerify(): Generator
    {
        yield [
            OopVisibilityType::isCorrectType(''),
            false,
        ];

        yield [
            OopVisibilityType::isCorrectType(null),
            false,
        ];

        yield [
            OopVisibilityType::isCorrectType('-1'),
            false,
        ];

        yield [
            OopVisibilityType::isCorrectType('1'),
            true,
        ];

        yield [
            OopVisibilityType::isCorrectType('2'),
            true,
        ];

        yield [
            OopVisibilityType::isCorrectType('3'),
            true,
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getAllExpectedTypes(): array
    {
        return [
            'IS_PRIVATE' => 3,
            'IS_PROTECTED' => 2,
            'IS_PUBLIC' => 1,
        ];
    }

    /**
     *{@inheritdoc}
     */
    protected function getTestedTypeInstance(): BaseType
    {
        return new OopVisibilityType();
    }
}
