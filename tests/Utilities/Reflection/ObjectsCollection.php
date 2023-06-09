<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Test\Common\Utilities\Reflection;

use Meritoo\Common\Collection\BaseCollection;
use PHPUnit\Framework\Attributes\CoversNothing;

#[CoversNothing]
class ObjectsCollection extends BaseCollection
{
    protected function isValidType($element): bool
    {
        return $element instanceof A
            || $element instanceof B
            || $element instanceof C
            || $element instanceof D
            || $element instanceof F;
    }
}
