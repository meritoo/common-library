<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Common\Enums\Date;

enum DatePart: string
{
    case Day = 'day';
    case Hour = 'hour';
    case Minute = 'minute';
    case Month = 'month';
    case Second = 'second';
    case Year = 'year';
}
