<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Exception\Regex;

use Exception;

/**
 * An exception used while given hexadecimal value of color is invalid
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class InvalidColorHexValueException extends Exception
{
    /**
     * Creates exception
     *
     * @param string $color Invalid hexadecimal value of color
     * @return InvalidColorHexValueException
     */
    public static function create(string $color): InvalidColorHexValueException
    {
        $message = sprintf('Hexadecimal value of color \'%s\' is invalid. Is there everything ok?', $color);

        return new static($message);
    }
}
