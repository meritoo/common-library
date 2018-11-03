<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Exception\Base;

use Exception;
use Meritoo\Common\Type\Base\BaseType;
use Meritoo\Common\Utilities\Arrays;

/**
 * An exception used while type of something is unknown
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
abstract class UnknownTypeException extends Exception
{
    /**
     * Creates exception
     *
     * @param mixed    $unknownType  The unknown type of something (value of constant)
     * @param BaseType $typeInstance An instance of class that contains type of the something
     * @param string   $typeName     Name of the something
     * @return UnknownTypeException
     */
    public static function create($unknownType, BaseType $typeInstance, $typeName)
    {
        $template = 'The \'%s\' type of %s is unknown. Probably doesn\'t exist or there is a typo. You should use one'
            . ' of these types: %s.';

        $allTypes = $typeInstance->getAll();
        $types = Arrays::values2string($allTypes, '', ', ');
        $message = sprintf($template, $unknownType, $typeName, $types);

        return new static($message);
    }
}
