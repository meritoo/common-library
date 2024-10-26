<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Exception\Reflection;

use Exception;

/**
 * An exception used while name of class or trait cannot be resolved
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class CannotResolveClassNameException extends Exception
{
    /**
     * Creates exception
     *
     * @param string $source   Source of name of the class or trait
     * @param bool   $forClass (optional) If is set to true, message of this exception for class is prepared. Otherwise
     *                         - for trait.
     * @return CannotResolveClassNameException
     */
    public static function create(string $source, bool $forClass = true): CannotResolveClassNameException
    {
        $forWho = 'trait';
        $value = '';

        if ($forClass) {
            $forWho = 'class';
        }

        if (is_scalar($source)) {
            $value = sprintf(' %s', $source);
        }

        $template = 'Name of %s from given \'%s\'%s cannot be resolved. Is there everything ok?';
        $message = sprintf($template, $forWho, gettype($source), $value);

        return new self($message);
    }
}
