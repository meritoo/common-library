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
 * An exception used while there is not enough values to fill all placeholders in template
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class NotEnoughValuesException extends Exception
{
    /**
     * Creates an exception
     *
     * @param string $content           Invalid content of template
     * @param int    $valuesCount       Count of values
     * @param int    $placeholdersCount Count of placeholders
     * @return NotEnoughValuesException
     */
    public static function create(string $content, int $valuesCount, int $placeholdersCount): NotEnoughValuesException
    {
        $template = 'Not enough values (%d) to fill all placeholders (%d) in template \'%s\'. Did you provide all'
            . ' required values?';
        $message = sprintf($template, $valuesCount, $placeholdersCount, $content);

        return new static($message);
    }
}
