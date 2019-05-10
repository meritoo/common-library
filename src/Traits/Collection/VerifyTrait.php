<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Traits\Collection;

use Meritoo\Common\Utilities\Arrays;

/**
 * Trait for the Collection with methods that verify collection
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
trait VerifyTrait
{
    /**
     * Returns information if collection is empty
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->elements);
    }

    /**
     * Returns information if given element is first in the collection
     *
     * @param mixed $element The element to verify
     * @return bool
     */
    public function isFirst($element): bool
    {
        return reset($this->elements) === $element;
    }

    /**
     * Returns information if given element is last in the collection
     *
     * @param mixed $element The element to verify
     * @return bool
     */
    public function isLast($element): bool
    {
        return end($this->elements) === $element;
    }

    /**
     * Returns information if the collection has given element, iow. if given element exists in the collection
     *
     * @param mixed $element The element to verify
     * @return bool
     */
    public function has($element): bool
    {
        $index = Arrays::getIndexOf($this->elements, $element);

        return null !== $index && false !== $index;
    }
}
