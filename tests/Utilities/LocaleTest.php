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
use ReflectionException;

/**
 * Test case of the useful locale methods
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class LocaleTest extends BaseTestCase
{
    /**
     * @throws ReflectionException
     */
    public function testConstructor()
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
     * @dataProvider provideLanguageEncodingAndCountryCode
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

    public function testSetLocaleIncorrectCategory()
    {
        self::assertFalse(Locale::setLocale(-1, 'en'));
    }

    /**
     * @param int    $category       Named constant specifying the category of the functions affected by the locale
     *                               setting. It's the same constant as required by setlocale() function.
     * @param string $languageCode   Language code, in ISO 639-1 format. Short form of the locale, e.g. "fr".
     * @param string $countryCode    Country code, in ISO 3166-1 alpha-2 format, e.g. "FR"
     * @param string $expectedLocale Expected locale
     *
     * @dataProvider provideCategoryLanguageCodeAndExpectedLocale
     */
    public function testSetLocale($category, $languageCode, $countryCode, $expectedLocale)
    {
        self::assertEquals($expectedLocale, Locale::setLocale($category, $languageCode, $countryCode));
    }

    /**
     * @param int    $category       Named constant specifying the category of the functions affected by the locale setting.
     *                               It's the same constant as required by setlocale() function.
     * @param string $languageCode   Language code, in ISO 639-1 format. Short form of the locale, e.g. "fr".
     * @param string $countryCode    Country code, in ISO 3166-1 alpha-2 format, e.g. "FR"
     * @param string $expectedLocale Expected locale
     *
     * @dataProvider provideCategoryLanguageCodeAndExpectedLocale
     */
    public function testGetLocale($category, $languageCode, $countryCode, $expectedLocale)
    {
        Locale::setLocale($category, $languageCode, $countryCode);
        self::assertEquals($expectedLocale, Locale::getLocale($category));
    }

    /**
     * Provides language, encoding and country code
     *
     * @return Generator
     */
    public function provideLanguageEncodingAndCountryCode()
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

        yield[
            'en',
            'US',
            '',
            'en_US',
        ];

        yield[
            'en',
            'US',
            'UTF-8',
            'en_US.UTF-8',
        ];

        yield[
            'en',
            'US',
            'ISO-8859-1',
            'en_US.ISO-8859-1',
        ];
    }

    /**
     * Provides category
     *
     * @return Generator
     */
    public function provideCategoryLanguageCodeAndExpectedLocale()
    {
        yield[
            LC_ALL,
            'fr',
            '',
            'fr_FR.UTF-8',
        ];

        yield[
            LC_COLLATE,
            'fr',
            'FR',
            'fr_FR.UTF-8',
        ];

        yield[
            LC_CTYPE,
            'en',
            'US',
            'en_US.UTF-8',
        ];

        yield[
            LC_NUMERIC,
            'en',
            'GB',
            'en_GB.UTF-8',
        ];

        yield[
            LC_MONETARY,
            'es',
            '',
            'es_ES.UTF-8',
        ];

        yield[
            LC_MONETARY,
            'es',
            'ES',
            'es_ES.UTF-8',
        ];

        yield[
            LC_TIME,
            'it',
            '',
            'it_IT.UTF-8',
        ];

        yield[
            LC_TIME,
            'it',
            'IT',
            'it_IT.UTF-8',
        ];

        yield[
            LC_TIME,
            'it',
            'it',
            'it_IT.UTF-8',
        ];
    }
}
