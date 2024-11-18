<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Common\Enums\Date;

enum DatePeriod: int
{
    case LastMonth = 4;
    case LastWeek = 1;
    case LastYear = 7;
    case NextMonth = 6;
    case NextWeek = 3;
    case NextYear = 9;
    case ThisMonth = 5;
    case ThisWeek = 2;
    case ThisYear = 8;
}
