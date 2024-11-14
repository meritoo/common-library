<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Exception\Reflection;

use Exception;

/**
 * An exception used while given class hasn't constructor
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class ClassWithoutConstructorException extends Exception
{
    /**
     * Creates exception
     *
     * @param string $className Fully-qualified name of class that hasn't constructor
     * @return ClassWithoutConstructorException
     */
    public static function create(string $className): ClassWithoutConstructorException
    {
        $template = 'Oops, class \'%s\' hasn\'t constructor. Did you use proper class?';
        $message = sprintf($template, $className);

        return new self($message);
    }
}
