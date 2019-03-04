<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Test\Common\Type;

use Meritoo\Common\Test\Base\BaseTypeTestCase;
use Meritoo\Common\Type\DatePartType;

/**
 * Test case of the type of date part, e.g. "year"
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class DatePartTypeTest extends BaseTypeTestCase
{
    /**
     * {@inheritdoc}
     */
    public function provideTypeToVerify()
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

    /**
     * {@inheritdoc}
     */
    protected function getAllExpectedTypes()
    {
        return [
            'DAY'    => DatePartType::DAY,
            'HOUR'   => DatePartType::HOUR,
            'MINUTE' => DatePartType::MINUTE,
            'MONTH'  => DatePartType::MONTH,
            'SECOND' => DatePartType::SECOND,
            'YEAR'   => DatePartType::YEAR,
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getTestedTypeInstance()
    {
        return new DatePartType();
    }
}
