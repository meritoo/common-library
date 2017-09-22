<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Test\Utilities;

use Generator;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Utilities\Locale;

/**
 * Test case of the useful locale methods
 *
 * @author    Krzysztof Niziol <krzysztof.niziol@meritoo.pl>
 * @copyright Meritoo.pl
 */
class LocaleTest extends BaseTestCase
{
    public function verifyConstructor()
    {
        static::assertHasNoConstructor(Locale::class);
    }

    /**
     * @param mixed $languageCode Empty value, e.g. ""
     * @dataProvider provideEmptyValue
     */
    public function testGetLongFormEmptyLanguageCode($languageCode)
    {
        self::assertEquals('', Locale::getLongForm($languageCode));
    }

    /**
     * @param string $languageCode Language code, in ISO 639-1 format. Short form of the locale, e.g. "fr".
     * @param string $countryCode  Country code, in ISO 3166-1 alpha-2 format, e.g. "FR"
     * @param string $encoding     Encoding of the final locale
     * @param string $expected     Expected long form of the locale
     *
     * @dataProvider provideLanguageAndCountryCode
     */
    public function testGetLongForm($languageCode, $countryCode, $encoding, $expected)
    {
        self::assertEquals($expected, Locale::getLongForm($languageCode, $countryCode, $encoding));
    }

    /**
     * @param mixed $emptyValue Empty value, e.g. ""
     * @dataProvider provideEmptyValue
     */
    public function testSetLocaleEmptyCategoryAndLanguageCode($emptyValue)
    {
        self::assertFalse(Locale::setLocale($emptyValue, $emptyValue));
    }

    /**
     * @param int    $category     Named constant specifying the category of the functions affected by the locale
     *                             setting. It's the same constant as required by setlocale() function.
     * @param string $languageCode Language code, in ISO 639-1 format. Short form of the locale, e.g. "fr".
     *
     * @dataProvider provideCategoryAndLanguageCode
     */
    public function testSetLocale($category, $languageCode)
    {
        self::assertTrue(Locale::setLocale($category, $languageCode));
    }

    /**
     * Provides language and country code
     *
     * @return Generator
     */
    public function provideLanguageAndCountryCode()
    {
        yield[
            'fr',
            '',
            '',
            'fr_FR',
        ];

        yield[
            'fr',
            '',
            'UTF-8',
            'fr_FR.UTF-8',
        ];

        yield[
            'fr',
            'FR',
            '',
            'fr_FR',
        ];

        yield[
            'fr',
            'FR',
            'UTF-8',
            'fr_FR.UTF-8',
        ];
    }

    /**
     * Provides category and language
     *
     * @return Generator
     */
    public function provideCategoryAndLanguageCode()
    {
        yield[
            LC_ALL,
            'fr',
        ];

        yield[
            LC_COLLATE,
            'fr',
        ];

        yield[
            LC_CTYPE,
            'en',
        ];

        yield[
            LC_NUMERIC,
            'en',
        ];
    }
}
