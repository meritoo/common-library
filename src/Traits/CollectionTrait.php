<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Traits;

use Meritoo\Common\Traits\Collection\ArrayAccessTrait;
use Meritoo\Common\Traits\Collection\CountableTrait;
use Meritoo\Common\Traits\Collection\IteratorAggregateTrait;
use Meritoo\Common\Traits\Collection\MainTrait;

/**
 * Trait for the Collection
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
trait CollectionTrait
{
    use MainTrait;
    use CountableTrait;
    use ArrayAccessTrait;
    use IteratorAggregateTrait;
}
