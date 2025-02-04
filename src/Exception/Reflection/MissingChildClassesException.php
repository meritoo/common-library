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
 * An exception used while given class has no child classes
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class MissingChildClassesException extends Exception
{
    /**
     * Creates exception
     *
     * @param array|object|string $parentClass Class that hasn't child classes, but it should. An array of objects,
     *                                         strings, object or string.
     * @return MissingChildClassesException
     */
    public static function create($parentClass): MissingChildClassesException
    {
        $template = 'The \'%s\' class requires one child class at least who will extend her (maybe is an abstract'
            .' class), but the child classes are missing. Did you forget to extend this class?';

        $parentClassName = Reflection::getClassName($parentClass) ?? '[unknown class]';
        $message = sprintf($template, $parentClassName);

        return new self($message);
    }
}
