<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Collection;

use ArrayAccess;
use Countable;
use IteratorAggregate;
use Meritoo\Common\Traits\CollectionTrait;

/**
 * Collection of elements with the same type
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
abstract class BaseCollection implements Countable, ArrayAccess, IteratorAggregate
{
    use CollectionTrait;

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
}
