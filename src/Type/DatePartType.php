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
    /**
     * The "day" date part
     *
     * @var string
     */
    public const DAY = 'day';

    /**
     * The "hour" date part
     *
     * @var string
     */
    public const HOUR = 'hour';

    /**
     * The "minute" date part
     *
     * @var string
     */
    public const MINUTE = 'minute';

    /**
     * The "month" date part
     *
     * @var string
     */
    public const MONTH = 'month';

    /**
     * The "second" date part
     *
     * @var string
     */
    public const SECOND = 'second';

    /**
     * The "year" date part
     *
     * @var string
     */
    public const YEAR = 'year';
}
