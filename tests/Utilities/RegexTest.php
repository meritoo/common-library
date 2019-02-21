<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Utilities;

use Generator;
use Meritoo\Common\Exception\Regex\IncorrectColorHexLengthException;
use Meritoo\Common\Exception\Regex\InvalidColorHexValueException;
use Meritoo\Common\Test\Base\BaseTestCase;

/**
 * Test case of the useful regular expressions methods
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class RegexTest extends BaseTestCase
{
    private $simpleText;
    private $camelCaseText;

    public function testConstructor()
    {
        static::assertHasNoConstructor(Regex::class);
    }

    public function testGetCamelCaseParts()
    {
        $parts = [];
        self::assertEquals($parts, Regex::getCamelCaseParts(''));

        $parts = [
            'lorem',
        ];

        self::assertEquals($parts, Regex::getCamelCaseParts('lorem'));

        $parts = [
            'lorem',
            'Ipsum',
            'Dolor',
            'Sit',
        ];

        self::assertEquals($parts, Regex::getCamelCaseParts($this->camelCaseText));

        $parts = [
            'Lorem',
            'Ipsum',
            'Dolor',
            'Sit',
        ];

        $string = ucfirst($this->camelCaseText); // 'LoremIpsumDolorSit'
        self::assertEquals($parts, Regex::getCamelCaseParts($string));
    }

    public function testCamelCase2humanReadable()
    {
        self::assertEquals('', Regex::camelCase2humanReadable(''));
        self::assertEquals('lorem', Regex::camelCase2humanReadable('lorem'));

        self::assertEquals($this->simpleText, Regex::camelCase2humanReadable($this->camelCaseText));
        self::assertEquals(ucfirst($this->simpleText), Regex::camelCase2humanReadable($this->camelCaseText, true));
    }

    public function testCamelCase2simpleLowercase()
    {
        self::assertEquals('', Regex::camelCase2simpleLowercase(''));
        self::assertEquals('lorem', Regex::camelCase2simpleLowercase('lorem'));
        self::assertEquals('Lorem', Regex::camelCase2simpleLowercase('Lorem', '', false));
        self::assertEquals('lorem-ipsum-dolor-sit', Regex::camelCase2simpleLowercase($this->camelCaseText, '-'));
        self::assertEquals('lorem-Ipsum-Dolor-Sit', Regex::camelCase2simpleLowercase($this->camelCaseText, '-', false));
    }

    public function testIsValidUrl()
    {
        $validUrls = [
            'http://php.net',
            'http://php.net/',
            'http://php.net/docs.php',
            'http://php.net/get-involved.php',
            'http://php.net/manual/en/function.preg-match.php',
            'http://domain.com/BigLetters',
            'http://domain.com/Another-Big-Letters',
            'http://domain.com/?a=1&b=c2d',
            'http://domAin.COM/?a=1&B=c2D',
            'http://domain.com/index.php?a=1&b=c2d',
            'http://domain.com/another-page-2.php?a=1&b=c2d',
            'https://domain.com',
            'https://domain.com/',
        ];

        $invalidUrls = [
            '',
            null,
            false,
            true,
            0,
            1,
            123,
            '123',
            'http:',
            'http://',
            'http://abc',
            'ftp://def',
        ];

        foreach ($validUrls as $url) {
            self::assertTrue(Regex::isValidUrl($url));
        }

        foreach ($invalidUrls as $url) {
            self::assertFalse(Regex::isValidUrl($url));
        }
    }

    public function testIsSubPathOf()
    {
        self::assertFalse(Regex::isSubPathOf(null, null));
        self::assertFalse(Regex::isSubPathOf('', ''));

        self::assertFalse(Regex::isSubPathOf('', '/my/directory'));
        self::assertFalse(Regex::isSubPathOf('/my/file', ''));
        self::assertFalse(Regex::isSubPathOf('/my/file', '/my/directory'));

        self::assertTrue(Regex::isSubPathOf('/my/directory', '/my/directory'));
        self::assertTrue(Regex::isSubPathOf('/my/directory/', '/my/directory'));
        self::assertTrue(Regex::isSubPathOf('/my/directory', '/my/directory/'));
        self::assertTrue(Regex::isSubPathOf('/my/directory/', '/my/directory/'));

        self::assertTrue(Regex::isSubPathOf('/my/another/directory/another/file', '/my/another/directory'));
    }

    public function testIsLetterOrDigit()
    {
        self::assertTrue(Regex::isLetterOrDigit('a'));
        self::assertTrue(Regex::isLetterOrDigit(10));
        self::assertFalse(Regex::isLetterOrDigit(';'));
    }

    public function testStartsWith()
    {
        $string = 'Lorem ipsum dolor sit amet';

        $beginning = '';
        self::assertFalse(Regex::startsWith($string, $beginning));
        self::assertFalse(Regex::startsWith('', $string));

        $beginning = 'Lor';
        self::assertTrue(Regex::startsWith($string, $beginning));

        $beginning = 'L';
        self::assertTrue(Regex::startsWith($string, $beginning));

        $beginning = 'X';
        self::assertFalse(Regex::startsWith($string, $beginning));

        $string = '1234567890';
        $beginning = '1';
        self::assertTrue(Regex::startsWith($string, $beginning));

        $beginning = ';';
        self::assertFalse(Regex::startsWith($string, $beginning));
    }

    public function testStartsWithDirectorySeparator()
    {
        /*
         * Not provided, default separator
         */
        self::assertTrue(Regex::startsWithDirectorySeparator('/my/extra/directory'));
        self::assertFalse(Regex::startsWithDirectorySeparator('my/extra/directory'));

        /*
         * Slash as separator
         */
        $separatorSlash = '/';

        self::assertTrue(Regex::startsWithDirectorySeparator('/my/extra/directory', $separatorSlash));
        self::assertFalse(Regex::startsWithDirectorySeparator('my/extra/directory', $separatorSlash));

        /*
         * Backslash as separator
         */
        $separatorBackslash = '\\';

        self::assertTrue(Regex::startsWithDirectorySeparator('\my\extra\directory', $separatorBackslash));
        self::assertFalse(Regex::startsWithDirectorySeparator('my\extra\directory', $separatorBackslash));
    }

    public function testEndsWithDirectorySeparator()
    {
        /*
         * Not provided, default separator
         */
        self::assertTrue(Regex::endsWithDirectorySeparator('my simple text/'));
        self::assertFalse(Regex::endsWithDirectorySeparator('my simple text'));

        /*
         * Slash as separator
         */
        $separatorSlash = '/';

        self::assertTrue(Regex::endsWithDirectorySeparator('my simple text/', $separatorSlash));
        self::assertFalse(Regex::endsWithDirectorySeparator('my simple text', $separatorSlash));

        /*
         * Backslash as separator
         */
        $separatorBackslash = '\\';

        self::assertTrue(Regex::endsWithDirectorySeparator('my simple text\\', $separatorBackslash));
        self::assertFalse(Regex::endsWithDirectorySeparator('my simple text', $separatorBackslash));
    }

    public function testEndsWith()
    {
        self::assertFalse(Regex::endsWith($this->simpleText, '\.\.\.'));
        self::assertFalse(Regex::endsWith($this->simpleText, '\.'));
        self::assertTrue(Regex::endsWith($this->simpleText, 't'));
    }

    public function testIsSetUriParameter()
    {
        $uri = 'www.domain.com/?name=phil&type=4';

        $parameterName = 'type';
        self::assertTrue(Regex::isSetUriParameter($uri, $parameterName));

        $parameterName = 'color';
        self::assertFalse(Regex::isSetUriParameter($uri, $parameterName));
    }

    public function testContainsEntities()
    {
        self::assertFalse(Regex::containsEntities('Lorem ipsum'));
        self::assertTrue(Regex::containsEntities('Lorem ipsum &raquo;'));
    }

    public function testContains()
    {
        self::assertTrue(Regex::contains($this->simpleText, 'ipsum'));
        self::assertFalse(Regex::contains($this->simpleText, 'neque'));

        self::assertFalse(Regex::contains($this->simpleText, '.'));
        self::assertTrue(Regex::contains($this->simpleText, 'l'));
    }

    public function testIsFileName()
    {
        $filePath = __FILE__;
        $directoryPath = dirname($filePath);

        self::assertTrue(Regex::isFileName($filePath));
        self::assertFalse(Regex::isFileName($directoryPath));
    }

    public function testIsQuoted()
    {
        self::assertTrue(Regex::isQuoted('\'lorem ipsum\''));
        self::assertTrue(Regex::isQuoted('"lorem ipsum"'));

        self::assertFalse(Regex::isQuoted('lorem ipsum'));
        self::assertFalse(Regex::isQuoted(new \stdClass()));
    }

    public function testIsWindowsBasedPath()
    {
        self::assertTrue(Regex::isWindowsBasedPath('C:\path\to\directory'));
        self::assertTrue(Regex::isWindowsBasedPath('C:\path\to\file.jpg'));

        self::assertFalse(Regex::isWindowsBasedPath('/path/to/directory'));
        self::assertFalse(Regex::isWindowsBasedPath('/path/to/file.jpg'));
    }

    public function testIsValidNip()
    {
        self::assertFalse(Regex::isValidNip(null));
        self::assertFalse(Regex::isValidNip(''));
        self::assertFalse(Regex::isValidNip(1234));
        self::assertFalse(Regex::isValidNip(1234567890));
        self::assertFalse(Regex::isValidNip(0000000000));
        self::assertFalse(Regex::isValidNip('1234567890'));
        self::assertFalse(Regex::isValidNip('0000000000'));
        self::assertFalse(Regex::isValidNip('abc'));
        self::assertFalse(Regex::isValidNip($this->simpleText));

        self::assertTrue(Regex::isValidNip('7340009469')); // Onet S.A.
        self::assertTrue(Regex::isValidNip('5252530705')); // Facebook Poland sp. z o.o.
    }

    /**
     * @param mixed $emptyValue Empty value, e.g. ""
     * @dataProvider provideEmptyValue
     */
    public function testIsValidBundleNameUsingEmptyValue($emptyValue)
    {
        self::assertFalse(Regex::isValidBundleName($emptyValue));
    }

    /**
     * @param string $bundleName Full name of bundle to verify, e.g. "MyExtraBundle"
     * @param bool   $expected   Information if it's valid name
     *
     * @dataProvider provideBundleName
     */
    public function testIsValidBundleName($bundleName, $expected)
    {
        self::assertEquals($expected, Regex::isValidBundleName($bundleName));
    }

    public function testGetBundleNamePattern()
    {
        self::assertEquals('/^(([A-Z]{1}[a-z0-9]+)((?2))*)(Bundle)$/', Regex::getBundleNamePattern());
    }

    public function testGetHtmlAttributePattern()
    {
        self::assertEquals('/([\w-]+)="([\w -]+)"/', Regex::getHtmlAttributePattern());
    }

    /**
     * @param mixed $emptyValue Empty value, e.g. ""
     * @dataProvider provideEmptyValue
     */
    public function testIsValidHtmlAttributeUsingEmptyValue($emptyValue)
    {
        self::assertFalse(Regex::isValidHtmlAttribute($emptyValue));
    }

    /**
     * @param string $htmlAttribute The html attribute to verify
     * @param bool   $expected      Information if it's valid attribute
     *
     * @dataProvider provideHtmlAttribute
     */
    public function testIsValidHtmlAttribute($htmlAttribute, $expected)
    {
        self::assertEquals($expected, Regex::isValidHtmlAttribute($htmlAttribute));
    }

    /**
     * @param mixed $emptyValue Empty value, e.g. ""
     * @dataProvider provideEmptyValue
     */
    public static function testAreValidHtmlAttributesUsingEmptyValue($emptyValue)
    {
        self::assertFalse(Regex::areValidHtmlAttributes($emptyValue));
    }

    /**
     * @param string $htmlAttributes The html attributes to verify
     * @param bool   $expected       Information if attributes are valid
     *
     * @dataProvider provideHtmlAttributes
     */
    public static function testAreValidHtmlAttributes($htmlAttributes, $expected)
    {
        self::assertEquals($expected, Regex::areValidHtmlAttributes($htmlAttributes));
    }

    /**
     * @param string $value    Value to verify
     * @param bool   $expected Information if value is a binary value
     *
     * @dataProvider provideBinaryValue
     */
    public static function testIsBinaryValue($value, $expected)
    {
        self::assertEquals($expected, Regex::isBinaryValue($value));
    }

    /**
     * @param mixed $emptyValue Empty value, e.g. ""
     * @dataProvider provideEmptyValue
     */
    public static function testIsValidEmailUsingEmptyValue($emptyValue)
    {
        self::assertFalse(Regex::isValidEmail($emptyValue));
    }

    /**
     * @param string $email    E-mail address to validate / verify
     * @param bool   $expected Information if e-mail is valid
     *
     * @dataProvider provideEmail
     */
    public static function testIsValidEmail($email, $expected)
    {
        self::assertEquals($expected, Regex::isValidEmail($email));
    }

    /**
     * @param mixed $emptyValue Empty value, e.g. ""
     * @dataProvider provideEmptyValue
     */
    public static function testIsValidTaxIdUsingEmptyValue($emptyValue)
    {
        self::assertFalse(Regex::isValidTaxId($emptyValue));
    }

    /**
     * @param string $taxIdString Tax ID (NIP) string
     * @param bool   $expected    Information if tax ID is valid
     *
     * @dataProvider provideTaxId
     */
    public static function testIsValidTaxId($taxIdString, $expected)
    {
        self::assertEquals($expected, Regex::isValidTaxId($taxIdString));
    }

    /**
     * @param mixed $emptyValue Empty value, e.g. ""
     * @dataProvider provideEmptyValue
     */
    public static function testIsValidPhoneNumberUsingEmptyValue($emptyValue)
    {
        self::assertFalse(Regex::isValidPhoneNumber($emptyValue));
    }

    /**
     * @param string $phoneNumber The phone number to validate / verify
     * @param bool   $expected    Information if phone number is valid
     *
     * @dataProvider providePhoneNumber
     */
    public static function testIsValidPhoneNumber($phoneNumber, $expected)
    {
        self::assertEquals($expected, Regex::isValidPhoneNumber($phoneNumber));
    }

    /**
     * @param string $pattern   Pattern to match
     * @param array  $dataArray The array
     * @param array  $expected  Expected array
     *
     * @dataProvider providePatternForArrayValues
     */
    public static function testGetArrayValuesByPatternUsingValues($pattern, array $dataArray, $expected)
    {
        self::assertEquals($expected, Regex::getArrayValuesByPattern($pattern, $dataArray));
    }

    /**
     * @param string $pattern   Pattern to match
     * @param array  $dataArray The array
     * @param array  $expected  Expected array
     *
     * @dataProvider providePatternForArrayKeys
     */
    public static function testGetArrayValuesByPatternUsingKeys($pattern, array $dataArray, $expected)
    {
        self::assertEquals($expected, Regex::getArrayValuesByPattern($pattern, $dataArray, true));
    }

    public static function testGetUrlPatternWithProtocolRequired()
    {
        $pattern = '/^([a-z]+:\/\/)([\da-z\.-]+)\.([a-z\.]{2,6})(\/)?([\w\.\-]*)?(\?)?([\w \.\-\/=&]*)\/?$/i';
        self::assertEquals($pattern, Regex::getUrlPattern(true));
    }

    public static function testGetUrlPatternWithoutProtocol()
    {
        $pattern = '/^([a-z]+:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})(\/)?([\w\.\-]*)?(\?)?([\w \.\-\/=&]*)\/?$/i';
        self::assertEquals($pattern, Regex::getUrlPattern());
    }

    /**
     * @param array  $array            The array that should be filtered
     * @param string $arrayColumnKey   Column name
     * @param string $filterExpression Simple filter expression, e.g. "== 2" or "!= \'home\'"
     * @param array  $expected         Expected array
     *
     * @dataProvider provideSimpleExpressionForArrayFiltering
     */
    public function testArrayFilterUsingSimpleExpression($array, $arrayColumnKey, $filterExpression, $expected)
    {
        self::assertEquals($expected, Regex::arrayFilter($array, $arrayColumnKey, $filterExpression));
    }

    /**
     * @param array  $array            The array that should be filtered
     * @param string $arrayColumnKey   Column name
     * @param string $filterExpression Regular expression, e.g. "/\d+/" or "/[a-z]+[,;]{2,}/"
     * @param array  $expected         Expected array
     *
     * @dataProvider provideRegularExpressionForArrayFiltering
     */
    public function testArrayFilterUsingRegularExpression($array, $arrayColumnKey, $filterExpression, $expected)
    {
        self::assertEquals($expected, Regex::arrayFilter($array, $arrayColumnKey, $filterExpression, true));
    }

    /**
     * @param array|string $patterns The patterns to match
     * @param string       $subject  The string to check
     * @param bool         $expected Information if given $subject matches given $patterns
     *
     * @dataProvider providePatternsAndSubjectForPregMultiMatch
     */
    public function testPregMultiMatch($patterns, $subject, $expected)
    {
        self::assertEquals($expected, Regex::pregMultiMatch($patterns, $subject));
    }

    /**
     * @param array|string $patterns The patterns to match
     * @param string       $subject  The string to check
     * @param bool         $expected Information if given $subject matches given $patterns
     *
     * @dataProvider providePatternsAndSubjectForPregMultiMatchWhenMustMatchAllPatterns
     */
    public function testPregMultiMatchWhenMustMatchAllPatterns($patterns, $subject, $expected)
    {
        self::assertEquals($expected, Regex::pregMultiMatch($patterns, $subject, true));
    }

    public function testGetMoneyPattern()
    {
        self::assertEquals('/^[-+]?\d+([\.,]{1}\d*)?$/', Regex::getMoneyPattern());
    }

    /**
     * @param mixed $emptyValue Empty value, e.g. ""
     * @dataProvider provideEmptyNonMoneyValue
     */
    public function testIsValidMoneyValueUsingEmptyValue($emptyValue)
    {
        self::assertFalse(Regex::isValidMoneyValue($emptyValue));
    }

    /**
     * @param mixed $value    Value to verify
     * @param bool  $expected Information if given value is a money value
     *
     * @dataProvider provideMoneyValue
     */
    public function testIsValidMoneyValue($value, $expected)
    {
        self::assertEquals($expected, Regex::isValidMoneyValue($value));
    }

    /**
     * @param mixed $nonScalarValue Non scalar value, e.g. [] or null
     * @dataProvider provideNonScalarValue
     */
    public function testGetValidColorHexValueUsingNonScalarValue($nonScalarValue)
    {
        self::assertFalse(Regex::getValidColorHexValue($nonScalarValue));
    }

    /**
     * @param mixed $emptyValue Empty value, e.g. ""
     * @dataProvider provideColorEmptyValue
     */
    public function testGetValidColorHexValueUsingEmptyValueWithoutException($emptyValue)
    {
        self::assertFalse(Regex::getValidColorHexValue($emptyValue, false));
    }

    /**
     * @param mixed $emptyValue Empty value, e.g. ""
     * @dataProvider provideColorEmptyValue
     */
    public function testGetValidColorHexValueUsingEmptyValue($emptyValue)
    {
        $this->setExpectedException(IncorrectColorHexLengthException::class);
        Regex::getValidColorHexValue($emptyValue);
    }

    /**
     * @param string $incorrectColor Incorrect value of color
     * @dataProvider provideColorIncorrectLength
     */
    public function testGetValidColorHexValueUsingIncorrectValueWithoutException($incorrectColor)
    {
        self::assertFalse(Regex::getValidColorHexValue($incorrectColor, false));
    }

    /**
     * @param string $incorrectColor Incorrect value of color
     * @dataProvider provideColorIncorrectLength
     */
    public function testGetValidColorHexValueUsingIncorrectValue($incorrectColor)
    {
        $this->setExpectedException(IncorrectColorHexLengthException::class);
        Regex::getValidColorHexValue($incorrectColor);
    }

    /**
     * @param string $invalidColor Invalid value of color
     * @dataProvider provideColorInvalidValue
     */
    public function testGetValidColorHexValueUsingInvalidValueWithoutException($invalidColor)
    {
        self::assertFalse(Regex::getValidColorHexValue($invalidColor, false));
    }

    /**
     * @param string $invalidColor Invalid value of color
     * @dataProvider provideColorInvalidValue
     */
    public function testGetValidColorHexValueUsingInvalidValue($invalidColor)
    {
        $this->setExpectedException(InvalidColorHexValueException::class);
        Regex::getValidColorHexValue($invalidColor);
    }

    /**
     * @param string $color    Color to verify
     * @param string $expected Expected value of color
     *
     * @dataProvider provideColor
     */
    public function testGetValidColorHexValue($color, $expected)
    {
        self::assertEquals($expected, Regex::getValidColorHexValue($color));
    }

    /**
     * @param string $value    Value that should be transformed to slug
     * @param string $expected Expected slug
     *
     * @dataProvider provideValueSlug
     */
    public function testCreateSlug($value, $expected)
    {
        self::assertSame($expected, Regex::createSlug($value));
    }

    /**
     * Provides name of bundle and information if it's valid name
     *
     * @return Generator
     */
    public function provideBundleName()
    {
        yield[
            'something',
            false,
        ];

        yield[
            'something_different',
            false,
        ];

        yield[
            'something-else',
            false,
        ];

        yield[
            'myExtraBundle',
            false,
        ];

        yield[
            'MyExtra',
            false,
        ];

        yield[
            'MyExtraBundle',
            true,
        ];

        yield[
            'MySuperExtraGorgeousBundle',
            true,
        ];
    }

    /**
     * Provides html attribute and information if it's valid
     *
     * @return Generator
     */
    public function provideHtmlAttribute()
    {
        yield[
            'abc = def',
            false,
        ];

        yield[
            'a b c=def',
            false,
        ];

        yield[
            'abc=def',
            false,
        ];

        yield[
            'a1b2c=d3e4f',
            false,
        ];

        yield[
            'abc="def"',
            true,
        ];

        yield[
            'a1b2c="d3e4f"',
            true,
        ];
    }

    /**
     * Provides html attribute and information if attributes are valid
     *
     * @return Generator
     */
    public function provideHtmlAttributes()
    {
        yield[
            'abc = def',
            false,
        ];

        yield[
            'abc = def ghi = jkl',
            false,
        ];

        yield[
            'abc=def ghi=jkl',
            false,
        ];

        yield[
            'abc=def ghi=jkl mno=pqr',
            false,
        ];

        yield[
            'abc="def"',
            true,
        ];

        yield[
            'abc="def" ghi="jkl"',
            true,
        ];

        yield[
            'abc="def" ghi="jkl" mno="pqr"',
            true,
        ];

        yield[
            'a2bc="d4ef" ghi="j k l" mno="pq9r"',
            true,
        ];
    }

    /**
     * Provides value to verify if it is a binary value
     *
     * @return Generator
     */
    public function provideBinaryValue()
    {
        $file1Path = $this->getFilePathForTesting('lorem-ipsum.txt');
        $file2Path = $this->getFilePathForTesting('minion.jpg');

        yield[
            null,
            false,
        ];

        yield[
            [],
            false,
        ];

        yield[
            '',
            false,
        ];

        yield[
            'abc',
            false,
        ];

        yield[
            '1234',
            false,
        ];

        yield[
            1234,
            false,
        ];

        yield[
            12.34,
            false,
        ];

        yield[
            fread(fopen($file1Path, 'rb'), 1),
            false,
        ];

        yield[
            fread(fopen($file2Path, 'rb'), 1),
            true,
        ];
    }

    /**
     * Provides e-mail and information if it's valid
     *
     * @return Generator
     */
    public function provideEmail()
    {
        yield[
            '1',
            false,
        ];

        yield[
            1,
            false,
        ];

        yield[
            'a@a',
            false,
        ];

        yield[
            'a@a.com',
            false,
        ];

        yield[
            'aa@a.com',
            true,
        ];

        yield[
            'a.b@d.com',
            true,
        ];
    }

    /**
     * Provides tax ID and information if it's valid
     *
     * @return Generator
     */
    public function provideTaxId()
    {
        yield[
            '123',
            false,
        ];

        yield[
            '12345',
            false,
        ];

        yield[
            '1122334455',
            false,
        ];

        yield[
            '1234567890',
            false,
        ];

        yield[
            '0987654321',
            false,
        ];

        /*
         * Microsoft sp. z o.o.
         */
        yield[
            '5270103391',
            true,
        ];

        /*
         * Onet S.A.
         */
        yield[
            '7340009469',
            true,
        ];
    }

    /**
     * Provides phone number and information if it's valid
     *
     * @return Generator
     */
    public function providePhoneNumber()
    {
        yield[
            'abc',
            false,
        ];

        yield[
            '1-2-3',
            false,
        ];

        yield[
            '123',
            true,
        ];

        yield[
            '123 456 789',
            true,
        ];

        yield[
            '123456789',
            true,
        ];
    }

    /**
     * Provides pattern and array with values that should match that pattern
     *
     * @return Generator
     */
    public function providePatternForArrayValues()
    {
        yield[
            '/\d/',
            [],
            [],
        ];

        yield[
            '/\d+/',
            [
                'lorem',
                'ipsum',
                123,
                'dolor',
                '456',
            ],
            [
                2 => 123,
                4 => '456',
            ],
        ];

        yield[
            '/\d+-[a-z]+/',
            [
                'lorem',
                123,
                false,
                'dolor',
                '456-ipsum',
            ],
            [
                4 => '456-ipsum',
            ],
        ];
    }

    /**
     * Provides pattern and array with keys that should match that pattern
     *
     * @return Generator
     */
    public function providePatternForArrayKeys()
    {
        yield[
            '/\d/',
            [],
            [],
        ];

        yield[
            '/\d+/',
            [
                'lorem' => 'ipsum',
                'dolor' => 123,
                'sit',
                4       => '456',
            ],
            [
                0 => 'sit',
                4 => '456',
            ],
        ];

        yield[
            '/\d+-[a-z]+/',
            [
                'lorem',
                '456-ipsum' => 123,
                '001-sit'   => false,
                'dolor',
            ],
            [
                '456-ipsum' => 123,
                '001-sit'   => false,
            ],
        ];
    }

    /**
     * Provides simple compare expression for array filtering and the array
     *
     * @return Generator
     */
    public function provideSimpleExpressionForArrayFiltering()
    {
        yield[
            [],
            'id',
            ' == 2',
            [],
        ];

        yield[
            [
                [
                    'id'         => 1,
                    'first_name' => 'Jane',
                    'last_name'  => 'Scott',
                    'is_active'  => true,
                ],
                [
                    'id'         => 2,
                    'first_name' => 'George',
                    'last_name'  => 'Brown',
                    'is_active'  => true,
                ],
                [
                    'id'         => 3,
                    'first_name' => 'Mike',
                    'last_name'  => 'Green',
                    'is_active'  => false,
                ],
            ],
            'birth_date',
            ' == 2',
            [
                [
                    'id'         => 1,
                    'first_name' => 'Jane',
                    'last_name'  => 'Scott',
                    'is_active'  => true,
                ],
                [
                    'id'         => 2,
                    'first_name' => 'George',
                    'last_name'  => 'Brown',
                    'is_active'  => true,
                ],
                [
                    'id'         => 3,
                    'first_name' => 'Mike',
                    'last_name'  => 'Green',
                    'is_active'  => false,
                ],
            ],
        ];

        yield[
            [
                [
                    'id'         => 1,
                    'first_name' => 'Jane',
                    'last_name'  => 'Scott',
                    'is_active'  => true,
                ],
                [
                    'id'         => 2,
                    'first_name' => 'George',
                    'last_name'  => 'Brown',
                    'is_active'  => true,
                ],
                [
                    'id'         => 3,
                    'first_name' => 'Mike',
                    'last_name'  => 'Green',
                    'is_active'  => false,
                ],
            ],
            'id',
            ' == 2',
            [
                1 => [
                    'id'         => 2,
                    'first_name' => 'George',
                    'last_name'  => 'Brown',
                    'is_active'  => true,
                ],
            ],
        ];

        yield[
            [
                [
                    'id'         => 1,
                    'first_name' => 'Jane',
                    'last_name'  => 'Scott',
                    'is_active'  => true,
                ],
                [
                    'id'         => 2,
                    'first_name' => 'George',
                    'last_name'  => 'Brown',
                    'is_active'  => true,
                ],
                [
                    'id'         => 3,
                    'first_name' => 'Mike',
                    'last_name'  => 'Green',
                    'is_active'  => false,
                ],
            ],
            'id',
            ' >= 2',
            [
                1 => [
                    'id'         => 2,
                    'first_name' => 'George',
                    'last_name'  => 'Brown',
                    'is_active'  => true,
                ],
                2 => [
                    'id'         => 3,
                    'first_name' => 'Mike',
                    'last_name'  => 'Green',
                    'is_active'  => false,
                ],
            ],
        ];

        yield[
            [
                [
                    'id'         => 1,
                    'first_name' => 'Jane',
                    'last_name'  => 'Scott',
                    'is_active'  => true,
                ],
                [
                    'id'         => 2,
                    'first_name' => 'George',
                    'last_name'  => 'Brown',
                    'is_active'  => true,
                ],
                [
                    'id'         => 3,
                    'first_name' => 'Mike',
                    'last_name'  => 'Green',
                    'is_active'  => false,
                ],
            ],
            'is_active',
            ' !== true',
            [
                2 => [
                    'id'         => 3,
                    'first_name' => 'Mike',
                    'last_name'  => 'Green',
                    'is_active'  => false,
                ],
            ],
        ];

        yield[
            [
                [
                    'id'         => 1,
                    'first_name' => 'Jane',
                    'last_name'  => 'Scott',
                    'is_active'  => true,
                ],
                [
                    'id'         => 2,
                    'first_name' => 'George',
                    'last_name'  => 'Brown',
                    'is_active'  => true,
                ],
                [
                    'id'         => 3,
                    'first_name' => 'Mike',
                    'last_name'  => 'Green',
                    'is_active'  => false,
                ],
            ],
            'first_name',
            ' == \'Mike\'',
            [
                2 => [
                    'id'         => 3,
                    'first_name' => 'Mike',
                    'last_name'  => 'Green',
                    'is_active'  => false,
                ],
            ],
        ];
    }

    /**
     * Provides regular expression for array filtering and the array
     *
     * @return Generator
     */
    public function provideRegularExpressionForArrayFiltering()
    {
        yield[
            [],
            'id',
            '/\d+/',
            [],
        ];

        yield[
            [
                [
                    'id'         => 1,
                    'first_name' => 'Jane',
                    'last_name'  => 'Scott',
                    'is_active'  => true,
                ],
                [
                    'id'         => 2,
                    'first_name' => 'George',
                    'last_name'  => 'Brown',
                    'is_active'  => true,
                ],
                [
                    'id'         => 3,
                    'first_name' => 'Mike',
                    'last_name'  => 'Green',
                    'is_active'  => false,
                ],
            ],
            'birth_date',
            '/\d+/',
            [
                [
                    'id'         => 1,
                    'first_name' => 'Jane',
                    'last_name'  => 'Scott',
                    'is_active'  => true,
                ],
                [
                    'id'         => 2,
                    'first_name' => 'George',
                    'last_name'  => 'Brown',
                    'is_active'  => true,
                ],
                [
                    'id'         => 3,
                    'first_name' => 'Mike',
                    'last_name'  => 'Green',
                    'is_active'  => false,
                ],
            ],
        ];

        yield[
            [
                [
                    'id'         => 1,
                    'first_name' => 'Jane',
                    'last_name'  => 'Scott',
                    'is_active'  => true,
                ],
                [
                    'id'         => 123,
                    'first_name' => 'George',
                    'last_name'  => 'Brown',
                    'is_active'  => true,
                ],
                [
                    'id'         => 3,
                    'first_name' => 'Mike',
                    'last_name'  => 'Green',
                    'is_active'  => false,
                ],
            ],
            'id',
            '/\d{3}/',
            [
                1 => [
                    'id'         => 123,
                    'first_name' => 'George',
                    'last_name'  => 'Brown',
                    'is_active'  => true,
                ],
            ],
        ];

        yield[
            [
                [
                    'id'         => 1,
                    'first_name' => 'Jane',
                    'last_name'  => 'Scott',
                    'is_active'  => true,
                ],
                [
                    'id'         => 123,
                    'first_name' => 'George',
                    'last_name'  => 'Brown',
                    'is_active'  => true,
                ],
                [
                    'id'         => 456,
                    'first_name' => 'Mike',
                    'last_name'  => 'Green',
                    'is_active'  => false,
                ],
            ],
            'first_name',
            '/George|Mike/',
            [
                1 => [
                    'id'         => 123,
                    'first_name' => 'George',
                    'last_name'  => 'Brown',
                    'is_active'  => true,
                ],
                2 => [
                    'id'         => 456,
                    'first_name' => 'Mike',
                    'last_name'  => 'Green',
                    'is_active'  => false,
                ],
            ],
        ];

        yield[
            [
                [
                    'id'         => 1,
                    'first_name' => 'Jane',
                    'last_name'  => 'Scott',
                    'is_active'  => true,
                ],
                [
                    'id'         => 2,
                    'first_name' => 'George',
                    'last_name'  => 'Brown',
                    'is_active'  => true,
                ],
                [
                    'id'         => 3,
                    'first_name' => 'Mike',
                    'last_name'  => 'Green-Blue',
                    'is_active'  => false,
                ],
            ],
            'last_name',
            '/\w+-\w+/',
            [
                2 => [
                    'id'         => 3,
                    'first_name' => 'Mike',
                    'last_name'  => 'Green-Blue',
                    'is_active'  => false,
                ],
            ],
        ];
    }

    /**
     * Provides patterns and subject for the pregMultiMatch() method
     *
     * @return Generator
     */
    public function providePatternsAndSubjectForPregMultiMatch()
    {
        yield[
            '',
            '',
            false,
        ];

        yield[
            [],
            '',
            false,
        ];

        yield[
            '/\d+/',
            'Lorem ipsum dolor sit',
            false,
        ];

        yield[
            [
                '/\d+/',
                '/^[a-z]{4}$/',
            ],
            'Lorem ipsum dolor sit',
            false,
        ];

        yield[
            '/\w+/',
            'Lorem ipsum dolor sit',
            true,
        ];

        yield[
            [
                '/\d+/',
                '/\w+/',
            ],
            'Lorem ipsum dolor sit',
            true,
        ];
    }

    /**
     * Provides patterns and subject for the pregMultiMatch() method when must match all patterns
     *
     * @return Generator
     */
    public function providePatternsAndSubjectForPregMultiMatchWhenMustMatchAllPatterns()
    {
        yield[
            '',
            '',
            false,
        ];

        yield[
            [],
            '',
            false,
        ];

        yield[
            '/\d+/',
            'Lorem ipsum dolor sit',
            false,
        ];

        yield[
            [
                '/\d+/',
                '/^[a-z]{4}$/',
            ],
            'Lorem ipsum dolor sit',
            false,
        ];

        yield[
            '/\w+/',
            'Lorem ipsum dolor sit',
            true,
        ];

        yield[
            [
                '/[a-zA-Z ]+/',
                '/\w+/',
            ],
            'Lorem ipsum dolor sit',
            true,
        ];
    }

    /**
     * Provides empty non money-related value
     *
     * @return Generator
     */
    public function provideEmptyNonMoneyValue()
    {
        yield[''];
        yield['   '];
        yield[null];
        yield[false];
        yield[[]];
    }

    /**
     * Provides money-related value and information if the value is valid
     *
     * @return Generator
     */
    public function provideMoneyValue()
    {
        yield[
            'abc',
            false,
        ];

        yield[
            '-a.b',
            false,
        ];

        yield[
            'a,b',
            false,
        ];

        yield[
            0,
            true,
        ];

        yield[
            1,
            true,
        ];

        yield[
            -1,
            true,
        ];

        yield[
            1.2,
            true,
        ];

        yield[
            1.202,
            true,
        ];

        yield[
            -1.202,
            true,
        ];

        yield[
            '0',
            true,
        ];

        yield[
            '1',
            true,
        ];

        yield[
            '-1',
            true,
        ];

        yield[
            '1.2',
            true,
        ];

        yield[
            '1.202',
            true,
        ];

        yield[
            '-1.202',
            true,
        ];

        yield[
            '1,202',
            true,
        ];

        yield[
            '-1,2',
            true,
        ];

        yield[
            '-1,202',
            true,
        ];
    }

    /**
     * Provides value of color with incorrect length
     *
     * @return Generator
     */
    public function provideColorIncorrectLength()
    {
        yield[
            '12',
        ];

        yield[
            '1234',
        ];

        yield[
            '12345678',
        ];

        yield[
            '#12',
        ];

        yield[
            '#1234',
        ];

        yield[
            '#12345678',
        ];
    }

    /**
     * Provides invalid value of color
     *
     * @return Generator
     */
    public function provideColorInvalidValue()
    {
        yield[
            '#qwerty',
        ];

        yield[
            'qwerty',
        ];
    }

    /**
     * Provides empty non color-related value
     *
     * @return Generator
     */
    public function provideColorEmptyValue()
    {
        yield[
            '',
        ];

        yield[
            0,
        ];

        yield[
            '0',
        ];

        yield[
            false,
        ];
    }

    /**
     * Provides value of color
     *
     * @return Generator
     */
    public function provideColor()
    {
        yield[
            '#1b0',
            '11bb00',
        ];

        yield[
            '#1B0',
            '11bb00',
        ];

        yield[
            '#1ab1ab',
            '1ab1ab',
        ];

        yield[
            '#1AB1AB',
            '1ab1ab',
        ];

        yield[
            '#000',
            '000000',
        ];
    }

    /**
     * Provide value to create slug
     *
     * @return Generator
     */
    public function provideValueSlug()
    {
        yield[
            [],
            false,
        ];

        yield[
            null,
            false,
        ];

        yield[
            '',
            '',
        ];

        yield[
            1234,
            '1234',
        ];

        yield[
            '1234',
            '1234',
        ];

        yield[
            '1/2/3/4',
            '1234',
        ];

        yield[
            '1 / 2 / 3 / 4',
            '1-2-3-4',
        ];

        yield[
            'test',
            'test',
        ];

        yield[
            'test test',
            'test-test',
        ];

        yield[
            'lorem ipsum dolor sit',
            'lorem-ipsum-dolor-sit',
        ];

        yield[
            'Lorem ipsum. Dolor sit 12.34 amet.',
            'lorem-ipsum-dolor-sit-1234-amet',
        ];

        yield[
            'Was sind Löwen, Bären, Vögel und Käfer (für die Prüfung)?',
            'was-sind-lowen-baren-vogel-und-kafer-fur-die-prufung',
        ];

        yield[
            'äöü (ÄÖÜ)',
            'aou-aou',
        ];

        yield[
            'Półka dębowa. Kolor: żółędziowy. Wymiary: 80 x 30 cm.',
            'polka-debowa-kolor-zoledziowy-wymiary-80-x-30-cm',
        ];

        yield[
            'ąęółńśżźć (ĄĘÓŁŃŚŻŹĆ)',
            'aeolnszzc-aeolnszzc',
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->simpleText = 'lorem ipsum dolor sit';
        $simpleUppercase = ucwords($this->simpleText);
        $this->camelCaseText = str_replace(' ', '', lcfirst($simpleUppercase)); // 'loremIpsumDolorSit'
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        parent::tearDown();
        unset($this->simpleText, $this->camelCaseText);
    }
}
