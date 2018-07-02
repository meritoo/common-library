<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Exception\File;

/**
 * An exception used while file with given path is empty (has no content)
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class EmptyFileException extends \Exception
{
    /**
     * Creates exception
     *
     * @param string $emptyFilePath Path of the empty file
     * @return EmptyFileException
     */
    public static function create($emptyFilePath)
    {
        $template = 'File with path \'%s\' is empty (has no content). Did you provide path of proper file?';
        $message = sprintf($template, $emptyFilePath);

        return new static($message);
    }
}
