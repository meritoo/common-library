<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Test\Common\Collection\Collection;

use Meritoo\Common\Collection\BaseCollection;

/**
 * Collection of arrays
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 *
 * @internal
 * @coversNothing
 */
class ArrayCollection extends BaseCollection
{
    protected function isValidType($element): bool
    {
        return is_array($element);
    }
}
