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
 * Collection of elements.
 * It's a set of some elements, e.g. objects.
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class Collection implements Countable, ArrayAccess, IteratorAggregate
{
    use CollectionTrait;

    /**
     * Class constructor
     *
     * @param array $elements (optional) The elements of collection
     */
    public function __construct(array $elements = [])
    {
        $this->elements = $elements;
    }
}
