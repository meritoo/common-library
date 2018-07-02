<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Exception\File;

/**
 * An exception used while path of given file is empty
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class EmptyFilePathException extends \Exception
{
    /**
     * Creates exception
     */
    public static function create()
    {
        return new static('Path of the file is empty. Did you provide path of proper file?');
    }
}
