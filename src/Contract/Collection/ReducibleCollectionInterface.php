<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Common\Contract\Collection;

/**
 * Contract for collection that may reduce its elements
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
interface ReducibleCollectionInterface
{
    /**
     * Returns new instance of this collection with limited elements
     *
     * @param int $max    Maximum elements to return
     * @param int $offset (optional) Position of element from which limitation should start
     * @return $this
     */
    public function limit(int $max, int $offset = 0): self;
}
