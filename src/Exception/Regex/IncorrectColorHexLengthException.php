<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Exception\Regex;

/**
 * An exception used while length of given hexadecimal value of color is incorrect
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class IncorrectColorHexLengthException extends \Exception
{
    /**
     * Creates exception
     *
     * @param string $color Incorrect hexadecimal value of color
     * @return IncorrectColorHexLengthException
     */
    public static function create($color)
    {
        $template = 'Length of hexadecimal value of color \'%s\' is incorrect. It\'s %d, but it should be 3 or 6.'
            . ' Is there everything ok?';

        $message = sprintf($template, $color, strlen($color));

        return new static($message);
    }
}
