<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Common\Contract\Collection;

/**
 * Contract for collection that may be cleared (all its elements may be removed)
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
interface ClearableCollectionInterface
{
    /**
     * Removes all elements of the collection
     *
     * @return void
     */
    public function clear(): void;
}
