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
use Meritoo\Common\Exception\Base\UnknownTypeException;
use Meritoo\Common\Exception\Type\UnknownDatePartTypeException;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Traits\Test\Base\BaseTestCaseTrait;
use Meritoo\Common\Type\Base\BaseType;
use Meritoo\Common\Type\DatePeriod;
use Meritoo\Common\Utilities\Arrays;
use Meritoo\Common\Utilities\Date;
use Meritoo\Common\Utilities\Locale;
use Meritoo\Common\Utilities\Reflection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(Date::class)]
#[UsesClass(BaseType::class)]
#[UsesClass(DatePeriod::class)]
#[UsesClass(Arrays::class)]
#[UsesClass(Reflection::class)]
#[UsesClass(Locale::class)]
#[UsesClass(BaseTestCaseTrait::class)]
#[UsesClass(UnknownTypeException::class)]
#[UsesClass(UnknownDatePartTypeException::class)]
class DateTest extends BaseTestCase
{
    /**
     * Provides correct period
     *
     * @return Generator
     */
    public static function provideCorrectPeriod(): Generator
    {
        yield [
            DatePeriod::LAST_WEEK,
            new DatePeriod(
                (new DateTime('this week'))->sub(new DateInterval('P7D'))->setTime(0, 0, 0),
                (new DateTime('this week'))->sub(new DateInterval('P1D'))->setTime(23, 59, 59)
            ),
        ];

        yield [
            DatePeriod::THIS_WEEK,
            new DatePeriod(
                (new DateTime('this week'))->setTime(0, 0, 0),
                (new DateTime('this week'))->add(new DateInterval('P6D'))->setTime(23, 59, 59)
            ),
        ];

        yield [
            DatePeriod::NEXT_WEEK,
            new DatePeriod(
                (new DateTime('this week'))->add(new DateInterval('P7D'))->setTime(0, 0, 0),
                (new DateTime('this week'))->add(new DateInterval('P7D'))
                    ->add(new DateInterval('P6D'))
                    ->setTime(23, 59, 59)
            ),
        ];

        yield [
            DatePeriod::LAST_MONTH,
            new DatePeriod(
                (new DateTime('first day of last month'))->setTime(0, 0, 0),
                (new DateTime('last day of last month'))->setTime(23, 59, 59)
            ),
        ];

        yield [
            DatePeriod::THIS_MONTH,
            new DatePeriod(
                Date::getDatesForPeriod(DatePeriod::LAST_MONTH)
                    ->getEndDate()
                    ->add(new DateInterval('P1D'))
                    ->setTime(0, 0, 0),
                Date::getDatesForPeriod(DatePeriod::NEXT_MONTH)
                    ->getStartDate()
                    ->sub(new DateInterval('P1D'))
                    ->setTime(23, 59, 59)
            ),
        ];

        yield [
            DatePeriod::NEXT_MONTH,
            new DatePeriod(
                (new DateTime('first day of next month'))->setTime(0, 0, 0),
                (new DateTime('last day of next month'))->setTime(23, 59, 59)
            ),
        ];

        $lastYearStart = (new DateTime())->modify('-1 year');
        $lastYearEnd = (new DateTime())->modify('-1 year');
        $year = $lastYearStart->format('Y');

        yield [
            DatePeriod::LAST_YEAR,
            new DatePeriod(
                $lastYearStart->setDate($year, 1, 1)->setTime(0, 0, 0),
                $lastYearEnd->setDate($year, 12, 31)->setTime(23, 59, 59)
            ),
        ];

        $year = (new DateTime())->format('Y');

        yield [
            DatePeriod::THIS_YEAR,
            new DatePeriod(
                (new DateTime())->setDate($year, 1, 1)->setTime(0, 0, 0),
                (new DateTime())->setDate($year, 12, 31)->setTime(23, 59, 59)
            ),
        ];

        $nextYearStart = (new DateTime())->modify('1 year');
        $nextYearEnd = (new DateTime())->modify('1 year');
        $year = $nextYearStart->format('Y');

        yield [
            DatePeriod::NEXT_YEAR,
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
    public static function provideDataOfRandomDate(): Generator
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
    public static function provideDataOfRandomDateIncorrectEnd(): Generator
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
    public static function provideEmptyDatesForDateDifference(): Generator
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
    public static function provideIncorrectDateTimeValue(): Generator
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
     * Provides incorrect period
     *
     * @return Generator
     */
    public static function provideIncorrectPeriod(): Generator
    {
        yield [-1];
        yield [0];
        yield [10];
    }

    /**
     * Provides incorrect values of year, month and day
     *
     * @return Generator
     */
    public static function provideIncorrectYearMonthDay(): Generator
    {
        yield [
            0,
            0,
            0,
        ];

        yield [
            -1,
            -1,
            -1,
        ];

        yield [
            5000,
            50,
            50,
        ];

        yield [
            2000,
            13,
            01,
        ];

        yield [
            2000,
            01,
            40,
        ];
    }

    /**
     * Provides invalid format of date
     *
     * @return Generator
     */
    public static function provideInvalidDateFormats(): Generator
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
    public static function provideYearMonthDay(): Generator
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

    #[DataProvider('provideEmptyScalarValue')]
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

    #[DataProvider('provideEmptyDatesForDateDifference')]
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

    #[DataProvider('provideBooleanValue')]
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

    #[DataProvider('provideEmptyValue')]
    public function testGetDateTimeEmptyValue($value): void
    {
        self::assertFalse(Date::getDateTime($value));
    }

    #[DataProvider('provideIncorrectDateTimeValue')]
    public function testGetDateTimeIncorrectValue($value): void
    {
        self::assertFalse(Date::getDateTime($value));
    }

    #[DataProvider('provideDateTimeInstance')]
    public function testGetDateTimeInstanceDateTime(\DateTime $dateTime): void
    {
        self::assertInstanceOf(DateTime::class, Date::getDateTime($dateTime));
    }

    #[DataProvider('provideDateTimeRelativeFormat')]
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

    #[DataProvider('provideEmptyScalarValue')]
    public function testGetDatesCollectionInvalidInterval($invalidInterval): void
    {
        self::assertEquals([], Date::getDatesCollection(new DateTime(), 2, $invalidInterval));
        self::assertEquals([], Date::getDatesCollection(new DateTime(), 2, 'lorem'));
        self::assertEquals([], Date::getDatesCollection(new DateTime(), 2, '%d'));
    }

    #[DataProvider('provideCorrectPeriod')]
    public function testGetDatesForPeriod($period, DatePeriod $expected): void
    {
        self::assertEquals($expected, Date::getDatesForPeriod($period));
    }

    public function testGetDatesForPeriodUsingEmptyString(): void
    {
        self::assertNull(Date::getDatesForPeriod(''));
    }

    #[DataProvider('provideIncorrectPeriod')]
    public function testGetDatesForPeriodUsingIncorrectPeriod($period): void
    {
        self::assertNull(Date::getDatesForPeriod($period));
    }

    #[DataProvider('provideYearMonthDay')]
    public function testGetDayOfWeek(int $year, int $month, int $day): void
    {
        self::assertMatchesRegularExpression('/^[0-6]{1}$/', (string) Date::getDayOfWeek($year, $month, $day));
    }

    #[DataProvider('provideIncorrectYearMonthDay')]
    public function testGetDayOfWeekIncorrectValues(int $year, int $month, int $day): void
    {
        $this->expectException(UnknownDatePartTypeException::class);
        self::assertEmpty(Date::getDayOfWeek($year, $month, $day));
    }

    #[DataProvider('provideDataOfRandomDate')]
    public function testGetRandomDate(\DateTime $startDate, $start, $end): void
    {
        $randomDate = Date::getRandomDate($startDate, $start, $end);

        $minDate = clone $startDate;
        $maxDate = clone $startDate;

        $intervalMinDate = $minDate->add(new DateInterval(sprintf('P%dD', $start)));
        $intervalMaxDate = $maxDate->add(new DateInterval(sprintf('P%dD', $end)));

        self::assertTrue($randomDate >= $intervalMinDate && $randomDate <= $intervalMaxDate);
    }

    #[DataProvider('provideDataOfRandomDateIncorrectEnd')]
    public function testGetRandomDateIncorrectEnd(\DateTime $startDate, $start, $end): void
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

    #[DataProvider('provideEmptyValue')]
    public function testIsValidDateEmptyDates($value): void
    {
        self::assertFalse(Date::isValidDate($value));
    }

    #[DataProvider('provideEmptyScalarValue')]
    public function testIsValidDateFormatEmptyFormats($value): void
    {
        self::assertFalse(Date::isValidDateFormat($value));
    }

    #[DataProvider('provideInvalidDateFormats')]
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

    #[DataProvider('provideIncorrectDateTimeValue')]
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
