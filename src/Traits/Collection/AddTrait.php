<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Traits\Collection;

use Meritoo\Common\Collection\Collection;

/**
 * Trait for the Collection with add*() methods
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
trait AddTrait
{
    /**
     * Adds given element (at the end of collection)
     *
     * @param mixed $element The element to add
     * @param mixed $index   (optional) Index / key of the element
     * @return $this
     */
    public function add($element, $index = null): self
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
    public function addMultiple($elements, bool $useIndexes = false): self
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
}
