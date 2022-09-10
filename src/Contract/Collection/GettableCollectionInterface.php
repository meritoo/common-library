<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Common\Contract\Collection;

/**
 * Contract for collection that returns first, last element etc.
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
interface GettableCollectionInterface
{
    /**
     * Returns element with given index
     *
     * @param mixed $index Index / key of element to return
     * @return mixed
     */
    public function getByIndex($index);

    /**
     * Returns first element
     *
     * @return mixed
     */
    public function getFirst();

    /**
     * Returns last element
     *
     * @return mixed
     */
    public function getLast();

    /**
     * Returns element next after given element
     *
     * @param mixed $element The element whose next element should be returned
     * @return mixed
     */
    public function getNext($element);

    /**
     * Returns element preceding given element
     *
     * @param mixed $element The element whose previous element should be returned
     * @return mixed
     */
    public function getPrevious($element);
}
