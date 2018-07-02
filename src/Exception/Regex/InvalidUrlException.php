<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Exception\Regex;

/**
 * An exception used while url is invalid
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class InvalidUrlException extends \Exception
{
    /**
     * Creates exception
     *
     * @param string $url Invalid url
     * @return InvalidUrlException
     */
    public static function create($url)
    {
        $message = sprintf('Url \'%s\' is invalid. Is there everything ok?', $url);

        return new static($message);
    }
}
