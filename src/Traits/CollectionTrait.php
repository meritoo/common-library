<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Traits;

use Meritoo\Common\Traits\Collection\AddTrait;
use Meritoo\Common\Traits\Collection\ArrayAccessTrait;
use Meritoo\Common\Traits\Collection\CountableTrait;
use Meritoo\Common\Traits\Collection\GetTrait;
use Meritoo\Common\Traits\Collection\IteratorAggregateTrait;
use Meritoo\Common\Traits\Collection\MainTrait;
use Meritoo\Common\Traits\Collection\ModifyTrait;
use Meritoo\Common\Traits\Collection\VerifyTrait;

/**
 * Trait for the Collection
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
trait CollectionTrait
{
    use MainTrait;
    use AddTrait;
    use ModifyTrait;
    use GetTrait;
    use VerifyTrait;
    use CountableTrait;
    use ArrayAccessTrait;
    use IteratorAggregateTrait;
}
