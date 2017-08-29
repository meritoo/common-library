<?php

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
