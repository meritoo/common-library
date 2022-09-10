<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Common\Contract\Collection;

/**
 * Contract for collection that may add elements
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
interface AddableCollectionInterface
{
    /**
     * Adds given element (at the end of collection)
     *
     * @param mixed $element The element to add
     * @param mixed $index   (optional) Index / key of the element
     * @return void
     */
    public function add($element, $index = null): void;

    /**
     * Adds given elements (at the end of collection)
     *
     * @param array|CollectionInterface $elements   The elements to add
     * @param bool                      $useIndexes (optional) If is set to true, indexes of given elements will be
     *                                              used in this collection. Otherwise - not.
     * @return void
     */
    public function addMultiple(array $elements, bool $useIndexes = false): void;

    /**
     * Appends given element (adds given element at the end of collection)
     *
     * @param mixed $element The element to add at the end
     * @return void
     */
    public function append($element): void;

    /**
     * Prepends given element (adds given element at the beginning of collection)
     *
     * @param mixed $element The element to add at the beginning
     * @return void
     */
    public function prepend($element): void;
}
