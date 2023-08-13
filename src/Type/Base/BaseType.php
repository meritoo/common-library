<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Type\Base;

use Meritoo\Common\Utilities\Reflection;

/**
 * Base / abstract type of something, e.g. type of button, order, date etc.
 * Child class should contain constants - each of them represent one type.
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
abstract class BaseType
{
    private ?array $all = null;

    public function getAll(): ?array
    {
        if (null === $this->all) {
            $this->all = Reflection::getConstants($this);
        }

        return $this->all;
    }

    public static function isCorrectType(null|string|int $type): bool
    {
        return in_array($type, (new static())->getAll(), true);
    }
}
