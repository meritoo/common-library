<?php

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
     * {@inheritdoc}
     */
    public function __construct($unknownType)
    {
        parent::__construct($unknownType, new OopVisibilityType(), 'OOP-related visibility');
    }
}
