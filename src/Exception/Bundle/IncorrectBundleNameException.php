<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Exception\Bundle;

use Exception;

/**
 * An exception used while name of bundle is incorrect
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class IncorrectBundleNameException extends Exception
{
    /**
     * Class constructor
     *
     * @param string $bundleName Incorrect name of bundle
     */
    public function __construct($bundleName)
    {
        $template = 'Name of bundle \'%s\' is incorrect. It should start with big letter and end with "Bundle". Is'
            . ' there everything ok?';

        $message = sprintf($template, $bundleName);
        parent::__construct($message);
    }
}
