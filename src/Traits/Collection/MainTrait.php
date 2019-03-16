<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Traits\Collection;

use Meritoo\Common\Collection\Collection;
use Meritoo\Common\Utilities\Arrays;

/**
 * Main trait for the Collection
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
trait MainTrait
{
    /**
     * The elements of collection
     *
     * @var array
     */
    private $elements;

    /**
     * Adds given element (at the end of collection)
     *
     * @param mixed $element The element to add
     * @param mixed $index   (optional) Index / key of the element
     * @return $this
     */
    public function add($element, $index = null)
    {
        if (null === $index || '' === $index) {
            $this->elements[] = $element;
        } else {
            $this->elements[$index] = $element;
        }

        return $this;
    }

    /**
     * Adds given elements (at the end of collection)
     *
     * @param array|Collection $elements   The elements to add
     * @param bool|false       $useIndexes (optional) If is set to true, indexes of given elements will be used in
     *                                     this collection. Otherwise - not.
     * @return $this
     */
    public function addMultiple($elements, $useIndexes = false)
    {
        if (!empty($elements)) {
            foreach ($elements as $index => $element) {
                if ($useIndexes) {
                    $this->add($element, $index);
                    continue;
                }

                $this->add($element);
            }
        }

        return $this;
    }

    /**
     * Prepends given element (adds given element at the beginning of collection)
     *
     * @param mixed $element The element to prepend
     * @return $this
     */
    public function prepend($element)
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
    public function remove($element)
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

    /**
     * Returns information if collection is empty
     *
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->elements);
    }

    /**
     * Returns information if given element is first in the collection
     *
     * @param mixed $element The element to verify
     * @return bool
     */
    public function isFirst($element)
    {
        return reset($this->elements) === $element;
    }

    /**
     * Returns information if given element is last in the collection
     *
     * @param mixed $element The element to verify
     * @return bool
     */
    public function isLast($element)
    {
        return end($this->elements) === $element;
    }

    /**
     * Returns information if the collection has given element, iow. if given element exists in the collection
     *
     * @param mixed $element The element to verify
     * @return bool
     */
    public function has($element)
    {
        $index = Arrays::getIndexOf($this->elements, $element);

        return null !== $index && false !== $index;
    }

    /**
     * Returns previous element for given element
     *
     * @param mixed $element The element to verify
     * @return mixed|null
     */
    public function getPrevious($element)
    {
        return Arrays::getPreviousElement($this->elements, $element);
    }

    /**
     * Returns next element for given element
     *
     * @param mixed $element The element to verify
     * @return mixed|null
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
     * @return mixed|null
     */
    public function getByIndex($index)
    {
        if (isset($this->elements[$index])) {
            return $this->elements[$index];
        }

        return null;
    }

    /**
     * Returns representation of object as array
     *
     * @return array
     */
    public function toArray()
    {
        return $this->elements;
    }
}
