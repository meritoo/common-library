<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Test\Common\Utilities\Reflection;

use PHPUnit\Framework\Attributes\CoversNothing;

#[CoversNothing]
class J
{
    private $f;

    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->f = new F(1000, 'New York', 'USA', 'john.scott');
    }
}
