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
    private array $elements;

    /**
     * Class constructor
     *
     * @param array $elements (optional) Elements of collection
     */
    public function __construct(array $elements = [])
    {
        $validated = $this->getElementsWithValidType($elements);
        $this->elements = $this->prepareElements($validated);
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function addMultiple($elements, bool $useIndexes = false): void
    {
        if (empty($elements)) {
            return;
        }

        $prepared = $this->prepareElements($elements);

        foreach ($prepared as $index => $element) {
            if ($useIndexes) {
                $this->add($element, $index);

                continue;
            }

            $this->add($element);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function append($element): void
    {
        $this->elements[] = $element;
    }

    /**
     * {@inheritdoc}
     */
    public function clear(): void
    {
        $this->elements = [];
    }

    /**
     * {@inheritdoc}
     */
    public function count(): int
    {
        return count($this->elements);
    }

    /**
     * {@inheritdoc}
     */
    public function getByIndex($index)
    {
        return $this->elements[$index] ?? null;
    }

    /**
     * {@inheritdoc}
     */
    public function getFirst()
    {
        return Arrays::getFirstElement($this->elements);
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->elements);
    }

    /**
     * {@inheritdoc}
     */
    public function getLast()
    {
        return Arrays::getLastElement($this->elements);
    }

    /**
     * {@inheritdoc}
     */
    public function getNext($element)
    {
        return Arrays::getNextElement($this->elements, $element);
    }

    /**
     * {@inheritdoc}
     */
    public function getPrevious($element)
    {
        return Arrays::getPreviousElement($this->elements, $element);
    }

    /**
     * {@inheritdoc}
     */
    public function has($element): bool
    {
        $index = Arrays::getIndexOf($this->elements, $element);

        return null !== $index && false !== $index;
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty(): bool
    {
        return empty($this->elements);
    }

    /**
     * {@inheritdoc}
     */
    public function isFirst($element): bool
    {
        return reset($this->elements) === $element;
    }

    /**
     * {@inheritdoc}
     */
    public function isLast($element): bool
    {
        return end($this->elements) === $element;
    }

    /**
     * {@inheritdoc}
     */
    public function limit(int $max, int $offset = 0): self
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

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset): bool
    {
        return $this->exists($offset);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset): mixed
    {
        if ($this->exists($offset)) {
            return $this->elements[$offset];
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value): void
    {
        $this->elements[$offset] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset): void
    {
        if ($this->exists($offset)) {
            unset($this->elements[$offset]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function prepend($element): void
    {
        array_unshift($this->elements, $element);
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function toArray(): array
    {
        return $this->elements;
    }

    /**
     * Returns information if given element has valid type
     *
     * @param mixed $element Element of collection
     * @return bool
     */
    abstract protected function isValidType($element): bool;

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
     * Returns information if element with given index/key exists
     *
     * @param int|string $index The index/key of element
     * @return bool
     */
    private function exists($index): bool
    {
        return isset($this->elements[$index]) || array_key_exists($index, $this->elements);
    }

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
}
