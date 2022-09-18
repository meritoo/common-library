<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Common\ValueObject;

use Meritoo\Common\Exception\ValueObject\Template\InvalidContentException;
use Meritoo\Common\Exception\ValueObject\Template\MissingPlaceholdersInValuesException;

/**
 * Template with placeholders that may be filled by real data
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class Template
{
    private const PLACEHOLDER_TAG = '%';
    private string $content;

    public function __construct(string $content)
    {
        if (!static::isValid($content)) {
            throw InvalidContentException::create($content);
        }

        $this->content = $content;
    }

    /**
     * Returns content of the template filled with given values (by replacing placeholders with their proper values)
     *
     * @param array $values Pairs of key-value where: key - name of placeholder, value - value of the placeholder
     * @return string
     * @throws MissingPlaceholdersInValuesException
     */
    public function fill(array $values): string
    {
        $placeholders = static::getPlaceholders($this->content);
        $providedPlaceholders = array_keys($values);
        $missingPlaceholders = array_diff($placeholders[1], $providedPlaceholders);

        // Oops, there are placeholders without values (iow. provided values are different than placeholders)
        if (!empty($missingPlaceholders)) {
            throw MissingPlaceholdersInValuesException::create($this->content, $missingPlaceholders);
        }

        $result = $this->content;

        foreach ($placeholders[0] as $index => $placeholder) {
            $placeholderName = $placeholders[1][$index];

            if (isset($values[$placeholderName])) {
                $value = $values[$placeholderName];
                $result = str_replace($placeholder, (string) $value, $result);
            }
        }

        return $result;
    }

    /**
     * Returns regular expression that defines format of placeholder
     *
     * Expectations:
     * - surrounded by the placeholder's tags (at beginning and at the end)
     * - at least 1 character
     * - no placeholder's tag inside name of placeholder
     *
     * Invalid placeholders:
     * - test
     * - test%
     * - % test%
     *
     * Valid placeholders:
     * - %test%
     * - %another_test%
     * - %another-test%
     * - %anotherTest%
     * - %another test%
     *
     * @return string
     */
    private static function getPlaceholderPattern(): string
    {
        return sprintf(
            '/%s([^%s]+)%s/',
            static::PLACEHOLDER_TAG,
            static::PLACEHOLDER_TAG,
            static::PLACEHOLDER_TAG
        );
    }

    /**
     * Returns placeholders of given template
     *
     * @param string $content Content of template
     * @return array
     */
    private static function getPlaceholders(string $content): array
    {
        $result = [];
        $matchCount = preg_match_all(static::getPlaceholderPattern(), $content, $result);

        if (false !== $matchCount && 0 < $matchCount) {
            foreach ($result as $index => $placeholders) {
                $result[$index] = array_unique($placeholders);
            }
        }

        return $result;
    }

    /**
     * Returns information if given template is valid
     *
     * @param string $content Raw string with placeholders to validate (content of the template)
     * @return bool
     */
    private static function isValid(string $content): bool
    {
        if ('' === $content) {
            return false;
        }

        return (bool) preg_match_all(static::getPlaceholderPattern(), $content);
    }
}
