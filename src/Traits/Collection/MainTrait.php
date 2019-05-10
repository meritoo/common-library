<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Traits\Collection;

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
     * Returns representation of object as array
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->elements;
    }
}
