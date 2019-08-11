<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Type;

use DateTime;
use Meritoo\Common\Type\Base\BaseType;
use Meritoo\Common\Utilities\Date;

/**
 * A date's period.
 * Contains start and end date of the period.
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class DatePeriod extends BaseType
{
    /**
     * The period constant: last month
     *
     * @var string
     */
    public const LAST_MONTH = '4';

    /**
     * The period constant: last week
     *
     * @var string
     */
    public const LAST_WEEK = '1';

    /**
     * The period constant: last year
     *
     * @var string
     */
    public const LAST_YEAR = '7';

    /**
     * The period constant: next month
     *
     * @var string
     */
    public const NEXT_MONTH = '6';

    /**
     * The period constant: next week
     *
     * @var string
     */
    public const NEXT_WEEK = '3';

    /**
     * The period constant: next year
     *
     * @var string
     */
    public const NEXT_YEAR = '9';

    /**
     * The period constant: this month
     *
     * @var string
     */
    public const THIS_MONTH = '5';

    /**
     * The period constant: this week
     *
     * @var string
     */
    public const THIS_WEEK = '2';

    /**
     * The period constant: this year
     *
     * @var string
     */
    public const THIS_YEAR = '8';

    /**
     * The start date of period
     *
     * @var null|DateTime
     */
    private $startDate;

    /**
     * The end date of period
     *
     * @var null|DateTime
     */
    private $endDate;

    /**
     * Class constructor
     *
     * @param null|DateTime $startDate (optional) The start date of period
     * @param null|DateTime $endDate   (optional) The end date of period
     */
    public function __construct(?DateTime $startDate = null, ?DateTime $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * Returns formatted one of the period's date: start date or end date
     *
     * @param string $format    Format used to format the date
     * @param bool   $startDate (optional) If is set to true, start date will be formatted (default behaviour).
     *                          Otherwise - end date.
     * @return string
     */
    public function getFormattedDate(string $format, bool $startDate = true): string
    {
        $date = $this->getEndDate();

        // Start date should be formatted?
        if ($startDate) {
            $date = $this->getStartDate();
        }

        // Unknown date or format is invalid?
        // Nothing to do
        if (null === $date || !Date::isValidDateFormat($format)) {
            return '';
        }

        return $date->format($format);
    }

    /**
     * Returns the end date of period
     *
     * @return null|DateTime
     */
    public function getEndDate(): ?DateTime
    {
        return $this->endDate;
    }

    /**
     * Sets the end date of period
     *
     * @param null|DateTime $endDate (optional) The end date of period. Default: null.
     * @return $this
     */
    public function setEndDate(?DateTime $endDate = null): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Returns the start date of period
     *
     * @return null|DateTime
     */
    public function getStartDate(): ?DateTime
    {
        return $this->startDate;
    }

    /**
     * Sets the start date of period
     *
     * @param null|DateTime $startDate (optional) The start date of period. Default: null.
     * @return $this
     */
    public function setStartDate(?DateTime $startDate = null): self
    {
        $this->startDate = $startDate;

        return $this;
    }
}
