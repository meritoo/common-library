<?php

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
     * Class constructor
     *
     * @param string $url Invalid url
     */
    public function __construct($url)
    {
        $message = sprintf('Url \'%s\' is invalid. Is there everything ok?', $url);
        parent::__construct($message);
    }
}
