<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Exception\Method;

use Exception;

/**
 * An exception used while method cannot be called, because is disabled
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class DisabledMethodException extends Exception
{
    /**
     * Creates exception
     *
     * @param string $disabledMethod    Name of the disabled method
     * @param string $alternativeMethod (optional) Name of the alternative method
     * @return DisabledMethodException
     */
    public static function create($disabledMethod, $alternativeMethod = '')
    {
        $template = 'Method %s() cannot be called, because is disabled.';

        if (!empty($alternativeMethod)) {
            $template .= ' Use %s() instead.';
        }

        $message = sprintf($template, $disabledMethod, $alternativeMethod);

        return new static($message);
    }
}
