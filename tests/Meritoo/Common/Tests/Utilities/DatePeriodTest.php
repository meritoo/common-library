<?php

namespace Meritoo\Common\Tests\Utilities;

use DateTime;
use Generator;
use Meritoo\Common\Utilities\DatePeriod;
use Meritoo\Common\Utilities\TestCase;

/**
 * Tests of date's period
 *
 * @author    Krzysztof Niziol <krzysztof.niziol@meritoo.pl>
 * @copyright Meritoo.pl
 */
class DatePeriodTest extends TestCase
{
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
     * @param mixed $period Empty value, e.g. ""
     * @dataProvider provideEmptyValue
     */
    public function testIsCorrectPeriodEmptyPeriod($period)
    {
        self::assertFalse(DatePeriod::isCorrectPeriod($period));
    }

    /**
     * @param int $period Incorrect period to verify
     * @dataProvider provideIncorrectPeriod
     */
    public function testIsCorrectPeriodIncorrectPeriod($period)
    {
        self::assertFalse(DatePeriod::isCorrectPeriod($period));
    }

    /**
     * @param int $period The period to verify
     * @dataProvider providePeriod
     */
    public function testIsCorrectPeriod($period)
    {
        self::assertTrue(DatePeriod::isCorrectPeriod($period));
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
     * Provides incorrect period
     *
     * @return Generator
     */
    public function provideIncorrectPeriod()
    {
        yield[-1];
        yield[0];
        yield[10];
    }

    /**
     * Provides period to verify
     *
     * @return Generator
     */
    public function providePeriod()
    {
        yield[DatePeriod::LAST_WEEK];
        yield[DatePeriod::THIS_WEEK];
        yield[DatePeriod::NEXT_WEEK];
        yield[DatePeriod::LAST_MONTH];
        yield[DatePeriod::THIS_MONTH];
        yield[DatePeriod::NEXT_MONTH];
        yield[DatePeriod::LAST_YEAR];
        yield[DatePeriod::THIS_YEAR];
        yield[DatePeriod::NEXT_YEAR];
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
}
