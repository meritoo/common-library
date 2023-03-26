<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Type;

use Meritoo\Common\Type\Base\BaseType;

/**
 * The visibility of a property, a method or (as of PHP 7.1.0) a constant
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 *
 * @see       http://php.net/manual/en/language.oop5.visibility.php
 */
class OopVisibilityType extends BaseType
{
    public const IS_PRIVATE = '3';
    public const IS_PROTECTED = '2';
    public const IS_PUBLIC = '1';
}
