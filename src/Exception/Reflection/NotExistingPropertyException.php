<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Exception\Reflection;

/**
 * An exception used while property does not exist in instance of class
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class NotExistingPropertyException extends \Exception
{
    /**
     * Creates exception
     *
     * @param mixed  $object   Object that should contains given property
     * @param string $property Name of the property
     * @return NotExistingPropertyException
     */
    public static function create($object, $property)
    {
        $template = 'Property \'%s\' does not exist in instance of class \'%s\'. Did you use proper name of property?';
        $message = sprintf($template, $property, get_class($object));

        return new static($message);
    }
}
