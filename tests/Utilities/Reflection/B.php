<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Test\Common\Utilities\Reflection;

use PHPUnit\Framework\Attributes\CoversNothing;

#[CoversNothing]
class B extends A implements I
{
    protected $name = 'Lorem Ipsum';

    public function getName()
    {
        return $this->name;
    }
}
