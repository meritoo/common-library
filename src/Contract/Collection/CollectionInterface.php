<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Common\Contract\Collection;

use ArrayAccess;
use IteratorAggregate;

/**
 * Contract for collection of elements with the same type
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
interface CollectionInterface extends ArrayAccess, IteratorAggregate, AddableCollectionInterface,
    RemovableCollectionInterface, CountableCollectionInterface, ClearableCollectionInterface,
    GettableCollectionInterface, VerifiableCollectionInterface, ReducibleCollectionInterface
{
    /**
     * Returns representation of object as array
     *
     * @return array
     */
    public function toArray(): array;
}
