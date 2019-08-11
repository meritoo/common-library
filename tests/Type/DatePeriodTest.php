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
use Meritoo\Common\Type\Base\BaseType;
use Meritoo\Common\Type\DatePeriod;
use Meritoo\Common\Type\OopVisibilityType;

/**
 * Test case of date's period
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 *
 * @internal
 * @covers    \Meritoo\Common\Type\DatePeriod
 */
class DatePeriodTest extends BaseTypeTestCase
{
    public function testConstructorVisibilityAndArguments(): void
    {
        static::assertConstructorVisibilityAndArguments(
            DatePeriod::class,
            OopVisibilityType::IS_PUBLIC,
            2
        );
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

    /**
     * @param DateTime $startDate (optional) Start date of period
     * @param DateTime $endDate   (optional) End date of period
     *
     * @dataProvider provideDatePeriod
     */
    public function testGettersAndSetters(DateTime $startDate = null, DateTime $endDate = null): void
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
    public function testGetFormattedDateUsingIncorrectDateFormat(DatePeriod $period, $format): void
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
    public function testGetFormattedDate(DatePeriod $period, $format, $startDate, $expected): void
    {
        self::assertEquals($expected, $period->getFormattedDate($format, $startDate));
    }

    /**
     * Provides the start and end date of date period
     *
     * @return Generator
     */
    public function provideDatePeriod(): Generator
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
    public function provideDatePeriodAndIncorrectDateFormat(): Generator
    {
        $startDate = new DateTime('2001-01-01');
        $endDate = new DateTime('2002-02-02');

        yield[
            new DatePeriod($startDate, $endDate),
            '',
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
    public function provideDatePeriodAndDateFormat(): Generator
    {
        $startDate = new DateTime('2001-01-01');
        $endDate = new DateTime('2002-02-02');

        // For start date
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

        // For end date
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
    public function provideTypeToVerify(): Generator
    {
        yield[
            DatePeriod::isCorrectType(''),
            false,
        ];

        yield[
            DatePeriod::isCorrectType('-1'),
            false,
        ];

        yield[
            DatePeriod::isCorrectType('4'),
            true,
        ];

        yield[
            DatePeriod::isCorrectType('3'),
            true,
        ];

        yield[
            DatePeriod::isCorrectType('8'),
            true,
        ];
    }

    /**
     * Returns all expected types of the tested type
     *
     * @return array
     */
    protected function getAllExpectedTypes(): array
    {
        return [
            'LAST_MONTH' => 4,
            'LAST_WEEK'  => 1,
            'LAST_YEAR'  => 7,
            'NEXT_MONTH' => 6,
            'NEXT_WEEK'  => 3,
            'NEXT_YEAR'  => 9,
            'THIS_MONTH' => 5,
            'THIS_WEEK'  => 2,
            'THIS_YEAR'  => 8,
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getTestedTypeInstance(): BaseType
    {
        return new DatePeriod();
    }
}
