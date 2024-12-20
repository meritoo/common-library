<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Test\Common\Utilities;

use DateInterval;
use DateTime;
use Generator;
use Meritoo\Common\Enums\Date\DatePeriod as DatePeriodEnum;
use Meritoo\Common\Exception\Date\InvalidDatePartException;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Utilities\Date;
use Meritoo\Common\Utilities\Locale;
use Meritoo\Common\ValueObject\DatePeriod;

/**
 * Test case of the Date methods (only static functions)
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 *
 * @internal
 * @covers    \Meritoo\Common\Utilities\Date
 */
class DateTest extends BaseTestCase
{
    /**
     * Provides correct period
     *
     * @return Generator
     */
    public function provideCorrectPeriod()
    {
        yield [
            DatePeriodEnum::LastWeek,
            new DatePeriod(
                (new DateTime('this week'))->sub(new DateInterval('P7D'))->setTime(0, 0, 0),
                (new DateTime('this week'))->sub(new DateInterval('P1D'))->setTime(23, 59, 59)
            ),
        ];

        yield [
            DatePeriodEnum::ThisWeek,
            new DatePeriod(
                (new DateTime('this week'))->setTime(0, 0, 0),
                (new DateTime('this week'))->add(new DateInterval('P6D'))->setTime(23, 59, 59)
            ),
        ];

        yield [
            DatePeriodEnum::NextWeek,
            new DatePeriod(
                (new DateTime('this week'))->add(new DateInterval('P7D'))->setTime(0, 0, 0),
                (new DateTime('this week'))->add(new DateInterval('P7D'))
                    ->add(new DateInterval('P6D'))
                    ->setTime(23, 59, 59)
            ),
        ];

        yield [
            DatePeriodEnum::LastMonth,
            new DatePeriod(
                (new DateTime('first day of last month'))->setTime(0, 0, 0),
                (new DateTime('last day of last month'))->setTime(23, 59, 59)
            ),
        ];

        yield [
            DatePeriodEnum::ThisMonth,
            new DatePeriod(
                Date::getDatesForPeriod(DatePeriodEnum::LastMonth)
                    ->getEndDate()
                    ->add(new DateInterval('P1D'))
                    ->setTime(0, 0, 0),
                Date::getDatesForPeriod(DatePeriodEnum::NextMonth)
                    ->getStartDate()
                    ->sub(new DateInterval('P1D'))
                    ->setTime(23, 59, 59)
            ),
        ];

        yield [
            DatePeriodEnum::NextMonth,
            new DatePeriod(
                (new DateTime('first day of next month'))->setTime(0, 0, 0),
                (new DateTime('last day of next month'))->setTime(23, 59, 59)
            ),
        ];

        $lastYearStart = (new DateTime())->modify('-1 year');
        $lastYearEnd = (new DateTime())->modify('-1 year');
        $year = $lastYearStart->format('Y');

        yield [
            DatePeriodEnum::LastYear,
            new DatePeriod(
                $lastYearStart->setDate($year, 1, 1)->setTime(0, 0, 0),
                $lastYearEnd->setDate($year, 12, 31)->setTime(23, 59, 59)
            ),
        ];

        $year = (new DateTime())->format('Y');

        yield [
            DatePeriodEnum::ThisYear,
            new DatePeriod(
                (new DateTime())->setDate($year, 1, 1)->setTime(0, 0, 0),
                (new DateTime())->setDate($year, 12, 31)->setTime(23, 59, 59)
            ),
        ];

        $nextYearStart = (new DateTime())->modify('1 year');
        $nextYearEnd = (new DateTime())->modify('1 year');
        $year = $nextYearStart->format('Y');

        yield [
            DatePeriodEnum::NextYear,
            new DatePeriod(
                $nextYearStart->setDate($year, 1, 1)->setTime(0, 0, 0),
                $nextYearEnd->setDate($year, 12, 31)->setTime(23, 59, 59)
            ),
        ];
    }

    /**
     * Provides data for the random date
     *
     * @return Generator
     */
    public function provideDataOfRandomDate()
    {
        yield [
            new DateTime('2000-01-01'),
            1,
            100,
        ];

        yield [
            new DateTime('2000-12-01'),
            1,
            100,
        ];
        yield [
            new DateTime('2000-01-01'),
            '1',
            '100',
        ];

        yield [
            new DateTime('2000-12-01'),
            '1',
            '100',
        ];

        yield [
            new DateTime('2000-01-01'),
            10,
            50,
        ];

        yield [
            new DateTime('2000-12-01'),
            10,
            50,
        ];
    }

    /**
     * Provides data for the random date with incorrect end of random partition
     *
     * @return Generator
     */
    public function provideDataOfRandomDateIncorrectEnd()
    {
        yield [
            new DateTime('2000-01-01'),
            100,
            1,
        ];
    }

    /**
     * Provide empty dates for date difference
     *
     * @return Generator
     */
    public function provideEmptyDatesForDateDifference()
    {
        yield [
            null,
            null,
        ];

        yield [
            '',
            '',
        ];

        yield [
            null,
            new DateTime(),
        ];

        yield [
            new DateTime(),
            null,
        ];
    }

    /**
     * Provides incorrect invalidCount of DateTime
     *
     * @return Generator
     */
    public function provideIncorrectDateTimeValue()
    {
        // Incorrect one-character values
        yield ['a'];
        yield ['m'];

        // Incorrect strings
        yield ['ss'];
        yield ['sss'];
        yield ['mm'];
        yield ['yy'];
        yield ['yyyy'];

        // Incorrect integer values
        yield [1];
        yield [10];
        yield [15];
        yield [100];
        yield [1000];

        // Incorrect string / numeric values
        yield ['1'];
        yield ['10'];
        yield ['15'];
        yield ['100'];
        yield ['1000'];

        // Incorrect dates
        yield ['0-0-0'];
        yield ['20-01-01'];
        yield ['2015-0-0'];
        yield ['2015-00-00'];
        yield ['2015-16-01'];
    }

    /**
     * Provides incorrect values of year, month and day
     *
     * @return Generator
     */
    public function provideIncorrectYearMonthDay(): Generator
    {
        yield [
            0,
            0,
            0,
            'month',
            0,
        ];

        yield [
            -1,
            -1,
            -1,
            'year',
            -1,
        ];

        yield [
            5000,
            50,
            50,
            'month',
            50,
        ];

        yield [
            2000,
            13,
            01,
            'month',
            13,
        ];

        yield [
            2000,
            01,
            40,
            'day',
            40,
        ];
    }

    /**
     * Provides invalid format of date
     *
     * @return Generator
     */
    public function provideInvalidDateFormats()
    {
        yield [0];
        yield [9];
        yield ['[]'];
        yield ['invalid'];
        yield ['Q'];
        yield [','];
        yield ['.'];
        yield ['aa###'];
        yield ['Y/m/d H:i:invalid'];
    }

    /**
     * Provides values of year, month and day
     *
     * @return Generator
     */
    public function provideYearMonthDay()
    {
        yield [
            2000,
            01,
            01,
        ];

        yield [
            2000,
            1,
            1,
        ];

        yield [
            2000,
            2,
            2,
        ];

        yield [
            2000,
            6,
            1,
        ];

        yield [
            2000,
            12,
            01,
        ];

        yield [
            2000,
            12,
            1,
        ];

        yield [
            2000,
            12,
            31,
        ];
    }

    public function testConstructor(): void
    {
        static::assertHasNoConstructor(Date::class);
    }

    public function testGenerateRandomTimeCustomFormat(): void
    {
        self::assertMatchesRegularExpression('/^0[1-9]{1}|1[0-2]{1}$/', Date::generateRandomTime('h')); // 01 through 12
        self::assertMatchesRegularExpression('/^[0-5]?[0-9]$/', Date::generateRandomTime('i')); // 00 through 59
        self::assertMatchesRegularExpression('/^[0-5]?[0-9]$/', Date::generateRandomTime('s')); // 00 through 59

        self::assertMatchesRegularExpression('/^\d{2}:\d{2}$/', Date::generateRandomTime('H:i'));
        self::assertMatchesRegularExpression('/^[1-9]|1[0-2]:\d{2}$/', Date::generateRandomTime('g:i'));
    }

    public function testGenerateRandomTimeDefaultFormat(): void
    {
        self::assertMatchesRegularExpression('/\d{2}:\d{2}:\d{2}/', Date::generateRandomTime());
    }

    /**
     * @param mixed $value Empty value, e.g. ""
     * @dataProvider provideEmptyScalarValue
     */
    public function testGenerateRandomTimeEmptyFormat($value): void
    {
        self::assertNull(Date::generateRandomTime($value));
    }

    public function testGenerateRandomTimeIncorrectFormat(): void
    {
        self::assertNull(Date::generateRandomTime(','));
        self::assertNull(Date::generateRandomTime(';'));
        self::assertNull(Date::generateRandomTime('|'));
        self::assertNull(Date::generateRandomTime('?'));
    }

    public function testGetCurrentDayOfWeek(): void
    {
        self::assertMatchesRegularExpression('/^[0-6]{1}$/', (string) Date::getCurrentDayOfWeek());
    }

    public function testGetCurrentDayOfWeekName(): void
    {
        // Required to avoid failure:
        //
        // Failed asserting that 'giovedì' matches PCRE pattern
        // "/^Monday|Tuesday|Wednesday|Thursday|Friday|Saturday|Sunday$/"
        Locale::setLocale(LC_ALL, 'en', 'US');

        $days = [
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
            'Saturday',
            'Sunday',
        ];

        $pattern = sprintf('/^%s$/', implode('|', $days));

        self::assertMatchesRegularExpression($pattern, Date::getCurrentDayOfWeekName());
    }

    /**
     * @param DateTime|string $dateStart The start date
     * @param DateTime|string $dateEnd   The end date
     *
     * @dataProvider provideEmptyDatesForDateDifference
     */
    public function testGetDateDifferenceEmptyDates($dateStart, $dateEnd): void
    {
        self::assertNull(Date::getDateDifference($dateStart, $dateEnd));
    }

    public function testGetDateDifferenceEqual24Hours(): void
    {
        $dateStart = '2017-01-01 00:00';
        $dateEnd = '2017-01-02 00:00';

        $effect = [
            Date::DATE_DIFFERENCE_UNIT_YEARS => 0,
            Date::DATE_DIFFERENCE_UNIT_MONTHS => 0,
            Date::DATE_DIFFERENCE_UNIT_DAYS => 1,
            Date::DATE_DIFFERENCE_UNIT_HOURS => 0,
            Date::DATE_DIFFERENCE_UNIT_MINUTES => 0,
        ];

        self::assertEquals($effect, Date::getDateDifference($dateStart, $dateEnd));
        self::assertEquals($effect, Date::getDateDifference(new DateTime($dateStart), new DateTime($dateEnd)));

        self::assertEquals(0, Date::getDateDifference($dateStart, $dateEnd, Date::DATE_DIFFERENCE_UNIT_YEARS));
        self::assertEquals(0, Date::getDateDifference(new DateTime($dateStart), new DateTime($dateEnd), Date::DATE_DIFFERENCE_UNIT_YEARS));

        self::assertEquals(0, Date::getDateDifference($dateStart, $dateEnd, Date::DATE_DIFFERENCE_UNIT_MONTHS));
        self::assertEquals(0, Date::getDateDifference(new DateTime($dateStart), new DateTime($dateEnd), Date::DATE_DIFFERENCE_UNIT_MONTHS));

        self::assertEquals(1, Date::getDateDifference($dateStart, $dateEnd, Date::DATE_DIFFERENCE_UNIT_DAYS));
        self::assertEquals(1, Date::getDateDifference(new DateTime($dateStart), new DateTime($dateEnd), Date::DATE_DIFFERENCE_UNIT_DAYS));

        self::assertEquals(0, Date::getDateDifference($dateStart, $dateEnd, Date::DATE_DIFFERENCE_UNIT_HOURS));
        self::assertEquals(0, Date::getDateDifference(new DateTime($dateStart), new DateTime($dateEnd), Date::DATE_DIFFERENCE_UNIT_HOURS));

        self::assertEquals(0, Date::getDateDifference($dateStart, $dateEnd, Date::DATE_DIFFERENCE_UNIT_MINUTES));
        self::assertEquals(0, Date::getDateDifference(new DateTime($dateStart), new DateTime($dateEnd), Date::DATE_DIFFERENCE_UNIT_MINUTES));
    }

    public function testGetDateDifferenceInvalidDates(): void
    {
        self::assertNull(Date::getDateDifference('2017-01-40', '2017-13-01'));
        self::assertNull(Date::getDateDifference('xyz', 'lorem'));
    }

    public function testGetDateDifferenceInvertedDates(): void
    {
        $dateStart = '2017-01-02 10:00';
        $dateEnd = '2017-01-01 16:00';

        $effect = [
            Date::DATE_DIFFERENCE_UNIT_YEARS => 0,
            Date::DATE_DIFFERENCE_UNIT_MONTHS => 0,
            Date::DATE_DIFFERENCE_UNIT_DAYS => -1,
            Date::DATE_DIFFERENCE_UNIT_HOURS => 6,
            Date::DATE_DIFFERENCE_UNIT_MINUTES => 0,
        ];

        self::assertEquals($effect, Date::getDateDifference($dateStart, $dateEnd));
        self::assertEquals($effect, Date::getDateDifference(new DateTime($dateStart), new DateTime($dateEnd)));

        self::assertEquals(0, Date::getDateDifference($dateStart, $dateEnd, Date::DATE_DIFFERENCE_UNIT_YEARS));
        self::assertEquals(0, Date::getDateDifference(new DateTime($dateStart), new DateTime($dateEnd), Date::DATE_DIFFERENCE_UNIT_YEARS));

        self::assertEquals(0, Date::getDateDifference($dateStart, $dateEnd, Date::DATE_DIFFERENCE_UNIT_MONTHS));
        self::assertEquals(0, Date::getDateDifference(new DateTime($dateStart), new DateTime($dateEnd), Date::DATE_DIFFERENCE_UNIT_MONTHS));

        self::assertEquals(-1, Date::getDateDifference($dateStart, $dateEnd, Date::DATE_DIFFERENCE_UNIT_DAYS));
        self::assertEquals(-1, Date::getDateDifference(new DateTime($dateStart), new DateTime($dateEnd), Date::DATE_DIFFERENCE_UNIT_DAYS));

        self::assertEquals(6, Date::getDateDifference($dateStart, $dateEnd, Date::DATE_DIFFERENCE_UNIT_HOURS));
        self::assertEquals(6, Date::getDateDifference(new DateTime($dateStart), new DateTime($dateEnd), Date::DATE_DIFFERENCE_UNIT_HOURS));

        self::assertEquals(0, Date::getDateDifference($dateStart, $dateEnd, Date::DATE_DIFFERENCE_UNIT_MINUTES));
        self::assertEquals(0, Date::getDateDifference(new DateTime($dateStart), new DateTime($dateEnd), Date::DATE_DIFFERENCE_UNIT_MINUTES));
    }

    public function testGetDateDifferenceLessThan24Hours(): void
    {
        $dateStart = '2017-01-01 16:00';
        $dateEnd = '2017-01-02 10:00';

        $effect = [
            Date::DATE_DIFFERENCE_UNIT_YEARS => 0,
            Date::DATE_DIFFERENCE_UNIT_MONTHS => 0,
            Date::DATE_DIFFERENCE_UNIT_DAYS => 0,
            Date::DATE_DIFFERENCE_UNIT_HOURS => 18,
            Date::DATE_DIFFERENCE_UNIT_MINUTES => 0,
        ];

        self::assertEquals($effect, Date::getDateDifference($dateStart, $dateEnd));
        self::assertEquals($effect, Date::getDateDifference(new DateTime($dateStart), new DateTime($dateEnd)));

        self::assertEquals(0, Date::getDateDifference($dateStart, $dateEnd, Date::DATE_DIFFERENCE_UNIT_YEARS));
        self::assertEquals(0, Date::getDateDifference(new DateTime($dateStart), new DateTime($dateEnd), Date::DATE_DIFFERENCE_UNIT_YEARS));

        self::assertEquals(0, Date::getDateDifference($dateStart, $dateEnd, Date::DATE_DIFFERENCE_UNIT_MONTHS));
        self::assertEquals(0, Date::getDateDifference(new DateTime($dateStart), new DateTime($dateEnd), Date::DATE_DIFFERENCE_UNIT_MONTHS));

        self::assertEquals(0, Date::getDateDifference($dateStart, $dateEnd, Date::DATE_DIFFERENCE_UNIT_DAYS));
        self::assertEquals(0, Date::getDateDifference(new DateTime($dateStart), new DateTime($dateEnd), Date::DATE_DIFFERENCE_UNIT_DAYS));

        self::assertEquals(18, Date::getDateDifference($dateStart, $dateEnd, Date::DATE_DIFFERENCE_UNIT_HOURS));
        self::assertEquals(18, Date::getDateDifference(new DateTime($dateStart), new DateTime($dateEnd), Date::DATE_DIFFERENCE_UNIT_HOURS));

        self::assertEquals(0, Date::getDateDifference($dateStart, $dateEnd, Date::DATE_DIFFERENCE_UNIT_MINUTES));
        self::assertEquals(0, Date::getDateDifference(new DateTime($dateStart), new DateTime($dateEnd), Date::DATE_DIFFERENCE_UNIT_MINUTES));
    }

    public function testGetDateDifferenceNewYear(): void
    {
        $dateStart = '2017-12-31 23:59';
        $dateEnd = '2018-01-01 00:00';

        $effect = [
            Date::DATE_DIFFERENCE_UNIT_YEARS => 0,
            Date::DATE_DIFFERENCE_UNIT_MONTHS => 0,
            Date::DATE_DIFFERENCE_UNIT_DAYS => 0,
            Date::DATE_DIFFERENCE_UNIT_HOURS => 0,
            Date::DATE_DIFFERENCE_UNIT_MINUTES => 1,
        ];

        self::assertEquals($effect, Date::getDateDifference($dateStart, $dateEnd));
        self::assertEquals($effect, Date::getDateDifference(new DateTime($dateStart), new DateTime($dateEnd)));

        self::assertEquals(0, Date::getDateDifference($dateStart, $dateEnd, Date::DATE_DIFFERENCE_UNIT_YEARS));
        self::assertEquals(0, Date::getDateDifference(new DateTime($dateStart), new DateTime($dateEnd), Date::DATE_DIFFERENCE_UNIT_YEARS));

        self::assertEquals(0, Date::getDateDifference($dateStart, $dateEnd, Date::DATE_DIFFERENCE_UNIT_MONTHS));
        self::assertEquals(0, Date::getDateDifference(new DateTime($dateStart), new DateTime($dateEnd), Date::DATE_DIFFERENCE_UNIT_MONTHS));

        self::assertEquals(0, Date::getDateDifference($dateStart, $dateEnd, Date::DATE_DIFFERENCE_UNIT_DAYS));
        self::assertEquals(0, Date::getDateDifference(new DateTime($dateStart), new DateTime($dateEnd), Date::DATE_DIFFERENCE_UNIT_DAYS));

        self::assertEquals(0, Date::getDateDifference($dateStart, $dateEnd, Date::DATE_DIFFERENCE_UNIT_HOURS));
        self::assertEquals(0, Date::getDateDifference(new DateTime($dateStart), new DateTime($dateEnd), Date::DATE_DIFFERENCE_UNIT_HOURS));

        self::assertEquals(1, Date::getDateDifference($dateStart, $dateEnd, Date::DATE_DIFFERENCE_UNIT_MINUTES));
        self::assertEquals(1, Date::getDateDifference(new DateTime($dateStart), new DateTime($dateEnd), Date::DATE_DIFFERENCE_UNIT_MINUTES));
    }

    public function testGetDateDifferenceNoDifference(): void
    {
        // No difference
        $dateStart = '2017-01-01 12:00';
        $dateEnd = $dateStart;

        $effect = [
            Date::DATE_DIFFERENCE_UNIT_YEARS => 0,
            Date::DATE_DIFFERENCE_UNIT_MONTHS => 0,
            Date::DATE_DIFFERENCE_UNIT_DAYS => 0,
            Date::DATE_DIFFERENCE_UNIT_HOURS => 0,
            Date::DATE_DIFFERENCE_UNIT_MINUTES => 0,
        ];

        self::assertEquals($effect, Date::getDateDifference($dateStart, $dateEnd));
        self::assertEquals($effect, Date::getDateDifference(new DateTime(), new DateTime()));

        self::assertEquals(0, Date::getDateDifference($dateStart, $dateEnd, Date::DATE_DIFFERENCE_UNIT_YEARS));
        self::assertEquals(0, Date::getDateDifference(new DateTime(), new DateTime(), Date::DATE_DIFFERENCE_UNIT_YEARS));

        self::assertEquals(0, Date::getDateDifference($dateStart, $dateEnd, Date::DATE_DIFFERENCE_UNIT_MONTHS));
        self::assertEquals(0, Date::getDateDifference(new DateTime(), new DateTime(), Date::DATE_DIFFERENCE_UNIT_MONTHS));

        self::assertEquals(0, Date::getDateDifference($dateStart, $dateEnd, Date::DATE_DIFFERENCE_UNIT_DAYS));
        self::assertEquals(0, Date::getDateDifference(new DateTime(), new DateTime(), Date::DATE_DIFFERENCE_UNIT_DAYS));

        self::assertEquals(0, Date::getDateDifference($dateStart, $dateEnd, Date::DATE_DIFFERENCE_UNIT_HOURS));
        self::assertEquals(0, Date::getDateDifference(new DateTime(), new DateTime(), Date::DATE_DIFFERENCE_UNIT_HOURS));

        self::assertEquals(0, Date::getDateDifference($dateStart, $dateEnd, Date::DATE_DIFFERENCE_UNIT_MINUTES));
        self::assertEquals(0, Date::getDateDifference(new DateTime(), new DateTime(), Date::DATE_DIFFERENCE_UNIT_MINUTES));
    }

    public function testGetDateDifferenceOneDay(): void
    {
        // Difference of 1 day
        $dateStart = '2017-01-01';
        $dateEnd = '2017-01-02';

        $effect = [
            Date::DATE_DIFFERENCE_UNIT_YEARS => 0,
            Date::DATE_DIFFERENCE_UNIT_MONTHS => 0,
            Date::DATE_DIFFERENCE_UNIT_DAYS => 1,
            Date::DATE_DIFFERENCE_UNIT_HOURS => 0,
            Date::DATE_DIFFERENCE_UNIT_MINUTES => 0,
        ];

        self::assertEquals($effect, Date::getDateDifference($dateStart, $dateEnd));
        self::assertEquals($effect, Date::getDateDifference(new DateTime($dateStart), new DateTime($dateEnd)));

        self::assertEquals(0, Date::getDateDifference($dateStart, $dateEnd, Date::DATE_DIFFERENCE_UNIT_YEARS));
        self::assertEquals(0, Date::getDateDifference(new DateTime($dateStart), new DateTime($dateEnd), Date::DATE_DIFFERENCE_UNIT_YEARS));

        self::assertEquals(0, Date::getDateDifference($dateStart, $dateEnd, Date::DATE_DIFFERENCE_UNIT_MONTHS));
        self::assertEquals(0, Date::getDateDifference(new DateTime($dateStart), new DateTime($dateEnd), Date::DATE_DIFFERENCE_UNIT_MONTHS));

        self::assertEquals(1, Date::getDateDifference($dateStart, $dateEnd, Date::DATE_DIFFERENCE_UNIT_DAYS));
        self::assertEquals(1, Date::getDateDifference(new DateTime($dateStart), new DateTime($dateEnd), Date::DATE_DIFFERENCE_UNIT_DAYS));

        self::assertEquals(0, Date::getDateDifference($dateStart, $dateEnd, Date::DATE_DIFFERENCE_UNIT_HOURS));
        self::assertEquals(0, Date::getDateDifference(new DateTime($dateStart), new DateTime($dateEnd), Date::DATE_DIFFERENCE_UNIT_HOURS));

        self::assertEquals(0, Date::getDateDifference($dateStart, $dateEnd, Date::DATE_DIFFERENCE_UNIT_MINUTES));
        self::assertEquals(0, Date::getDateDifference(new DateTime($dateStart), new DateTime($dateEnd), Date::DATE_DIFFERENCE_UNIT_MINUTES));

        // Difference of 1 day (using the relative date format)
        $effect = [
            Date::DATE_DIFFERENCE_UNIT_YEARS => 0,
            Date::DATE_DIFFERENCE_UNIT_MONTHS => 0,
            Date::DATE_DIFFERENCE_UNIT_DAYS => 1,
            Date::DATE_DIFFERENCE_UNIT_HOURS => 0,
            Date::DATE_DIFFERENCE_UNIT_MINUTES => 0,
        ];

        self::assertEquals($effect, Date::getDateDifference(new DateTime('yesterday'), new DateTime('midnight')));
        self::assertEquals(0, Date::getDateDifference(new DateTime('yesterday'), new DateTime('midnight'), Date::DATE_DIFFERENCE_UNIT_MONTHS));
        self::assertEquals(1, Date::getDateDifference(new DateTime('yesterday'), new DateTime('midnight'), Date::DATE_DIFFERENCE_UNIT_DAYS));
        self::assertEquals(0, Date::getDateDifference(new DateTime('yesterday'), new DateTime('midnight'), Date::DATE_DIFFERENCE_UNIT_HOURS));
        self::assertEquals(0, Date::getDateDifference(new DateTime('yesterday'), new DateTime('midnight'), Date::DATE_DIFFERENCE_UNIT_MINUTES));
    }

    public function testGetDateDifferenceOneDayTwoHours(): void
    {
        // Difference of 1 day, 2 hours and 15 minutes
        $dateStart = '2017-01-01 12:00';
        $dateEnd = '2017-01-02 14:15';

        $effect = [
            Date::DATE_DIFFERENCE_UNIT_YEARS => 0,
            Date::DATE_DIFFERENCE_UNIT_MONTHS => 0,
            Date::DATE_DIFFERENCE_UNIT_DAYS => 1,
            Date::DATE_DIFFERENCE_UNIT_HOURS => 2,
            Date::DATE_DIFFERENCE_UNIT_MINUTES => 15,
        ];

        self::assertEquals($effect, Date::getDateDifference($dateStart, $dateEnd));
        self::assertEquals($effect, Date::getDateDifference(new DateTime($dateStart), new DateTime($dateEnd)));

        self::assertEquals(0, Date::getDateDifference($dateStart, $dateEnd, Date::DATE_DIFFERENCE_UNIT_YEARS));
        self::assertEquals(0, Date::getDateDifference(new DateTime($dateStart), new DateTime($dateEnd), Date::DATE_DIFFERENCE_UNIT_YEARS));

        self::assertEquals(0, Date::getDateDifference($dateStart, $dateEnd, Date::DATE_DIFFERENCE_UNIT_MONTHS));
        self::assertEquals(0, Date::getDateDifference(new DateTime($dateStart), new DateTime($dateEnd), Date::DATE_DIFFERENCE_UNIT_MONTHS));

        self::assertEquals(1, Date::getDateDifference($dateStart, $dateEnd, Date::DATE_DIFFERENCE_UNIT_DAYS));
        self::assertEquals(1, Date::getDateDifference(new DateTime($dateStart), new DateTime($dateEnd), Date::DATE_DIFFERENCE_UNIT_DAYS));

        self::assertEquals(2, Date::getDateDifference($dateStart, $dateEnd, Date::DATE_DIFFERENCE_UNIT_HOURS));
        self::assertEquals(2, Date::getDateDifference(new DateTime($dateStart), new DateTime($dateEnd), Date::DATE_DIFFERENCE_UNIT_HOURS));

        self::assertEquals(15, Date::getDateDifference($dateStart, $dateEnd, Date::DATE_DIFFERENCE_UNIT_MINUTES));
        self::assertEquals(15, Date::getDateDifference(new DateTime($dateStart), new DateTime($dateEnd), Date::DATE_DIFFERENCE_UNIT_MINUTES));
    }

    public function testGetDateDifferenceOneMonthFortyOneDays(): void
    {
        // Difference of 1 month, 41 days, 4 hours and 30 minutes
        $dateStart = '2017-01-01 12:00';
        $dateEnd = '2017-02-11 16:30';

        $effect = [
            Date::DATE_DIFFERENCE_UNIT_YEARS => 0,
            Date::DATE_DIFFERENCE_UNIT_MONTHS => 1,
            Date::DATE_DIFFERENCE_UNIT_DAYS => 41,
            Date::DATE_DIFFERENCE_UNIT_HOURS => 4,
            Date::DATE_DIFFERENCE_UNIT_MINUTES => 30,
        ];

        self::assertEquals($effect, Date::getDateDifference($dateStart, $dateEnd));
        self::assertEquals($effect, Date::getDateDifference(new DateTime($dateStart), new DateTime($dateEnd)));

        self::assertEquals(0, Date::getDateDifference($dateStart, $dateEnd, Date::DATE_DIFFERENCE_UNIT_YEARS));
        self::assertEquals(0, Date::getDateDifference(new DateTime($dateStart), new DateTime($dateEnd), Date::DATE_DIFFERENCE_UNIT_YEARS));

        self::assertEquals(1, Date::getDateDifference($dateStart, $dateEnd, Date::DATE_DIFFERENCE_UNIT_MONTHS));
        self::assertEquals(1, Date::getDateDifference(new DateTime($dateStart), new DateTime($dateEnd), Date::DATE_DIFFERENCE_UNIT_MONTHS));

        self::assertEquals(41, Date::getDateDifference($dateStart, $dateEnd, Date::DATE_DIFFERENCE_UNIT_DAYS));
        self::assertEquals(41, Date::getDateDifference(new DateTime($dateStart), new DateTime($dateEnd), Date::DATE_DIFFERENCE_UNIT_DAYS));

        self::assertEquals(4, Date::getDateDifference($dateStart, $dateEnd, Date::DATE_DIFFERENCE_UNIT_HOURS));
        self::assertEquals(4, Date::getDateDifference(new DateTime($dateStart), new DateTime($dateEnd), Date::DATE_DIFFERENCE_UNIT_HOURS));

        self::assertEquals(30, Date::getDateDifference($dateStart, $dateEnd, Date::DATE_DIFFERENCE_UNIT_MINUTES));
        self::assertEquals(30, Date::getDateDifference(new DateTime($dateStart), new DateTime($dateEnd), Date::DATE_DIFFERENCE_UNIT_MINUTES));
    }

    /**
     * @param bool $value The value which maybe is a date
     * @dataProvider provideBooleanValue
     */
    public function testGetDateTimeBoolean($value): void
    {
        self::assertFalse(Date::getDateTime($value));
    }

    public function testGetDateTimeConcreteDates(): void
    {
        // Using the standard date format provided by the tested method
        self::assertInstanceOf(DateTime::class, Date::getDateTime('2015-03-20'));

        // Using custom date format
        self::assertInstanceOf(DateTime::class, Date::getDateTime('2015-03-20 11:30', false, 'Y-m-d H:i'));
        self::assertInstanceOf(DateTime::class, Date::getDateTime('20.03.2015', false, 'd.m.Y'));
    }

    /**
     * @param mixed $value Empty value, e.g. ""
     * @dataProvider provideEmptyValue
     */
    public function testGetDateTimeEmptyValue($value): void
    {
        self::assertFalse(Date::getDateTime($value));
    }

    /**
     * @param mixed $value Incorrect source of DateTime
     * @dataProvider provideIncorrectDateTimeValue
     */
    public function testGetDateTimeIncorrectValue($value): void
    {
        self::assertFalse(Date::getDateTime($value));
    }

    /**
     * @param DateTime $dateTime Instance of DateTime class
     * @dataProvider provideDateTimeInstance
     */
    public function testGetDateTimeInstanceDateTime(DateTime $dateTime): void
    {
        self::assertInstanceOf(DateTime::class, Date::getDateTime($dateTime));
    }

    /**
     * @param string $relativeFormat Relative / compound format of DateTime
     * @dataProvider provideDateTimeRelativeFormat
     */
    public function testGetDateTimeRelativeFormats($relativeFormat): void
    {
        /*
         * Values based on relative / compound formats, but... without explicitly declaring them as compound
         * (2nd argument set to false by default)
         *
         * http://php.net/manual/en/datetime.formats.compound.php
         */
        self::assertFalse(Date::getDateTime($relativeFormat));

        /*
         * Values based on relative / compound formats
         * http://php.net/manual/en/datetime.formats.compound.php
         */
        self::assertInstanceOf(DateTime::class, Date::getDateTime($relativeFormat, true));
    }

    public function testGetDatesCollection(): void
    {
        // 1 date only
        $effect = [
            1 => new DateTime('2017-01-02'),
        ];

        self::assertEquals($effect, Date::getDatesCollection(new DateTime('2017-01-01'), 1));

        // 3 dates with default date interval (days)
        $effect = [
            1 => new DateTime('2017-01-02'),
            2 => new DateTime('2017-01-03'),
            3 => new DateTime('2017-01-04'),
        ];

        self::assertEquals($effect, Date::getDatesCollection(new DateTime('2017-01-01'), 3));

        // 3 dates with custom date interval (hours)
        $effect = [
            1 => new DateTime('2017-01-01 10:30'),
            2 => new DateTime('2017-01-01 11:30'),
            3 => new DateTime('2017-01-01 12:30'),
        ];

        self::assertEquals($effect, Date::getDatesCollection(new DateTime('2017-01-01 09:30'), 3, 'PT%dH'));

        // 3 dates with custom date interval (months)
        $effect = [
            1 => new DateTime('2017-02-01'),
            2 => new DateTime('2017-03-01'),
            3 => new DateTime('2017-04-01'),
        ];

        self::assertEquals($effect, Date::getDatesCollection(new DateTime('2017-01-01'), 3, 'P%dM'));
    }

    public function testGetDatesCollectionInvalidCount(): void
    {
        self::assertEquals([], Date::getDatesCollection(new DateTime(), -1));
    }

    /**
     * @param mixed $invalidInterval Empty value, e.g. ""
     * @dataProvider provideEmptyScalarValue
     */
    public function testGetDatesCollectionInvalidInterval($invalidInterval): void
    {
        self::assertEquals([], Date::getDatesCollection(new DateTime(), 2, $invalidInterval));
        self::assertEquals([], Date::getDatesCollection(new DateTime(), 2, 'lorem'));
        self::assertEquals([], Date::getDatesCollection(new DateTime(), 2, '%d'));
    }

    /** @dataProvider provideCorrectPeriod */
    public function testGetDatesForPeriod(DatePeriodEnum $period, DatePeriod $expected): void
    {
        self::assertEquals($expected, Date::getDatesForPeriod($period));
    }

    /**
     * @param int $year  The year value
     * @param int $month The month value
     * @param int $day   The day value
     *
     * @dataProvider provideYearMonthDay
     */
    public function testGetDayOfWeek(int $year, int $month, int $day): void
    {
        self::assertMatchesRegularExpression('/^[0-6]{1}$/', (string) Date::getDayOfWeek($year, $month, $day));
    }

    /** @dataProvider provideIncorrectYearMonthDay */
    public function testGetDayOfWeekIncorrectValues(
        int $year,
        int $month,
        int $day,
        string $invalidDatePart,
        int $invalidValue,
    ): void {
        $this->expectException(InvalidDatePartException::class);
        $this->expectExceptionMessage('Value of the \''.$invalidDatePart.'\' date part is invalid: '.$invalidValue);

        self::assertEmpty(Date::getDayOfWeek($year, $month, $day));
    }

    /**
     * @param DateTime $startDate The start date. Start of the random date.
     * @param int      $start     Start of random partition
     * @param int      $end       End of random partition
     *
     * @dataProvider provideDataOfRandomDate
     */
    public function testGetRandomDate(DateTime $startDate, $start, $end): void
    {
        $randomDate = Date::getRandomDate($startDate, $start, $end);

        $minDate = clone $startDate;
        $maxDate = clone $startDate;

        $intervalMinDate = $minDate->add(new DateInterval(sprintf('P%dD', $start)));
        $intervalMaxDate = $maxDate->add(new DateInterval(sprintf('P%dD', $end)));

        self::assertTrue($randomDate >= $intervalMinDate && $randomDate <= $intervalMaxDate);
    }

    /**
     * @param DateTime $startDate The start date. Start of the random date.
     * @param int      $start     Start of random partition
     * @param int      $end       End of random partition
     *
     * @dataProvider provideDataOfRandomDateIncorrectEnd
     */
    public function testGetRandomDateIncorrectEnd(DateTime $startDate, $start, $end): void
    {
        $randomDate = Date::getRandomDate($startDate, $start, $end);

        $cloned = clone $startDate;
        $intervalDate = $cloned->add(new DateInterval(sprintf('P%dD', $start)));

        self::assertTrue($randomDate >= $intervalDate && $randomDate <= $intervalDate);
    }

    public function testGetRandomDateUsingDefaults(): void
    {
        $startDate = new DateTime();
        $start = 1;
        $end = 100;

        $minDate = clone $startDate;
        $maxDate = clone $startDate;

        $intervalMinDate = $minDate->add(new DateInterval(sprintf('P%dD', $start)));
        $intervalMaxDate = $maxDate->add(new DateInterval(sprintf('P%dD', $end)));

        $randomDate = Date::getRandomDate();
        self::assertTrue($randomDate >= $intervalMinDate && $randomDate <= $intervalMaxDate);
    }

    /**
     * @param mixed $value Empty value, e.g. ""
     * @dataProvider provideEmptyValue
     */
    public function testIsValidDateEmptyDates($value): void
    {
        self::assertFalse(Date::isValidDate($value));
    }

    /**
     * @param mixed $value Empty source of date format
     * @dataProvider provideEmptyScalarValue
     */
    public function testIsValidDateFormatEmptyFormats($value): void
    {
        self::assertFalse(Date::isValidDateFormat($value));
    }

    /**
     * @param mixed $format Invalid format of date
     * @dataProvider provideInvalidDateFormats
     */
    public function testIsValidDateFormatInvalidFormats($format): void
    {
        self::assertFalse(Date::isValidDateFormat($format));
    }

    public function testIsValidDateFormatValidFormats(): void
    {
        self::assertTrue(Date::isValidDateFormat('Y'));
        self::assertTrue(Date::isValidDateFormat('yy'));
        self::assertTrue(Date::isValidDateFormat('M'));
        self::assertTrue(Date::isValidDateFormat('i'));
        self::assertTrue(Date::isValidDateFormat('l'));
        self::assertTrue(Date::isValidDateFormat('l, d F'));
        self::assertTrue(Date::isValidDateFormat('Y-m-d'));
        self::assertTrue(Date::isValidDateFormat('H:i:s'));
        self::assertTrue(Date::isValidDateFormat('Y/m/d H:i:s'));
    }

    /**
     * @param mixed $value Incorrect source of DateTime
     * @dataProvider provideIncorrectDateTimeValue
     */
    public function testIsValidDateIncorrectDates($value): void
    {
        self::assertFalse(Date::isValidDate($value));
    }

    public function testIsValidDateValidDates(): void
    {
        self::assertTrue(Date::isValidDate('2017-01-01'));
        self::assertTrue(Date::isValidDate('2017-01-01 10:30', true));
        self::assertTrue(Date::isValidDate('2017-01-01 14:00', true));

        self::assertTrue(Date::isValidDate(new DateTime()));
        self::assertTrue(Date::isValidDate(new DateTime('now')));
        self::assertTrue(Date::isValidDate(new DateTime('tomorrow')));
        self::assertTrue(Date::isValidDate(new DateTime('m')));
    }
}
