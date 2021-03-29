<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Common\Contract\Collection;

use ArrayAccess;
use Countable;
use IteratorAggregate;

/**
 * Interface/Contract of collection of elements with the same type
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
interface CollectionInterface extends Countable, ArrayAccess, IteratorAggregate
{
    public function toArray(): array;

    public function add($element, $index = null): void;

    public function addMultiple($elements, bool $useIndexes = false): void;

    public function prepend($element): void;

    public function remove($element): void;

    public function getPrevious($element);

    public function getNext($element);

    public function getFirst();

    public function getLast();

    public function getByIndex($index);

    public function isEmpty(): bool;

    public function isFirst($element): bool;

    public function isLast($element): bool;

    public function has($element): bool;
}
