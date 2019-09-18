<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Common\Collection;

use DateTime;

/**
 * Collection of DateTime instances
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class DateTimeCollection extends BaseCollection
{
    protected function isValidType($element): bool
    {
        return $element instanceof DateTime;
    }
}
