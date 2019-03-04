<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Test\Common\Type;

use DateTime;
use Generator;
use Meritoo\Common\Test\Base\BaseTypeTestCase;
use Meritoo\Common\Type\DatePeriod;
use Meritoo\Common\Type\OopVisibilityType;

/**
 * Test case of date's period
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class DatePeriodTest extends BaseTypeTestCase
{
    public function testConstructorVisibilityAndArguments()
    {
        static::assertConstructorVisibilityAndArguments(DatePeriod::class, OopVisibilityType::IS_PUBLIC, 2, 0);
    }

    /**
     * @param DateTime $startDate (optional) Start date of period
     * @param DateTime $endDate   (optional) End date of period
     *
     * @dataProvider provideDatePeriod
     */
    public function testConstruct(DateTime $startDate = null, DateTime $endDate = null)
    {
        $period = new DatePeriod($startDate, $endDate);

        self::assertEquals($startDate, $period->getStartDate());
        self::assertEquals($endDate, $period->getEndDate());
    }

    /**
     * @param DateTime $startDate (optional) Start date of period
     * @param DateTime $endDate   (optional) End date of period
     *
     * @dataProvider provideDatePeriod
     */
    public function testGettersAndSetters(DateTime $startDate = null, DateTime $endDate = null)
    {
        $period = new DatePeriod();

        $period->setStartDate($startDate);
        self::assertEquals($startDate, $period->getStartDate());

        $period->setEndDate($endDate);
        self::assertEquals($endDate, $period->getEndDate());
    }

    /**
     * @param DatePeriod $period The date period to verify
     * @param string     $format Format used to format the date
     *
     * @dataProvider provideDatePeriodAndIncorrectDateFormat
     */
    public function testGetFormattedDateIncorrectDateFormat(DatePeriod $period, $format)
    {
        self::assertEquals('', $period->getFormattedDate($format));
    }

    /**
     * @param DatePeriod $period    The date period to verify
     * @param string     $format    Format used to format the date
     * @param bool       $startDate If is set to true, start date is formatted. Otherwise - end date.
     * @param string     $expected  Expected, formatted date
     *
     * @dataProvider provideDatePeriodAndDateFormat
     */
    public function testGetFormattedDate(DatePeriod $period, $format, $startDate, $expected)
    {
        self::assertEquals($expected, $period->getFormattedDate($format, $startDate));
    }

    /**
     * Provides the start and end date of date period
     *
     * @return Generator
     */
    public function provideDatePeriod()
    {
        $startDate = new DateTime('2001-01-01');
        $endDate = new DateTime('2002-02-02');

        yield[
            null,
            null,
        ];

        yield[
            $startDate,
            $startDate,
            null,
        ];

        yield[
            null,
            null,
            $endDate,
        ];

        yield[
            $startDate,
            $endDate,
        ];
    }

    /**
     * Provides period and incorrect format of date to verify
     *
     * @return Generator
     */
    public function provideDatePeriodAndIncorrectDateFormat()
    {
        $startDate = new DateTime('2001-01-01');
        $endDate = new DateTime('2002-02-02');

        yield[
            new DatePeriod($startDate, $endDate),
            '',
        ];

        yield[
            new DatePeriod($startDate, $endDate),
            null,
        ];

        yield[
            new DatePeriod($startDate, $endDate),
            false,
        ];
    }

    /**
     * Provides period and format of date to verify
     *
     * @return Generator
     */
    public function provideDatePeriodAndDateFormat()
    {
        $startDate = new DateTime('2001-01-01');
        $endDate = new DateTime('2002-02-02');

        /*
         * For start date
         */
        yield[
            new DatePeriod($startDate, $endDate),
            'Y',
            true,
            '2001',
        ];

        yield[
            new DatePeriod($startDate, $endDate),
            'D',
            true,
            'Mon',
        ];

        yield[
            new DatePeriod($startDate, $endDate),
            'Y-m-d',
            true,
            '2001-01-01',
        ];

        yield[
            new DatePeriod($startDate, $endDate),
            'Y-m-d H:i',
            true,
            '2001-01-01 00:00',
        ];

        /*
         * For end date
         */
        yield[
            new DatePeriod($startDate, $endDate),
            'Y',
            false,
            '2002',
        ];

        yield[
            new DatePeriod($startDate, $endDate),
            'D',
            false,
            'Sat',
        ];

        yield[
            new DatePeriod($startDate, $endDate),
            'Y-m-d',
            false,
            '2002-02-02',
        ];

        yield[
            new DatePeriod($startDate, $endDate),
            'Y-m-d H:i',
            false,
            '2002-02-02 00:00',
        ];
    }

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
            -1,
            false,
        ];

        yield[
            true,
            false,
        ];

        yield[
            DatePeriod::LAST_MONTH,
            true,
        ];

        yield[
            DatePeriod::NEXT_WEEK,
            true,
        ];

        yield[
            DatePeriod::THIS_YEAR,
            true,
        ];
    }

    /**
     * Returns all expected types of the tested type
     *
     * @return array
     */
    protected function getAllExpectedTypes()
    {
        return [
            'LAST_MONTH' => DatePeriod::LAST_MONTH,
            'LAST_WEEK'  => DatePeriod::LAST_WEEK,
            'LAST_YEAR'  => DatePeriod::LAST_YEAR,
            'NEXT_MONTH' => DatePeriod::NEXT_MONTH,
            'NEXT_WEEK'  => DatePeriod::NEXT_WEEK,
            'NEXT_YEAR'  => DatePeriod::NEXT_YEAR,
            'THIS_MONTH' => DatePeriod::THIS_MONTH,
            'THIS_WEEK'  => DatePeriod::THIS_WEEK,
            'THIS_YEAR'  => DatePeriod::THIS_YEAR,
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getTestedTypeInstance()
    {
        return new DatePeriod();
    }
}
