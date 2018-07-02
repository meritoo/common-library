<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Exception\Type;

use Meritoo\Common\Exception\Base\UnknownTypeException;
use Meritoo\Common\Type\DatePartType;

/**
 * An exception used while type of date part, e.g. "year", is unknown
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class UnknownDatePartTypeException extends UnknownTypeException
{
    /**
     * Creates exception
     *
     * @param string $unknownDatePart Unknown type of date part
     * @param string $value           Incorrect value
     * @return UnknownDatePartTypeException
     */
    public static function createException($unknownDatePart, $value)
    {
        /* @var UnknownDatePartTypeException $exception */
        $exception = parent::create($unknownDatePart, new DatePartType(), sprintf('date part (with value %s)', $value));

        return $exception;
    }
}
