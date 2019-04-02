<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Common\Exception\ValueObject\Template;

use Exception;

/**
 * An exception used while content of template is invalid
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class InvalidContentException extends Exception
{
    /**
     * Creates an exception
     *
     * @param string $content Invalid content of template
     * @return InvalidContentException
     */
    public static function create(string $content): InvalidContentException
    {
        $template = 'Content of template \'%s\' is invalid. Did you use string with 1 placeholder at least?';
        $message = sprintf($template, $content);

        return new static($message);
    }
}
