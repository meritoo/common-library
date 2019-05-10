<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Traits\Collection;

/**
 * Trait for the Collection with methods that modify collection
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
trait ModifyTrait
{
    /**
     * Prepends given element (adds given element at the beginning of collection)
     *
     * @param mixed $element The element to prepend
     * @return $this
     */
    public function prepend($element): self
    {
        array_unshift($this->elements, $element);

        return $this;
    }

    /**
     * Removes given element
     *
     * @param mixed $element The element to remove
     * @return $this
     */
    public function remove($element): self
    {
        if ($this->count() > 0) {
            foreach ($this->elements as $index => $existing) {
                if ($element === $existing) {
                    unset($this->elements[$index]);

                    break;
                }
            }
        }

        return $this;
    }
}
