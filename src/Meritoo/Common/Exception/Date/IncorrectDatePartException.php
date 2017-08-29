<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Exception\Date;

use Exception;

/**
 * An exception used while given part of date is incorrect, e.g. value of year is incorrect
 *
 * @author    Krzysztof Niziol <krzysztof.niziol@meritoo.pl>
 * @copyright Meritoo.pl
 */
class IncorrectDatePartException extends Exception
{
    /**
     * Class constructor
     *
     * @param string $value    Incorrect value
     * @param string $datePart Type of date part, e.g. "year". One of \Meritoo\Common\Type\DatePartType class constants.
     */
    public function __construct($value, $datePart)
    {
        $message = sprintf('Value of %s \'%s\' is incorrect. Is there everything ok?', $datePart, $value);
        parent::__construct($message);
    }
}
