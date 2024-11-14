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
 * An exception used while template with given index was not found
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class TemplateNotFoundException extends Exception
{
    /**
     * Creates the exception
     *
     * @param string $index Index that should contain template, but it was not found
     * @return TemplateNotFoundException
     */
    public static function create(string $index): TemplateNotFoundException
    {
        $template = 'Template with \'%s\' index was not found. Did you provide all required templates?';
        $message = sprintf($template, $index);

        return new self($message);
    }
}
