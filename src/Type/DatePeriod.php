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
    public const LAST_MONTH = '4';
    public const LAST_WEEK = '1';
    public const LAST_YEAR = '7';
    public const NEXT_MONTH = '6';
    public const NEXT_WEEK = '3';
    public const NEXT_YEAR = '9';
    public const THIS_MONTH = '5';
    public const THIS_WEEK = '2';
    public const THIS_YEAR = '8';
    private ?DateTime $startDate;
    private ?DateTime $endDate;

    public function __construct(?DateTime $startDate = null, ?DateTime $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function getEndDate(): ?DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(?DateTime $endDate = null): self
    {
        $this->endDate = $endDate;

        return $this;
    }

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

    public function getStartDate(): ?DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(?DateTime $startDate = null): self
    {
        $this->startDate = $startDate;

        return $this;
    }
}
