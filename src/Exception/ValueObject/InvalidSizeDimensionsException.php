<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Exception\ValueObject;

/**
 * An exception used while dimensions of size, passed to the instance of Size class, are invalid
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class InvalidSizeDimensionsException extends \Exception
{
    /**
     * Creates exception
     *
     * @param int $width  The width
     * @param int $height The height
     * @return InvalidSizeDimensionsException
     */
    public static function create($width, $height)
    {
        $template = 'Dimensions of size should be positive, but they are not: %d, %d. Is there everything ok?';
        $message = sprintf($template, $width, $height);

        return new static($message);
    }
}
