<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Exception\Reflection;

use Exception;
use Meritoo\Common\Utilities\Reflection;

/**
 * An exception used while given class has more than one child class
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class TooManyChildClassesException extends Exception
{
    /**
     * Creates exception
     *
     * @param array|object|string $parentClass  Class that has more than one child class, but it shouldn't. An array
     *                                          of objects, strings, object or string.
     * @param array               $childClasses Child classes
     * @return TooManyChildClassesException
     */
    public static function create($parentClass, array $childClasses)
    {
        $template = "The '%s' class requires one child class at most who will extend her, but more than one child"
            . " class was found:\n- %s\n\nWhy did you create more than one classes that extend '%s' class?";

        $parentClassName = Reflection::getClassName($parentClass);
        $message = sprintf($template, $parentClassName, implode("\n- ", $childClasses), $parentClassName);

        return new static($message);
    }
}
