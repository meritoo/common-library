<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Tests\Utilities;

use DateInterval;
use DateTime;
use Generator;
use Meritoo\Common\Exception\Date\IncorrectDatePartException;
use Meritoo\Common\Utilities\Date;
use Meritoo\Common\Utilities\TestCase;

/**
 * Tests of the Date methods (only static functions)
 *
 * @author    Krzysztof Niziol <krzysztof.niziol@meritoo.pl>
 * @copyright Meritoo.pl
 */
class DateTest extends TestCase
{
    /**
     * @param mixed $value Empty value, e.g. ""
     * @dataProvider provideEmptyValue
     */
    public function testGetDateTimeEmptyValue($value)
    {
        self::assertFalse(Date::getDateTime($value));
    }

    /**
     * @param mixed $value Incorrect source of DateTime
     * @dataProvider provideIncorrectDateTimeValue
     */
    public function testGetDateTimeIncorrectValue($value)
    {
        self::assertFalse(Date::getDateTime($value));
    }

    /**
     * @param bool $value The value which maybe is a date
     * @dataProvider provideBooleanValue
     */
    public function testGetDateTimeBoolean($value)
    {
        self::assertFalse(Date::getDateTime($value));
    }

    /**
     * @param string $relativeFormat Relative / compound format of DateTime
     * @dataProvider provideDateTimeRelativeFormat
     */
    public function testGetDateTimeRelativeFormats($relativeFormat)
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

    /**
     * @param DateTime $dateTime Instance of DateTime class
     * @dataProvider provideDateTimeInstance
     */
    public function testGetDateTimeInstanceDateTime(DateTime $dateTime)
    {
        self::assertInstanceOf(DateTime::class, Date::getDateTime($dateTime));
    }

    public function testGetDateTimeConcreteDates()
    {
        /*
         * Using the standard date format provided by the tested method
         */
        self::assertInstanceOf(DateTime::class, Date::getDateTime('2015-03-20'));

        /*
         * Using custom date format
         */
        self::assertInstanceOf(DateTime::class, Date::getDateTime('2015-03-20 11:30', false, 'Y-m-d H:i'));
        self::assertInstanceOf(DateTime::class, Date::getDateTime('20.03.2015', false, 'd.m.Y'));
    }

    /**
     * @param mixed $value Empty value, e.g. ""
     * @dataProvider provideEmptyValue
     */
    public function testIsValidDateEmptyDates($value)
    {
        self::assertFalse(Date::isValidDate($value));
    }

    /**
     * @param mixed $value Incorrect source of DateTime
     * @dataProvider provideIncorrectDateTimeValue
     */
    public function testIsValidDateIncorrectDates($value)
    {
        self::assertFalse(Date::isValidDate($value));
    }

    public function testIsValidDateValidDates()
    {
        self::assertTrue(Date::isValidDate('2017-01-01'));
        self::assertTrue(Date::isValidDate('2017-01-01 10:30', true));
        self::assertTrue(Date::isValidDate('2017-01-01 14:00', true));

        self::assertTrue(Date::isValidDate(new DateTime()));
        self::assertTrue(Date::isValidDate(new DateTime('now')));
        self::assertTrue(Date::isValidDate(new DateTime('tomorrow')));
        self::assertTrue(Date::isValidDate(new DateTime('m')));
    }

    /**
     * @param mixed $value Empty source of date format
     * @dataProvider provideEmptyValue
     */
    public function testIsValidDateFormatEmptyFormats($value)
    {
        self::assertFalse(Date::isValidDateFormat($value));
    }

    /**
     * @param mixed $format Invalid format of date
     * @dataProvider provideInvalidDateFormats
     */
    public function testIsValidDateFormatInvalidFormats($format)
    {
        self::assertFalse(Date::isValidDateFormat($format));
    }

    public function testIsValidDateFormatValidFormats()
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
     * @param mixed $value Empty value, e.g. ""
     * @dataProvider provideEmptyValue
     */
    public function testGenerateRandomTimeEmptyFormat($value)
    {
        self::assertNull(Date::generateRandomTime($value));
    }

    public function testGenerateRandomTimeIncorrectFormat()
    {
        self::assertNull(Date::generateRandomTime(','));
        self::assertNull(Date::generateRandomTime(';'));
        self::assertNull(Date::generateRandomTime('|'));
        self::assertNull(Date::generateRandomTime('?'));
    }

    public function testGenerateRandomTimeDefaultFormat()
    {
        self::assertRegExp('/\d{2}:\d{2}:\d{2}/', Date::generateRandomTime());
    }

    public function testGenerateRandomTimeCustomFormat()
    {
        self::assertRegExp('/^0[1-9]{1}|1[0-2]{1}$/', Date::generateRandomTime('h')); // 01 through 12
        self::assertRegExp('/^[0-5]?[0-9]$/', Date::generateRandomTime('i')); // 00 through 59
        self::assertRegExp('/^[0-5]?[0-9]$/', Date::generateRandomTime('s')); // 00 through 59

        self::assertRegExp('/^\d{2}:\d{2}$/', Date::generateRandomTime('H:i'));
        self::assertRegExp('/^[1-9]|1[0-2]:\d{2}$/', Date::generateRandomTime('g:i'));
    }

    public function testGetCurrentDayOfWeek()
    {
        self::assertRegExp('/^[0-6]{1}$/', (string)Date::getCurrentDayOfWeek());
    }

    public function testGetCurrentDayOfWeekName()
    {
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

        self::assertRegExp($pattern, Date::getCurrentDayOfWeekName());
    }

    /**
     * @param int $year  The year value
     * @param int $month The month value
     * @param int $day   The day value
     *
     * @dataProvider provideIncorrectYearMonthDay
     */
    public function testGetDayOfWeekIncorrectValues($year, $month, $day)
    {
        $this->expectException(IncorrectDatePartException::class);
        self::assertEmpty(Date::getDayOfWeek($year, $month, $day));
    }

    /**
     * @param int $year  The year value
     * @param int $month The month value
     * @param int $day   The day value
     *
     * @dataProvider provideYearMonthDay
     */
    public function testGetDayOfWeek($year, $month, $day)
    {
        self::assertRegExp('/^[0-6]{1}$/', (string)Date::getDayOfWeek($year, $month, $day));
    }

    /**
     * @param string|DateTime $dateStart The start date
     * @param string|DateTime $dateEnd   The end date
     *
     * @dataProvider provideEmptyDatesForDateDifference
     */
    public function testGetDateDifferenceEmptyDates($dateStart, $dateEnd)
    {
        self::assertNull(Date::getDateDifference($dateStart, $dateEnd));
    }

    public function testGetDateDifferenceInvalidDates()
    {
        self::assertNull(Date::getDateDifference('2017-01-40', '2017-13-01'));
        self::assertNull(Date::getDateDifference('xyz', 'lorem'));
    }

    public function testGetDateDifferenceOneDay()
    {
        /*
         * Difference of 1 day
         */
        $dateStart = '2017-01-01';
        $dateEnd = '2017-01-02';

        $effect = [
            Date::DATE_DIFFERENCE_UNIT_YEARS   => 0,
            Date::DATE_DIFFERENCE_UNIT_MONTHS  => 0,
            Date::DATE_DIFFERENCE_UNIT_DAYS    => 1,
            Date::DATE_DIFFERENCE_UNIT_HOURS   => 0,
            Date::DATE_DIFFERENCE_UNIT_MINUTES => 0,
        ];

        self::assertEquals($effect, Date::getDateDifference($dateStart, $dateEnd));
        self::assertEquals($effect, Date::getDateDifference(new DateTime($dateStart), new DateTime($dateEnd)));

        self::assertEquals(0, Date::getDateDifference($dateStart, $dateEnd, Date::DATE_DIFFERENCE_UNIT_YEARS));
        self::assertEquals(0, Date::getDateDifference(new DateTime($dateStart), new DateTime($dateEnd), Date::DATE_DIFFERENCE_UNIT_YEARS));

        self::assertEquals(1, Date::getDateDifference($dateStart, $dateEnd, Date::DATE_DIFFERENCE_UNIT_DAYS));
        self::assertEquals(1, Date::getDateDifference(new DateTime($dateStart), new DateTime($dateEnd), Date::DATE_DIFFERENCE_UNIT_DAYS));

        /*
         * Difference of 1 day (using the relative date format)
         */
        $effect = [
            Date::DATE_DIFFERENCE_UNIT_YEARS   => 0,
            Date::DATE_DIFFERENCE_UNIT_MONTHS  => 0,
            Date::DATE_DIFFERENCE_UNIT_DAYS    => 1,
            Date::DATE_DIFFERENCE_UNIT_HOURS   => 0,
            Date::DATE_DIFFERENCE_UNIT_MINUTES => 0,
        ];

        self::assertEquals($effect, Date::getDateDifference(new DateTime('yesterday'), new DateTime('midnight')));
        self::assertEquals(1, Date::getDateDifference(new DateTime('yesterday'), new DateTime('midnight'), Date::DATE_DIFFERENCE_UNIT_DAYS));
        self::assertEquals(0, Date::getDateDifference(new DateTime('yesterday'), new DateTime('midnight'), Date::DATE_DIFFERENCE_UNIT_HOURS));
    }

    public function testGetDateDifferenceOneDayTwoHours()
    {
        /*
         * Difference of 1 day, 2 hours and 15 minutes
         */
        $dateStart = '2017-01-01 12:00';
        $dateEnd = '2017-01-02 14:15';

        $effect = [
            Date::DATE_DIFFERENCE_UNIT_YEARS   => 0,
            Date::DATE_DIFFERENCE_UNIT_MONTHS  => 0,
            Date::DATE_DIFFERENCE_UNIT_DAYS    => 1,
            Date::DATE_DIFFERENCE_UNIT_HOURS   => 2,
            Date::DATE_DIFFERENCE_UNIT_MINUTES => 15,
        ];

        self::assertEquals($effect, Date::getDateDifference($dateStart, $dateEnd));
        self::assertEquals($effect, Date::getDateDifference(new DateTime($dateStart), new DateTime($dateEnd)));

        self::assertEquals(0, Date::getDateDifference($dateStart, $dateEnd, Date::DATE_DIFFERENCE_UNIT_YEARS));
        self::assertEquals(0, Date::getDateDifference(new DateTime($dateStart), new DateTime($dateEnd), Date::DATE_DIFFERENCE_UNIT_YEARS));

        self::assertEquals(2, Date::getDateDifference($dateStart, $dateEnd, Date::DATE_DIFFERENCE_UNIT_HOURS));
        self::assertEquals(2, Date::getDateDifference(new DateTime($dateStart), new DateTime($dateEnd), Date::DATE_DIFFERENCE_UNIT_HOURS));

        self::assertEquals(15, Date::getDateDifference($dateStart, $dateEnd, Date::DATE_DIFFERENCE_UNIT_MINUTES));
        self::assertEquals(15, Date::getDateDifference(new DateTime($dateStart), new DateTime($dateEnd), Date::DATE_DIFFERENCE_UNIT_MINUTES));
    }

    public function testGetDateDifferenceOneMonthFortyOneDays()
    {
        /*
         * Difference of 1 month, 41 days, 4 hours and 30 minutes
         */
        $dateStart = '2017-01-01 12:00';
        $dateEnd = '2017-02-11 16:30';

        $effect = [
            Date::DATE_DIFFERENCE_UNIT_YEARS   => 0,
            Date::DATE_DIFFERENCE_UNIT_MONTHS  => 1,
            Date::DATE_DIFFERENCE_UNIT_DAYS    => 41,
            Date::DATE_DIFFERENCE_UNIT_HOURS   => 4,
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

        self::assertEquals(30, Date::getDateDifference($dateStart, $dateEnd, Date::DATE_DIFFERENCE_UNIT_MINUTES));
        self::assertEquals(30, Date::getDateDifference(new DateTime($dateStart), new DateTime($dateEnd), Date::DATE_DIFFERENCE_UNIT_MINUTES));
    }

    public function testGetDateDifferenceNoDifference()
    {
        /*
         * No difference
         */
        $dateStart = '2017-01-01 12:00';
        $dateEnd = $dateStart;

        $effect = [
            Date::DATE_DIFFERENCE_UNIT_YEARS   => 0,
            Date::DATE_DIFFERENCE_UNIT_MONTHS  => 0,
            Date::DATE_DIFFERENCE_UNIT_DAYS    => 0,
            Date::DATE_DIFFERENCE_UNIT_HOURS   => 0,
            Date::DATE_DIFFERENCE_UNIT_MINUTES => 0,
        ];

        self::assertEquals($effect, Date::getDateDifference($dateStart, $dateEnd));
        self::assertEquals($effect, Date::getDateDifference(new DateTime(), new DateTime()));

        self::assertEquals(0, Date::getDateDifference($dateStart, $dateEnd, Date::DATE_DIFFERENCE_UNIT_YEARS));
        self::assertEquals(0, Date::getDateDifference(new DateTime(), new DateTime(), Date::DATE_DIFFERENCE_UNIT_YEARS));

        self::assertEquals(0, Date::getDateDifference($dateStart, $dateEnd, Date::DATE_DIFFERENCE_UNIT_MONTHS));
        self::assertEquals(0, Date::getDateDifference(new DateTime(), new DateTime(), Date::DATE_DIFFERENCE_UNIT_MONTHS));

        self::assertEquals(0, Date::getDateDifference($dateStart, $dateEnd, Date::DATE_DIFFERENCE_UNIT_MINUTES));
        self::assertEquals(0, Date::getDateDifference(new DateTime(), new DateTime(), Date::DATE_DIFFERENCE_UNIT_MINUTES));
    }

    /**
     * @param mixed $invalidCount Empty value, e.g. ""
     * @dataProvider provideEmptyValue
     */
    public function testGetDatesCollectionInvalidCount($invalidCount)
    {
        self::assertEquals([], Date::getDatesCollection(new DateTime(), $invalidCount));
        self::assertEquals([], Date::getDatesCollection(new DateTime(), -1));
    }

    /**
     * @param mixed $invalidInterval Empty value, e.g. ""
     * @dataProvider provideEmptyValue
     */
    public function testGetDatesCollectionInvalidInterval($invalidInterval)
    {
        self::assertEquals([], Date::getDatesCollection(new DateTime(), 2, $invalidInterval));
        self::assertEquals([], Date::getDatesCollection(new DateTime(), 2, 'lorem'));
        self::assertEquals([], Date::getDatesCollection(new DateTime(), 2, '%d'));
    }

    public function testGetDatesCollection()
    {
        /*
         * 1 date only
         */
        $effect = [
            1 => new DateTime('2017-01-02'),
        ];

        self::assertEquals($effect, Date::getDatesCollection(new DateTime('2017-01-01'), 1));

        /*
         * 3 dates with default date interval (days)
         */
        $effect = [
            1 => new DateTime('2017-01-02'),
            2 => new DateTime('2017-01-03'),
            3 => new DateTime('2017-01-04'),
        ];

        self::assertEquals($effect, Date::getDatesCollection(new DateTime('2017-01-01'), 3));

        /*
         * 3 dates with custom date interval (hours)
         */
        $effect = [
            1 => new DateTime('2017-01-01 10:30'),
            2 => new DateTime('2017-01-01 11:30'),
            3 => new DateTime('2017-01-01 12:30'),
        ];

        self::assertEquals($effect, Date::getDatesCollection(new DateTime('2017-01-01 09:30'), 3, 'PT%dH'));

        /*
         * 3 dates with custom date interval (months)
         */
        $effect = [
            1 => new DateTime('2017-02-01'),
            2 => new DateTime('2017-03-01'),
            3 => new DateTime('2017-04-01'),
        ];

        self::assertEquals($effect, Date::getDatesCollection(new DateTime('2017-01-01'), 3, 'P%dM'));
    }

    public function testGetRandomDateUsingDefaults()
    {
        $startDate = new DateTime();
        $start = 1;
        $end = 100;

        $intervalMinDate = (clone $startDate)->add(new DateInterval(sprintf('P%dD', $start)));
        $intervalMaxDate = (clone $startDate)->add(new DateInterval(sprintf('P%dD', $end)));

        $randomDate = Date::getRandomDate();
        self::assertTrue($randomDate >= $intervalMinDate && $randomDate <= $intervalMaxDate);
    }

    /**
     * @param DateTime $startDate The start date. Start of the random date.
     * @param int      $start     Start of random partition
     * @param int      $end       End of random partition
     *
     * @dataProvider provideDataOfRandomDateIncorrectEnd
     */
    public function testGetRandomDateIncorrectEnd(DateTime $startDate, $start, $end)
    {
        $randomDate = Date::getRandomDate($startDate, $start, $end);
        $intervalDate = (clone $startDate)->add(new DateInterval(sprintf('P%dD', $start)));

        self::assertTrue($randomDate >= $intervalDate && $randomDate <= $intervalDate);
    }

    /**
     * @param DateTime $startDate The start date. Start of the random date.
     * @param int      $start     Start of random partition
     * @param int      $end       End of random partition
     *
     * @dataProvider provideDataOfRandomDate
     */
    public function testGetRandomDate(DateTime $startDate, $start, $end)
    {
        $randomDate = Date::getRandomDate($startDate, $start, $end);

        $intervalMinDate = (clone $startDate)->add(new DateInterval(sprintf('P%dD', $start)));
        $intervalMaxDate = (clone $startDate)->add(new DateInterval(sprintf('P%dD', $end)));

        self::assertTrue($randomDate >= $intervalMinDate && $randomDate <= $intervalMaxDate);
    }

    /**
     * Provides incorrect invalidCount of DateTime
     *
     * @return Generator
     */
    public function provideIncorrectDateTimeValue()
    {
        /*
         * Incorrect one-character values
         */
        yield['a'];
        yield['m'];

        /*
         * Incorrect strings
         */
        yield['ss'];
        yield['sss'];
        yield['mm'];
        yield['yy'];
        yield['yyyy'];

        /*
         * Incorrect integer values
         */
        yield[1];
        yield[10];
        yield[15];
        yield[100];
        yield[1000];

        /*
         * Incorrect string / numeric values
         */
        yield['1'];
        yield['10'];
        yield['15'];
        yield['100'];
        yield['1000'];

        /*
         * Incorrect dates
         */
        yield['0-0-0'];
        yield['20-01-01'];
        yield['2015-0-0'];
        yield['2015-00-00'];
        yield['2015-16-01'];
    }

    /**
     * Provides invalid format of date
     *
     * @return Generator
     */
    public function provideInvalidDateFormats()
    {
        yield[0];
        yield[9];
        yield['[]'];
        yield['invalid'];
        yield['Q'];
        yield[','];
        yield['.'];
        yield['aa###'];
        yield['Y/m/d H:i:invalid'];
    }

    /**
     * Provide empty dates for date difference
     *
     * @return Generator
     */
    public function provideEmptyDatesForDateDifference()
    {
        yield[
            null,
            null,
        ];

        yield[
            '',
            '',
        ];

        yield[
            null,
            new DateTime(),
        ];

        yield[
            new DateTime(),
            null,
        ];
    }

    /**
     * Provides incorrect values of year, month and day
     *
     * @return Generator
     */
    public function provideIncorrectYearMonthDay()
    {
        yield[
            null,
            null,
            null,
        ];

        yield[
            '',
            '',
            '',
        ];

        yield[
            0,
            0,
            0,
        ];

        yield[
            -1,
            -1,
            -1,
        ];

        yield[
            5000,
            50,
            50,
        ];

        yield[
            2000,
            13,
            01,
        ];

        yield[
            2000,
            01,
            40,
        ];
    }

    /**
     * Provides values of year, month and day
     *
     * @return Generator
     */
    public function provideYearMonthDay()
    {
        yield[
            2000,
            01,
            01,
        ];

        yield[
            2000,
            1,
            1,
        ];

        yield[
            2000,
            2,
            2,
        ];

        yield[
            2000,
            6,
            1,
        ];

        yield[
            2000,
            12,
            01,
        ];

        yield[
            2000,
            12,
            1,
        ];

        yield[
            2000,
            12,
            31,
        ];
    }

    /**
     * Provides data for the random date with incorrect end of random partition
     *
     * @return Generator
     */
    public function provideDataOfRandomDateIncorrectEnd()
    {
        yield[
            new DateTime('2000-01-01'),
            100,
            1,
        ];
    }

    /**
     * Provides data for the random date
     *
     * @return Generator
     */
    public function provideDataOfRandomDate()
    {
        yield[
            new DateTime('2000-01-01'),
            1,
            100,
        ];

        yield[
            new DateTime('2000-12-01'),
            1,
            100,
        ];
        yield[
            new DateTime('2000-01-01'),
            '1',
            '100',
        ];

        yield[
            new DateTime('2000-12-01'),
            '1',
            '100',
        ];

        yield[
            new DateTime('2000-01-01'),
            10,
            50,
        ];

        yield[
            new DateTime('2000-12-01'),
            10,
            50,
        ];
    }
}
