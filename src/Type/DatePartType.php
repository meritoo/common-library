<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Type;

use Meritoo\Common\Type\Base\BaseType;

/**
 * Type of date part, e.g. "year"
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class DatePartType extends BaseType
{
    public const DAY = 'day';
    public const HOUR = 'hour';
    public const MINUTE = 'minute';
    public const MONTH = 'month';
    public const SECOND = 'second';
    public const YEAR = 'year';
}
