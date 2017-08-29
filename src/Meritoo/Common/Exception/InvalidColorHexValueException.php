<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Exception;

/**
 * An exception used while given hexadecimal value of color is incorrect
 *
 * @author    Krzysztof Niziol <krzysztof.niziol@meritoo.pl>
 * @copyright Meritoo.pl
 */
class InvalidColorHexValueException extends \Exception
{
    /**
     * Class constructor
     *
     * @param string $color Incorrect hexadecimal value of color
     */
    public function __construct($color)
    {
        $message = sprintf('Hexadecimal value of color \'%s\' is incorrect. Is there everything ok?', $color);
        parent::__construct($message);
    }
}
