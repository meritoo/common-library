<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Tests\Type;

use Generator;
use Meritoo\Common\Type\DatePartType;
use PHPUnit_Framework_TestCase;

/**
 * Tests of the type of date part, e.g. "year"
 *
 * @author    Krzysztof Niziol <krzysztof.niziol@meritoo.pl>
 * @copyright Meritoo.pl
 */
class DatePartTypeTest extends PHPUnit_Framework_TestCase
{
    public function testGetAll()
    {
        $expectedTypes = [
            'DAY'    => DatePartType::DAY,
            'HOUR'   => DatePartType::HOUR,
            'MINUTE' => DatePartType::MINUTE,
            'MONTH'  => DatePartType::MONTH,
            'SECOND' => DatePartType::SECOND,
            'YEAR'   => DatePartType::YEAR,
        ];

        $all = (new DatePartType())->getAll();
        self::assertEquals($expectedTypes, $all);
    }

    /**
     * @param string $toVerifyType Concrete type to verify (of given instance of type)
     * @param bool   $isCorrect    Expected information if given type is correct
     *
     * @dataProvider provideConcreteType
     */
    public function testIsCorrectType($toVerifyType, $isCorrect)
    {
        $type = new DatePartType();
        self::assertEquals($isCorrect, $type->isCorrectType($toVerifyType));
    }

    /**
     * Provides type of something for testing the isCorrectType() method
     *
     * @return Generator
     */
    public function provideConcreteType()
    {
        yield[
            '',
            false,
        ];

        yield[
            null,
            false,
        ];

        yield[
            0,
            false,
        ];

        yield[
            1,
            false,
        ];

        yield[
            'day',
            true,
        ];

        yield[
            'hour',
            true,
        ];

        yield[
            'minute',
            true,
        ];

        yield[
            'month',
            true,
        ];

        yield[
            'second',
            true,
        ];

        yield[
            'year',
            true,
        ];
    }
}
