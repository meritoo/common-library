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
use Meritoo\Common\Type\DatePartType;
use Meritoo\Common\Utilities\Reflection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(DatePartType::class)]
#[UsesClass(BaseTypeTestCaseTrait::class)]
#[UsesClass(BaseType::class)]
#[UsesClass(Reflection::class)]
class DatePartTypeTest extends BaseTypeTestCase
{
    /**
     * {@inheritdoc}
     */
    public static function provideTypeToVerify(): Generator
    {
        yield [
            DatePartType::isCorrectType(''),
            false,
        ];

        yield [
            DatePartType::isCorrectType(null),
            false,
        ];

        yield [
            DatePartType::isCorrectType('0'),
            false,
        ];

        yield [
            DatePartType::isCorrectType('1'),
            false,
        ];

        yield [
            DatePartType::isCorrectType('day'),
            true,
        ];

        yield [
            DatePartType::isCorrectType('hour'),
            true,
        ];

        yield [
            DatePartType::isCorrectType('minute'),
            true,
        ];

        yield [
            DatePartType::isCorrectType('month'),
            true,
        ];

        yield [
            DatePartType::isCorrectType('second'),
            true,
        ];

        yield [
            DatePartType::isCorrectType('year'),
            true,
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getAllExpectedTypes(): array
    {
        return [
            'DAY' => 'day',
            'HOUR' => 'hour',
            'MINUTE' => 'minute',
            'MONTH' => 'month',
            'SECOND' => 'second',
            'YEAR' => 'year',
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getTestedTypeInstance(): BaseType
    {
        return new DatePartType();
    }
}
