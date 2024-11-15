<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Exception\ValueObject\Template;

use Exception;

/**
 * An exception used while there are missing values required to fill all placeholders in template
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class MissingPlaceholdersInValuesException extends Exception
{
    /**
     * Creates an exception
     *
     * @param string $content             Content of template
     * @param array  $missingPlaceholders Missing placeholders in provided values, iow. placeholders without values
     * @return MissingPlaceholdersInValuesException
     */
    public static function create(string $content, array $missingPlaceholders): MissingPlaceholdersInValuesException
    {
        $template = 'Cannot fill template \'%s\', because of missing values for placeholder(s): %s. Did you provide all'
            .' required values?';
        $message = sprintf($template, $content, implode(', ', $missingPlaceholders));

        return new self($message);
    }
}
