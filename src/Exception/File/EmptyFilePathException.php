<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Exception\File;

use Exception;

/**
 * An exception used while path of given file is empty
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class EmptyFilePathException extends Exception
{
    /**
     * Creates exception
     *
     * @return EmptyFilePathException
     */
    public static function create(): EmptyFilePathException
    {
        return new self('Path of the file is empty. Did you provide path of proper file?');
    }
}
