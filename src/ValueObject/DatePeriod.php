<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Common\ValueObject;

use DateTime;
use Meritoo\Common\Utilities\Date;

/**
 * A date's period.
 * Contains start and end date of the period.
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class DatePeriod
{
    public function __construct(private ?DateTime $startDate = null, private ?DateTime $endDate = null)
    {
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

    public function getStartDate(): ?DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(?DateTime $startDate = null): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getFormattedDate(string $format, bool $startDate = true): string
    {
        $date = $this->endDate;

        // Start date should be formatted?
        if ($startDate) {
            $date = $this->startDate;
        }

        // Unknown date or format is invalid?
        // Nothing to do
        if (null === $date || !Date::isValidDateFormat($format)) {
            return '';
        }

        return $date->format($format);
    }
}
