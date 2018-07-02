<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Exception\Type;

use Meritoo\Common\Exception\Base\UnknownTypeException;
use Meritoo\Common\Type\OopVisibilityType;

/**
 * An exception used while the visibility of a property, a method or (as of PHP 7.1.0) a constant is unknown
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class UnknownOopVisibilityTypeException extends UnknownTypeException
{
    /**
     * Creates exception
     *
     * @param string $unknownType Unknown visibility of a property, a method or (as of PHP 7.1.0) a constant
     * @return UnknownOopVisibilityTypeException
     */
    public static function createException($unknownType)
    {
        /* @var UnknownOopVisibilityTypeException $exception */
        $exception = parent::create($unknownType, new OopVisibilityType(), 'OOP-related visibility');

        return $exception;
    }
}
