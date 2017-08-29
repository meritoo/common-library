<?php

namespace Meritoo\Common\Tests\Utilities\Reflection;

/**
 * The A class.
 * Used for testing the Reflection class.
 *
 * @author    Krzysztof Niziol <krzysztof.niziol@meritoo.pl>
 * @copyright Meritoo.pl
 */
class A
{
    use E;

    protected function lorem()
    {
        return 'ipsum';
    }
}
