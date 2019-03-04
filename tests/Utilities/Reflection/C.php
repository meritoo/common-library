<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Test\Common\Utilities\Reflection;

/**
 * The C class.
 * Used for testing the Reflection class.
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class C extends B
{
    public function getPositive()
    {
        return true;
    }

    public function getNegative()
    {
        return false;
    }
}
