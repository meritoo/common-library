<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Utilities;

use DateInterval;
use DateTime;
use Exception;
use Meritoo\Common\Enums\Date\DatePart;
use Meritoo\Common\Enums\Date\DatePeriod as DatePeriodEnum;
use Meritoo\Common\Exception\Date\InvalidDatePartException;
use Meritoo\Common\ValueObject\DatePeriod;

/**
 * Useful date methods
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class Date
{
    public const DATE_FORMAT = 'Y-m-d';
    public const TIME_FORMAT = 'H:i:s';

    /**
     * The 'days' unit of date difference.
     * Difference between dates in days.
     *
     * @var string
     */
    public const DATE_DIFFERENCE_UNIT_DAYS = 'days';

    /**
     * The 'hours' unit of date difference.
     * Difference between dates in hours.
     *
     * @var string
     */
    public const DATE_DIFFERENCE_UNIT_HOURS = 'hours';

    /**
     * The 'minutes' unit of date difference.
     * Difference between dates in minutes.
     *
     * @var string
     */
    public const DATE_DIFFERENCE_UNIT_MINUTES = 'minutes';

    /**
     * The 'months' unit of date difference.
     * Difference between dates in months.
     *
     * @var string
     */
    public const DATE_DIFFERENCE_UNIT_MONTHS = 'months';

    /**
     * The 'years' unit of date difference.
     * Difference between dates in years.
     *
     * @var string
     */
    public const DATE_DIFFERENCE_UNIT_YEARS = 'years';

    /**
     * Generates and returns random time (the hour, minute and second values)
     *
     * @param string $format (optional) Format of returned value. A string acceptable by the DateTime::format()
     *                       method.
     * @return null|string
     */
    public static function generateRandomTime(string $format = self::TIME_FORMAT): ?string
    {
        $dateTime = new DateTime();

        /*
         * Format is empty or incorrect?
         * Nothing to do
         */
        if (empty($format) || $dateTime->format($format) === $format) {
            return null;
        }

        $hours = [];
        $minutes = [];
        $seconds = [];

        for ($i = 1; $i <= 23; ++$i) {
            $hours[] = $i;
        }

        for ($i = 1; $i <= 59; ++$i) {
            $minutes[] = $i;
        }

        for ($i = 1; $i <= 59; ++$i) {
            $seconds[] = $i;
        }

        // Prepare random time (hour, minute and second)
        $hour = $hours[array_rand($hours)];
        $minute = $minutes[array_rand($minutes)];
        $second = $seconds[array_rand($seconds)];

        return $dateTime
            ->setTime($hour, $minute, $second)
            ->format($format)
        ;
    }

    /**
     * Returns current day of week
     *
     * @return int
     * @throws InvalidDatePartException
     */
    public static function getCurrentDayOfWeek(): int
    {
        $now = new DateTime();

        $year = (int) $now->format('Y');
        $month = (int) $now->format('m');
        $day = (int) $now->format('d');

        return self::getDayOfWeek($year, $month, $day);
    }

    /**
     * Returns based on locale name of current weekday
     *
     * @return string
     */
    public static function getCurrentDayOfWeekName(): string
    {
        $now = new DateTime();

        $year = (int) $now->format('Y');
        $month = (int) $now->format('m');
        $day = (int) $now->format('d');

        return self::getDayOfWeekName($year, $month, $day);
    }

    /**
     * Returns difference between given dates.
     *
     * The difference is calculated in units based on the 3rd argument or all available unit of date difference
     * (defined as DATE_DIFFERENCE_UNIT_* constants of this class).
     *
     * The difference is also whole / complete value for given unit instead of relative value as may be received by
     * DateTime::diff() method, e.g.:
     * - 2 days, 50 hours
     * instead of
     * - 2 days, 2 hours
     *
     * If the unit of date difference is null, all units are returned in array (units are keys of the array).
     * Otherwise - one, integer value is returned.
     *
     * @param DateTime|string $dateStart      The start date
     * @param DateTime|string $dateEnd        The end date
     * @param string|null     $differenceUnit (optional) Unit of date difference. One of this class
     *                                        DATE_DIFFERENCE_UNIT_* constants. If is set to null all units are
     *                                        returned as array.
     * @return array|int
     */
    public static function getDateDifference($dateStart, $dateEnd, string $differenceUnit = null)
    {
        $validDateStart = self::isValidDate($dateStart, true);
        $validDateEnd = self::isValidDate($dateEnd, true);

        /*
         * The start or end date is unknown?
         * or
         * The start or end date is not valid date?
         *
         * Nothing to do
         */
        if (empty($dateStart) || empty($dateEnd) || !$validDateStart || !$validDateEnd) {
            return null;
        }

        $start = self::getDateTime($dateStart, true);
        $end = self::getDateTime($dateEnd, true);

        $difference = [];
        $dateDiff = $end->getTimestamp() - $start->getTimestamp();

        $daysInSeconds = 0;
        $hoursInSeconds = 0;

        $hourSeconds = 60 * 60;
        $daySeconds = $hourSeconds * 24;

        /*
         * These units are related, because while calculating difference in the lowest unit, difference in the highest
         * unit is required, e.g. while calculating hours I have to know difference in days
         */
        $relatedUnits = [
            self::DATE_DIFFERENCE_UNIT_DAYS,
            self::DATE_DIFFERENCE_UNIT_HOURS,
            self::DATE_DIFFERENCE_UNIT_MINUTES,
        ];

        $differenceYear = self::DATE_DIFFERENCE_UNIT_YEARS === $differenceUnit;
        $differenceMonth = self::DATE_DIFFERENCE_UNIT_MONTHS === $differenceUnit;
        $differenceMinutes = self::DATE_DIFFERENCE_UNIT_MINUTES === $differenceUnit;

        if (null === $differenceUnit || $differenceYear) {
            $diff = $end->diff($start);

            // Difference between dates in years should be returned only?
            if ($differenceYear) {
                return $diff->y;
            }

            $difference[self::DATE_DIFFERENCE_UNIT_YEARS] = $diff->y;
        }

        if (null === $differenceUnit || $differenceMonth) {
            $diff = $end->diff($start);

            // Difference between dates in months should be returned only?
            if ($differenceMonth) {
                return $diff->m;
            }

            $difference[self::DATE_DIFFERENCE_UNIT_MONTHS] = $diff->m;
        }

        if (null === $differenceUnit || in_array($differenceUnit, $relatedUnits, true)) {
            $days = (int) floor($dateDiff / $daySeconds);

            // Difference between dates in days should be returned only?
            if (self::DATE_DIFFERENCE_UNIT_DAYS === $differenceUnit) {
                return $days;
            }

            // All units should be returned?
            if (null === $differenceUnit) {
                $difference[self::DATE_DIFFERENCE_UNIT_DAYS] = $days;
            }

            // Calculation for later usage
            $daysInSeconds = $days * $daySeconds;
        }

        if (null === $differenceUnit || in_array($differenceUnit, $relatedUnits, true)) {
            $hours = (int) floor(($dateDiff - $daysInSeconds) / $hourSeconds);

            // Difference between dates in hours should be returned only?
            if (self::DATE_DIFFERENCE_UNIT_HOURS === $differenceUnit) {
                return $hours;
            }

            // All units should be returned?
            if (null === $differenceUnit) {
                $difference[self::DATE_DIFFERENCE_UNIT_HOURS] = $hours;
            }

            // Calculation for later usage
            $hoursInSeconds = $hours * $hourSeconds;
        }

        if (null === $differenceUnit || $differenceMinutes) {
            $minutes = (int) floor(($dateDiff - $daysInSeconds - $hoursInSeconds) / 60);

            // Difference between dates in minutes should be returned only?
            if ($differenceMinutes) {
                return $minutes;
            }

            $difference[self::DATE_DIFFERENCE_UNIT_MINUTES] = $minutes;
        }

        return $difference;
    }

    /**
     * Returns the DateTime object for given value.
     * If the DateTime object cannot be created, false is returned.
     *
     * @param mixed  $value                The value which maybe is a date
     * @param bool   $allowCompoundFormats (optional) If is set to true, the compound formats used to create an
     *                                     instance of DateTime class are allowed (e.g. "now", "last day of next
     *                                     month", "yyyy"). Otherwise - not and every incorrect value is refused
     *                                     (default behaviour).
     * @param string $dateFormat           (optional) Format of date used to verify if given value is actually a date.
     *                                     It should match given value, e.g. "Y-m-d H:i" for "2015-01-01 10:00" value.
     *                                     Default: "Y-m-d".
     * @return bool|DateTime
     */
    public static function getDateTime(
        $value,
        bool $allowCompoundFormats = false,
        string $dateFormat = self::DATE_FORMAT
    ) {
        /*
         * Empty value?
         * Nothing to do :)
         */
        if (empty($value)) {
            return false;
        }

        /*
         * Instance of DateTime class?
         * Nothing to do :)
         */
        if ($value instanceof DateTime) {
            return $value;
        }

        try {
            try {
                /*
                 * Pass the value to the constructor. Maybe it's one of the allowed relative formats.
                 * Examples: "now", "last day of next month"
                 */
                $date = new DateTime($value);

                /*
                 * Instance of the DateTime class was created.
                 * Let's verify if given value is really proper date.
                 */
                $dateFromFormat = DateTime::createFromFormat($dateFormat, $value);

                if (false === $dateFromFormat) {
                    /*
                     * Nothing to do more, because:
                     * 1. Instance of the DateTime was created
                     * and
                     * 2. If createFromFormat() method failed, given value is one of the allowed relative formats
                     * ("now", "last day of next month")
                     * and...
                     */
                    if ($allowCompoundFormats) {
                        /*
                         * ...and
                         * c) it's not an integer, e.g. not 10 or 100 or 1000
                         */
                        if (!is_numeric($value)) {
                            return $date;
                        }
                    } else {
                        $specialFormats = [
                            'now',
                        ];

                        /*
                         * ...and
                         * c) it's special compound format that contains characters that each may be used by
                         * DateTime::format() method, and it raises problem while trying to verify the value at the end
                         * of this method:
                         *
                         * (new DateTime())->format($value);
                         *
                         * So, I have to refuse those special compound formats if they are not explicitly declared as
                         * compound (2nd argument of this method, set to false by default)
                         */
                        if (in_array($value, $specialFormats, true)) {
                            return false;
                        }
                    }
                } /*
                 * Verify instance of the DateTime created by constructor and by createFromFormat() method.
                 * After formatting, these dates should be the same.
                 */
                elseif ($dateFromFormat->format($dateFormat) === $value) {
                    return $date;
                }
            } catch (Exception $exception) {
                if (!$allowCompoundFormats) {
                    return false;
                }
            }

            /*
             * Does the value is a string that may be used to format date?
             * Example: "Y-m-d"
             */
            $dateString = (new DateTime())->format($value);

            if ($dateString !== (string) $value) {
                return new DateTime($dateString);
            }
        } catch (Exception $exception) {
        }

        return false;
    }

    /**
     * Returns collection / set of dates for given start date and count of dates.
     * Start from given date, add next, iterated value to given date interval and returns requested count of dates.
     *
     * @param DateTime $startDate        The start date. Start of the collection / set.
     * @param int      $datesCount       Count of dates in resulting collection / set
     * @param string   $intervalTemplate (optional) Template used to build date interval. It should contain "%d" as the
     *                                   placeholder which is replaced with a number that represents each iteration.
     *                                   Default: interval for days.
     * @return array
     * @throws Exception
     */
    public static function getDatesCollection(
        DateTime $startDate,
        int $datesCount,
        string $intervalTemplate = 'P%dD'
    ): array {
        $dates = [];

        /*
         * The template used to build date interval should be string.
         * Otherwise cannot run preg_match() function and an error occurs.
         */
        if (is_string($intervalTemplate)) {
            /*
             * Let's verify the interval template. It should contain the "%d" placeholder and something before and
             * after it.
             *
             * Examples:
             * - P%dD
             * - P%dM
             * - P1Y%dMT1H
             */
            $intervalPattern = '/^(\w*)%d(\w*)$/';
            $matches = [];
            $matchCount = preg_match($intervalPattern, $intervalTemplate, $matches);

            if ($matchCount > 0 && (!empty($matches[1]) || !empty($matches[2]))) {
                for ($index = 1; $index <= $datesCount; ++$index) {
                    $date = clone $startDate;
                    $dates[$index] = $date->add(new DateInterval(sprintf($intervalTemplate, $index)));
                }
            }
        }

        return $dates;
    }

    /**
     * Returns date's period (that contains start and end date) for given period
     *
     * @param DatePeriodEnum $period The date period
     *
     * @return null|DatePeriod
     * @throws \DateInvalidOperationException
     * @throws \DateMalformedStringException
     */
    public static function getDatesForPeriod(DatePeriodEnum $period): ?DatePeriod
    {
        $dateStart = null;
        $dateEnd = null;

        switch ($period) {
            case DatePeriodEnum::LastWeek:
                $thisWeekStart = new DateTime('this week');

                $dateStart = clone $thisWeekStart;
                $dateEnd = clone $thisWeekStart;

                $dateStart->sub(new DateInterval('P7D'));
                $dateEnd->sub(new DateInterval('P1D'));

                break;
            case DatePeriodEnum::ThisWeek:
                $dateStart = new DateTime('this week');

                $dateEnd = clone $dateStart;
                $dateEnd->add(new DateInterval('P6D'));

                break;
            case DatePeriodEnum::NextWeek:
                $dateStart = new DateTime('this week');
                $dateStart->add(new DateInterval('P7D'));

                $dateEnd = clone $dateStart;
                $dateEnd->add(new DateInterval('P6D'));

                break;
            case DatePeriodEnum::LastMonth:
                $dateStart = new DateTime('first day of last month');
                $dateEnd = new DateTime('last day of last month');

                break;
            case DatePeriodEnum::ThisMonth:
                $lastMonth = self::getDatesForPeriod(DatePeriodEnum::LastMonth);
                $nextMonth = self::getDatesForPeriod(DatePeriodEnum::NextMonth);

                if (null !== $lastMonth) {
                    $dateStart = $lastMonth->getEndDate();

                    if (null !== $dateStart) {
                        $dateStart->add(new DateInterval('P1D'));
                    }
                }

                if (null !== $nextMonth) {
                    $dateEnd = $nextMonth->getStartDate();

                    if (null !== $dateEnd) {
                        $dateEnd->sub(new DateInterval('P1D'));
                    }
                }

                break;
            case DatePeriodEnum::NextMonth:
                $dateStart = new DateTime('first day of next month');
                $dateEnd = new DateTime('last day of next month');

                break;
            case DatePeriodEnum::LastYear:
            case DatePeriodEnum::ThisYear:
            case DatePeriodEnum::NextYear:
                $dateStart = new DateTime();
                $dateEnd = new DateTime();

                $yearPeriod = [
                    DatePeriodEnum::LastYear,
                    DatePeriodEnum::NextYear,
                ];

                if (in_array($period, $yearPeriod, true)) {
                    $yearDifference = 1;

                    if (DatePeriodEnum::LastYear === $period) {
                        $yearDifference *= -1;
                    }

                    $modifyString = sprintf('%s year', $yearDifference);
                    $dateStart->modify($modifyString);
                    $dateEnd->modify($modifyString);
                }

                $year = (int) $dateStart->format('Y');
                $dateStart->setDate($year, 1, 1);
                $dateEnd->setDate($year, 12, 31);

                break;
        }

        /*
         * Start or end date is unknown?
         * Nothing to do
         */
        if (null === $dateStart || null === $dateEnd) {
            return null;
        }

        $dateStart->setTime(0, 0);
        $dateEnd->setTime(23, 59, 59);

        return new DatePeriod($dateStart, $dateEnd);
    }

    /**
     * Returns day of week (number 0 to 6, 0 - sunday, 6 - saturday).
     * Based on the Zeller's algorithm (https://en.wikipedia.org/wiki/Perpetual_calendar).
     *
     * @param int $year  The year value
     * @param int $month The month value
     * @param int $day   The day value
     *
     * @return int
     * @throws InvalidDatePartException
     */
    public static function getDayOfWeek(int $year, int $month, int $day): int
    {
        self::validateYear($year);
        self::validateMonth($month);
        self::validateDay($day);

        if ($month < 3) {
            $count = 0;
            $yearValue = $year - 1;
        } else {
            $count = 2;
            $yearValue = $year;
        }

        $firstPart = floor(23 * $month / 9);
        $secondPart = floor($yearValue / 4);
        $thirdPart = floor($yearValue / 100);
        $fourthPart = floor($yearValue / 400);

        return ($firstPart + $day + 4 + $year + $secondPart - $thirdPart + $fourthPart - $count) % 7;
    }

    /**
     * Returns name of weekday based on locale
     *
     * @param int $year  The year value
     * @param int $month The month value
     * @param int $day   The day value
     * @return string
     */
    public static function getDayOfWeekName(int $year, int $month, int $day): string
    {
        $hour = 0;
        $minute = 0;
        $second = 0;

        $time = mktime($hour, $minute, $second, $month, $day, $year);
        $name = \date('l', $time);

        $encoding = mb_detect_encoding($name);

        if (false === $encoding) {
            $name = mb_convert_encoding($name, 'UTF-8', 'ISO-8859-2');
        }

        return $name;
    }

    /**
     * Returns random date based on given start date
     *
     * @param DateTime|null $startDate        (optional) Beginning of the random date. If not provided, current date
     *                                        will be used (default behaviour).
     * @param int           $start            (optional) Start of random partition. If not provided, 1 will be used
     *                                        (default behaviour).
     * @param int           $end              (optional) End of random partition. If not provided, 100 will be used
     *                                        (default behaviour).
     * @param string        $intervalTemplate (optional) Template used to build date interval. The placeholder is
     *                                        replaced with next, iterated value. If not provided, "P%sD" will be used
     *                                        (default behaviour).
     * @return DateTime
     * @throws Exception
     */
    public static function getRandomDate(
        DateTime $startDate = null,
        int $start = 1,
        int $end = 100,
        string $intervalTemplate = 'P%sD'
    ): DateTime {
        if (null === $startDate) {
            $startDate = new DateTime();
        }

        /*
         * Incorrect end of random partition?
         * Use start as the end of random partition
         */
        if ($end < $start) {
            $end = $start;
        }

        $randomDate = clone $startDate;
        $randomInterval = new DateInterval(sprintf($intervalTemplate, random_int($start, $end)));

        return $randomDate->add($randomInterval);
    }

    /**
     * Returns information if given value is valid date
     *
     * @param mixed $value                The value which maybe is a date
     * @param bool  $allowCompoundFormats (optional) If is set to true, the compound formats used to create an
     *                                    instance of DateTime class are allowed (e.g. "now", "last day of next
     *                                    month", "yyyy"). Otherwise - not and every incorrect value is refused.
     * @return bool
     */
    public static function isValidDate($value, bool $allowCompoundFormats = false): bool
    {
        return self::getDateTime($value, $allowCompoundFormats) instanceof DateTime;
    }

    /**
     * Returns information if given format of date is valid
     *
     * @param string $format The validated format of date
     * @return bool
     */
    public static function isValidDateFormat(string $format): bool
    {
        if (empty($format)) {
            return false;
        }

        $formatted = (new DateTime())->format($format);

        // Formatted date it's the format who is validated?
        // The format is invalid
        if ($formatted === $format) {
            return false;
        }

        // Validate the format used to create the datetime
        $fromFormat = DateTime::createFromFormat($format, $formatted);

        // It's instance of DateTime?
        // The format is valid
        return $fromFormat instanceof DateTime;
    }

    /**
     * Verifies/validates given day
     *
     * @param int $day Day to verify/validate
     *
     * @throws InvalidDatePartException
     */
    private static function validateDay(int $day): void
    {
        if ($day >= 1 && $day <= 31) {
            return;
        }

        throw new InvalidDatePartException(DatePart::Day, $day);
    }

    /**
     * Verifies/validates given month
     *
     * @param int $month Month to verify/validate
     *
     * @throws InvalidDatePartException
     */
    private static function validateMonth(int $month): void
    {
        if ($month >= 1 && $month <= 12) {
            return;
        }

        throw new InvalidDatePartException(DatePart::Month, $month);
    }

    /**
     * Verifies/validates given year
     *
     * @param int $year Year to verify/validate
     *
     * @throws InvalidDatePartException
     */
    private static function validateYear(int $year): void
    {
        if ($year >= 0) {
            return;
        }

        throw new InvalidDatePartException(DatePart::Year, $year);
    }
}
