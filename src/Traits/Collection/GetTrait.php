<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Traits\Collection;

use Meritoo\Common\Utilities\Arrays;

/**
 * Trait for the Collection with getters
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
trait GetTrait
{
    /**
     * Returns previous element for given element
     *
     * @param mixed $element The element to verify
     * @return null|mixed
     */
    public function getPrevious($element)
    {
        return Arrays::getPreviousElement($this->elements, $element);
    }

    /**
     * Returns next element for given element
     *
     * @param mixed $element The element to verify
     * @return null|mixed
     */
    public function getNext($element)
    {
        return Arrays::getNextElement($this->elements, $element);
    }

    /**
     * Returns the first element in the collection
     *
     * @return mixed
     */
    public function getFirst()
    {
        return Arrays::getFirstElement($this->elements);
    }

    /**
     * Returns the last element in the collection
     *
     * @return mixed
     */
    public function getLast()
    {
        return Arrays::getLastElement($this->elements);
    }

    /**
     * Returns element with given index
     *
     * @param mixed $index Index / key of the element
     * @return null|mixed
     */
    public function getByIndex($index)
    {
        return $this->elements[$index] ?? null;
    }
}
