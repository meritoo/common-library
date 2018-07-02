<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Utilities;

/**
 * Useful locale methods
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class Locale
{
    /**
     * Sets locale for given category using given language and country code
     *
     * @param int    $category     Named constant specifying the category of the functions affected by the locale
     *                             setting. It's the same constant as required by setlocale() function.
     * @param string $languageCode Language code, in ISO 639-1 format. Short form of the locale, e.g. "fr".
     * @param string $countryCode  (optional) Country code, in ISO 3166-1 alpha-2 format, e.g. "FR"
     * @return false|string
     *
     * Available categories (values of $category argument):
     * - LC_ALL for all of the below
     * - LC_COLLATE for string comparison, see strcoll()
     * - LC_CTYPE for character classification and conversion, for example strtoupper()
     * - LC_MONETARY for localeconv()
     * - LC_NUMERIC for decimal separator (See also localeconv())
     * - LC_TIME for date and time formatting with strftime()
     * - LC_MESSAGES for system responses (available if PHP was compiled with libintl)
     */
    public static function setLocale($category, $languageCode, $countryCode = '')
    {
        $category = (int)$category;

        if (is_string($languageCode)) {
            $languageCode = trim($languageCode);
        }

        $availableCategories = [
            LC_ALL,
            LC_COLLATE,
            LC_CTYPE,
            LC_MONETARY,
            LC_NUMERIC,
            LC_TIME,
            LC_MESSAGES,
        ];

        if (empty($languageCode) || !in_array($category, $availableCategories)) {
            return false;
        }

        $localeLongForm = self::getLongForm($languageCode, $countryCode);

        return setlocale($category, $localeLongForm);
    }

    /**
     * Returns locale for given category
     *
     * @param int $category Named constant specifying the category of the functions affected by the locale setting.
     *                      It's the same constant as required by setlocale() function.
     * @return string
     *
     * Available categories (values of $category argument):
     * - LC_ALL for all of the below
     * - LC_COLLATE for string comparison, see strcoll()
     * - LC_CTYPE for character classification and conversion, for example strtoupper()
     * - LC_MONETARY for localeconv()
     * - LC_NUMERIC for decimal separator (See also localeconv())
     * - LC_TIME for date and time formatting with strftime()
     * - LC_MESSAGES for system responses (available if PHP was compiled with libintl)
     */
    public static function getLocale($category)
    {
        return setlocale($category, '0');
    }

    /**
     * Returns long form of the locale
     *
     * @param string $languageCode Language code, in ISO 639-1 format. Short form of the locale, e.g. "fr".
     * @param string $countryCode  (optional) Country code, in ISO 3166-1 alpha-2 format, e.g. "FR"
     * @param string $encoding     (optional) Encoding of the final locale
     * @return string
     *
     * Example:
     * - language code: fr
     * - country code: ''
     * - result: fr_FR
     */
    public static function getLongForm($languageCode, $countryCode = '', $encoding = 'UTF-8')
    {
        if (is_string($languageCode)) {
            $languageCode = trim($languageCode);
        }

        /*
         * Language code not provided?
         * Nothing to do
         */
        if (empty($languageCode)) {
            return '';
        }

        /*
         * Country code not provided?
         * Let's use language code
         */
        if (empty($countryCode)) {
            $countryCode = $languageCode;
        }

        if (!empty($encoding)) {
            $encoding = sprintf('.%s', $encoding);
        }

        return sprintf('%s_%s%s', $languageCode, strtoupper($countryCode), $encoding);
    }
}
