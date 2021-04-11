<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Common\Collection;

use ArrayIterator;
use Meritoo\Common\Contract\Collection\CollectionInterface;
use Meritoo\Common\Utilities\Arrays;

/**
 * Collection of elements with the same type
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
abstract class BaseCollection implements CollectionInterface
{
    /**
     * The elements of collection
     *
     * @var array
     */
    private $elements;

    /**
     * Class constructor
     *
     * @param array $elements (optional) The elements of collection
     */
    public function __construct(array $elements = [])
    {
        $validated = $this->getElementsWithValidType($elements);
        $this->elements = $this->prepareElements($validated);
    }

    /**
     * Returns representation of object as array
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->elements;
    }

    /**
     * Adds given element (at the end of collection)
     *
     * @param mixed $element The element to add
     * @param mixed $index   (optional) Index / key of the element
     */
    public function add($element, $index = null): void
    {
        if (!$this->isValidType($element)) {
            return;
        }

        if (null === $index) {
            $this->elements[] = $element;

            return;
        }

        $this->elements[$index] = $element;
    }

    /**
     * Adds given elements (at the end of collection)
     *
     * @param array|CollectionInterface $elements   The elements to add
     * @param bool                      $useIndexes (optional) If is set to true, indexes of given elements will be
     *                                              used in this collection. Otherwise - not.
     */
    public function addMultiple($elements, bool $useIndexes = false): void
    {
        if (empty($elements)) {
            return;
        }

        foreach ($elements as $index => $element) {
            if ($useIndexes) {
                $this->add($element, $index);

                continue;
            }

            $this->add($element);
        }
    }

    /**
     * Prepends given element (adds given element at the beginning of collection)
     *
     * @param mixed $element The element to prepend
     */
    public function prepend($element): void
    {
        array_unshift($this->elements, $element);
    }

    /**
     * Removes given element
     *
     * @param mixed $element The element to remove
     */
    public function remove($element): void
    {
        if (0 === $this->count()) {
            return;
        }

        foreach ($this->elements as $index => $existing) {
            if ($element === $existing) {
                unset($this->elements[$index]);

                break;
            }
        }
    }

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

    public function clear(): void
    {
        $this->elements = [];
    }

    public function limit(int $max, int $offset = 0): CollectionInterface
    {
        $result = clone $this;

        $negativeMax = $max <= 0;
        $exceededMax = $max >= $this->count();

        if ($negativeMax || $exceededMax) {
            if ($negativeMax) {
                $result->clear();
            }

            return $result;
        }

        $iteration = -1;

        foreach ($result as $index => $element) {
            $iteration++;

            if ($iteration >= $offset && $iteration < $offset + $max) {
                continue;
            }

            unset($result[$index]);
        }

        return $result;
    }

    public function count(): int
    {
        return count($this->elements);
    }

    public function offsetExists($offset): bool
    {
        return $this->exists($offset);
    }

    public function offsetGet($offset)
    {
        if ($this->exists($offset)) {
            return $this->elements[$offset];
        }

        return null;
    }

    public function offsetSet($offset, $value): void
    {
        $this->elements[$offset] = $value;
    }

    public function offsetUnset($offset): void
    {
        if ($this->exists($offset)) {
            unset($this->elements[$offset]);
        }
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->elements);
    }

    /**
     * Prepares elements to initialize the collection.
     * Feel free to override and prepare elements in your way.
     *
     * @param array $elements The elements of collection to prepare
     * @return array
     */
    protected function prepareElements(array $elements): array
    {
        return $elements;
    }

    /**
     * Returns information if given element has valid type
     *
     * @param mixed $element Element of collection
     * @return bool
     */
    abstract protected function isValidType($element): bool;

    /**
     * Returns elements of collection with valid types
     *
     * @param array $elements The elements of collection to verify
     * @return array
     */
    private function getElementsWithValidType(array $elements): array
    {
        if (empty($elements)) {
            return [];
        }

        $result = [];

        foreach ($elements as $index => $element) {
            if (!$this->isValidType($element)) {
                continue;
            }

            $result[$index] = $element;
        }

        return $result;
    }

    /**
     * Returns information if element with given index/key exists
     *
     * @param int|string $index The index/key of element
     * @return bool
     */
    private function exists($index): bool
    {
        return isset($this->elements[$index]) || array_key_exists($index, $this->elements);
    }
}
