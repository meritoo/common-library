<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Exception\File;

/**
 * An exception used while file with given path does not exist
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class NotExistingFileException extends \Exception
{
    /**
     * Creates exception
     *
     * @param string $notExistingFilePath Path of not existing (or not readable) file
     * @return NotExistingFileException
     */
    public static function create($notExistingFilePath)
    {
        $template = 'File with path \'%s\' does not exist (or is not readable). Did you provide path of proper file?';
        $message = sprintf($template, $notExistingFilePath);

        return new static($message);
    }
}
