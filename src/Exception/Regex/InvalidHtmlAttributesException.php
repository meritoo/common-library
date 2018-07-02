<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Exception\Regex;

/**
 * An exception used while html attributes are invalid
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class InvalidHtmlAttributesException extends \Exception
{
    /**
     * Creates exception
     *
     * @param string $htmlAttributes Invalid html attributes
     * @return InvalidHtmlAttributesException
     */
    public static function create($htmlAttributes)
    {
        $message = sprintf('HTML attributes \'%s\' are invalid. Is there everything ok?', $htmlAttributes);

        return new static($message);
    }
}
