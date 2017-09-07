<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Tests\Utilities\Reflection;

/**
 * The C class.
 * Used for testing the Reflection class.
 *
 * @author    Krzysztof Niziol <krzysztof.niziol@meritoo.pl>
 * @copyright Meritoo.pl
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
