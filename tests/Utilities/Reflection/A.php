<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Test\Common\Utilities\Reflection;

use PHPUnit\Framework\Attributes\CoversNothing;

#[CoversNothing]
class A
{
    use E;

    private $count = 1;

    protected function getCount()
    {
        return $this->count;
    }

    protected function lorem()
    {
        return 'ipsum';
    }
}
