<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Common\Collection;

use Meritoo\Common\Exception\ValueObject\Template\TemplateNotFoundException;
use Meritoo\Common\ValueObject\Template;

/**
 * Collection/storage of templates
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class Templates extends BaseCollection
{
    /**
     * Finds and returns template with given index
     *
     * @param string $index Index that contains required template
     * @return Template
     * @throws TemplateNotFoundException
     */
    public function findTemplate(string $index): Template
    {
        $template = $this->getByIndex($index);

        if ($template instanceof Template) {
            return $template;
        }

        // Oops, template not found
        throw TemplateNotFoundException::create($index);
    }

    /**
     * Creates and returns the collection from given array
     *
     * @param array $templates Pairs of key-value where: key - template's index, value - template's content
     * @return Templates
     */
    public static function fromArray(array $templates): Templates
    {
        // No templates. Nothing to do.
        if (empty($templates)) {
            return new self();
        }

        $result = new self();

        foreach ($templates as $index => $template) {
            $result->add(new Template($template), $index);
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    protected function isValidType($element): bool
    {
        return $element instanceof Template;
    }
}
