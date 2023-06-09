<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Test\Common\Type;

use Generator;
use Meritoo\Common\Test\Base\BaseTypeTestCase;
use Meritoo\Common\Traits\Test\Base\BaseTypeTestCaseTrait;
use Meritoo\Common\Type\Base\BaseType;
use Meritoo\Common\Type\OopVisibilityType;
use Meritoo\Common\Utilities\Reflection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(OopVisibilityType::class)]
#[UsesClass(BaseTypeTestCaseTrait::class)]
#[UsesClass(BaseType::class)]
#[UsesClass(Reflection::class)]
class OopVisibilityTypeTest extends BaseTypeTestCase
{
    /**
     * {@inheritdoc}
     */
    public static function provideTypeToVerify(): Generator
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
