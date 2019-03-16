<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Traits\Collection;

/**
 * Trait for the Collection required by ArrayAccess interface
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
trait ArrayAccessTrait
{
    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return $this->exists($offset);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        if ($this->exists($offset)) {
            return $this->elements[$offset];
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        $this->elements[$offset] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        if ($this->exists($offset)) {
            unset($this->elements[$offset]);
        }
    }

    /**
     * Returns information if element with given index/key exists
     *
     * @param string|int $index The index/key of element
     * @return bool
     */
    private function exists($index)
    {
        return isset($this->elements[$index]) || array_key_exists($index, $this->elements);
    }
}
