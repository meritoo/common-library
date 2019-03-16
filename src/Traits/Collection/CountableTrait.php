<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Traits\Collection;

/**
 * Trait for the Collection required by Countable interface
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
trait CountableTrait
{
    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->elements);
    }
}
