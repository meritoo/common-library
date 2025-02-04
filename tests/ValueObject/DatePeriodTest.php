<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Test\Common\ValueObject;

use DateTime;
use Generator;
use Meritoo\Common\Enums\OopVisibility;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\ValueObject\DatePeriod;

/**
 * Test case of date's period
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 *
 * @internal
 * @covers    \Meritoo\Common\ValueObject\DatePeriod
 */
class DatePeriodTest extends BaseTestCase
{
    /**
     * Provides the start and end date of date period
     *
     * @return Generator
     */
    public function provideDatePeriod(): Generator
    {
        $startDate = new DateTime('2001-01-01');
        $endDate = new DateTime('2002-02-02');

        yield [
            null,
            null,
        ];

        yield [
            $startDate,
            $startDate,
            null,
        ];

        yield [
            null,
            null,
            $endDate,
        ];

        yield [
            $startDate,
            $endDate,
        ];
    }

    /**
     * Provides period and format of date to verify
     *
     * @return Generator
     */
    public function provideDatePeriodAndDateFormat(): Generator
    {
        $startDate = new DateTime('2001-01-01');
        $endDate = new DateTime('2002-02-02');

        // For start date
        yield [
            new DatePeriod($startDate, $endDate),
            'Y',
            true,
            '2001',
        ];

        yield [
            new DatePeriod($startDate, $endDate),
            'D',
            true,
            'Mon',
        ];

        yield [
            new DatePeriod($startDate, $endDate),
            'Y-m-d',
            true,
            '2001-01-01',
        ];

        yield [
            new DatePeriod($startDate, $endDate),
            'Y-m-d H:i',
            true,
            '2001-01-01 00:00',
        ];

        // For end date
        yield [
            new DatePeriod($startDate, $endDate),
            'Y',
            false,
            '2002',
        ];

        yield [
            new DatePeriod($startDate, $endDate),
            'D',
            false,
            'Sat',
        ];

        yield [
            new DatePeriod($startDate, $endDate),
            'Y-m-d',
            false,
            '2002-02-02',
        ];

        yield [
            new DatePeriod($startDate, $endDate),
            'Y-m-d H:i',
            false,
            '2002-02-02 00:00',
        ];
    }

    /**
     * Provides period and format of date to verify using the start date
     *
     * @return Generator
     */
    public function provideDatePeriodAndDateFormatUsingStartDateOnly(): Generator
    {
        $startDate = new DateTime('2001-01-01');
        $endDate = new DateTime('2002-02-02');

        yield [
            new DatePeriod($startDate, $endDate),
            'Y',
            '2001',
        ];

        yield [
            new DatePeriod($startDate, $endDate),
            'D',
            'Mon',
        ];

        yield [
            new DatePeriod($startDate, $endDate),
            'Y-m-d',
            '2001-01-01',
        ];

        yield [
            new DatePeriod($startDate, $endDate),
            'Y-m-d H:i',
            '2001-01-01 00:00',
        ];
    }

    public function provideDatePeriodAndIncorrectDateFormat(): Generator
    {
        $startDate = new DateTime('2001-01-01');
        $endDate = new DateTime('2002-02-02');

        yield [
            new DatePeriod($startDate, $endDate),
            '',
        ];

        yield [
            new DatePeriod($startDate, $endDate),
            'false',
        ];

        yield [
            new DatePeriod($startDate, $endDate),
            'xyz',
        ];
    }

    public function provideDatePeriodAndUnknownDate(): ?Generator
    {
        $date = new DateTime('2001-01-01');

        yield [
            new DatePeriod(),
            'Y-m-d',
            false,
        ];

        yield [
            new DatePeriod(),
            'Y-m-d',
            true,
        ];

        yield [
            new DatePeriod($date),
            'Y-m-d',
            false,
        ];

        yield [
            new DatePeriod(null, $date),
            'Y-m-d',
            true,
        ];
    }

    /**
     * @param DateTime $startDate (optional) Start date of period
     * @param DateTime $endDate   (optional) End date of period
     *
     * @dataProvider provideDatePeriod
     */
    public function testConstruct(DateTime $startDate = null, DateTime $endDate = null): void
    {
        $period = new DatePeriod($startDate, $endDate);

        self::assertEquals($startDate, $period->getStartDate());
        self::assertEquals($endDate, $period->getEndDate());
    }

    public function testConstructorVisibilityAndArguments(): void
    {
        static::assertConstructorVisibilityAndArguments(
            DatePeriod::class,
            OopVisibility::Public,
            2
        );
    }

    /**
     * @param DatePeriod $period    The date period to verify
     * @param string     $format    Format used to format the date
     * @param bool       $startDate If is set to true, start date is formatted. Otherwise - end date.
     * @param string     $expected  Expected, formatted date
     *
     * @dataProvider provideDatePeriodAndDateFormat
     */
    public function testGetFormattedDate(DatePeriod $period, $format, $startDate, $expected): void
    {
        self::assertEquals($expected, $period->getFormattedDate($format, $startDate));
    }

    /** @dataProvider provideDatePeriodAndIncorrectDateFormat */
    public function testGetFormattedDateUsingIncorrectDateFormat(DatePeriod $period, string $format): void
    {
        self::assertEquals('', $period->getFormattedDate($format));
    }

    /**
     * @param DatePeriod $period   The date period to verify
     * @param string     $format   Format used to format the date
     * @param string     $expected Expected, formatted date
     *
     * @dataProvider provideDatePeriodAndDateFormatUsingStartDateOnly
     */
    public function testGetFormattedDateUsingStartDateOnly(DatePeriod $period, $format, $expected): void
    {
        self::assertEquals($expected, $period->getFormattedDate($format));
    }

    /**
     * @param DatePeriod $period    The date period to verify
     * @param string     $format    Format used to format the date
     * @param bool       $startDate If is set to true, start date is formatted. Otherwise - end date.
     *
     * @dataProvider provideDatePeriodAndUnknownDate
     */
    public function testGetFormattedDateUsingUnknownDate(DatePeriod $period, $format, $startDate): void
    {
        self::assertEquals('', $period->getFormattedDate($format, $startDate));
    }

    /** @dataProvider provideDatePeriod */
    public function testGettersAndSetters(DateTime $startDate = null, DateTime $endDate = null): void
    {
        $period = new DatePeriod();

        $period->setStartDate($startDate);
        self::assertEquals($startDate, $period->getStartDate());

        $period->setEndDate($endDate);
        self::assertEquals($endDate, $period->getEndDate());
    }
}
