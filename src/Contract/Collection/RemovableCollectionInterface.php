<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Common\Contract\Collection;

/**
 * Contract for collection that may remove elements
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
interface RemovableCollectionInterface
{
    /**
     * Removes given element
     *
     * @param mixed $element The element to remove
     * @return void
     */
    public function remove($element): void;
}
