<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Common\Contract\Collection;

/**
 * Contract for collection that may verify its elements
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
interface VerifiableCollectionInterface
{
    /**
     * Returns information if given element exists in collection
     *
     * @param mixed $element The element to verify
     * @return bool
     */
    public function has($element): bool;

    /**
     * Returns information if collection is empty (has not any element)
     *
     * @return bool
     */
    public function isEmpty(): bool;

    /**
     * Returns information if given element is the first element in collection
     *
     * @param mixed $element The element to verify
     * @return bool
     */
    public function isFirst($element): bool;

    /**
     * Returns information if given element is the last element in collection
     *
     * @param mixed $element The element to verify
     * @return bool
     */
    public function isLast($element): bool;
}
