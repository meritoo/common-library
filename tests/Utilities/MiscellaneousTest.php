<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Test\Common\Utilities;

use Generator;
use Meritoo\Common\Exception\Regex\IncorrectColorHexLengthException;
use Meritoo\Common\Exception\Regex\InvalidColorHexValueException;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Utilities\Locale;
use Meritoo\Common\Utilities\Miscellaneous;
use stdClass;

/**
 * Test case of the Miscellaneous methods (only static functions)
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class MiscellaneousTest extends BaseTestCase
{
    private $stringSmall;
    private $stringCommaSeparated;
    private $stringDotSeparated;
    private $stringWithoutSpaces;

    public function testConstructor()
    {
        static::assertHasNoConstructor(Miscellaneous::class);
    }

    public function testGetDirectoryContent()
    {
        $directoryPath = __DIR__ . '/../';
        $filePath = __FILE__;

        self::assertNull(Miscellaneous::getDirectoryContent(null));
        self::assertNull(Miscellaneous::getDirectoryContent(''));

        self::assertGreaterThanOrEqual(0, count(Miscellaneous::getDirectoryContent($directoryPath)));
        self::assertGreaterThanOrEqual(0, count(Miscellaneous::getDirectoryContent($directoryPath, true)));
        self::assertGreaterThanOrEqual(0, count(Miscellaneous::getDirectoryContent($directoryPath, true, 5)));

        self::assertGreaterThanOrEqual(0, count(Miscellaneous::getDirectoryContent($filePath)));
        self::assertGreaterThanOrEqual(0, count(Miscellaneous::getDirectoryContent($filePath, true)));
    }

    public function testCheckboxValue2Boolean()
    {
        self::assertTrue(Miscellaneous::checkboxValue2Boolean('on'));
        self::assertFalse(Miscellaneous::checkboxValue2Boolean('  off'));
        self::assertFalse(Miscellaneous::checkboxValue2Boolean(null));
    }

    public function testCheckboxValue2Integer()
    {
        self::assertEquals(1, Miscellaneous::checkboxValue2Integer('on'));
        self::assertEquals(0, Miscellaneous::checkboxValue2Integer('  off'));
        self::assertEquals(0, Miscellaneous::checkboxValue2Integer(null));
    }

    public function testGetFileExtension()
    {
        $fileName = 'Lorem.ipsum-dolor.sit.JPG';
        self::assertEquals('JPG', Miscellaneous::getFileExtension($fileName));
        self::assertEquals('jpg', Miscellaneous::getFileExtension($fileName, true));
    }

    /**
     * @param string $fileName Empty value, e.g. ""
     * @dataProvider provideEmptyValue
     */
    public function testGetFileNameWithoutExtensionEmptyValue($fileName)
    {
        self::assertEquals('', Miscellaneous::getFileNameWithoutExtension($fileName));
    }

    /**
     * @param string $fileName         The file name with extension
     * @param string $withoutExtension The file name without extension
     *
     * @dataProvider provideFileNames
     */
    public function testGetFileNameWithoutExtension($fileName, $withoutExtension)
    {
        self::assertEquals($withoutExtension, Miscellaneous::getFileNameWithoutExtension($fileName));
    }

    public function testGetFileNameFromPath()
    {
        /*
         * Path with file
         */
        self::assertEquals('sit.amet.JPG', Miscellaneous::getFileNameFromPath('lorem/ipsum-dolor/sit.amet.JPG'));

        /*
         * Path without file
         */
        self::assertEquals('', Miscellaneous::getFileNameFromPath('lorem/ipsum-dolor/sit-amet'));

        /*
         * Path with a dot "." in name of directory
         */
        self::assertEquals('sit.amet.JPG', Miscellaneous::getFileNameFromPath('lorem/ipsum.dolor/sit.amet.JPG'));

        /*
         * Relative path
         */
        self::assertEquals('sit.amet.JPG', Miscellaneous::getFileNameFromPath('lorem/ipsum/../dolor/sit.amet.JPG'));
    }

    public function testGetUniqueFileName()
    {
        $originalFileName = 'Lorem.ipsum-dolor.sit.JPG';
        $pattern = '|^lorem\-ipsum\-dolor\-sit\-[a-z0-9.-]+\.jpg$|';

        /*
         * With object ID
         */
        $uniqueFileName1 = Miscellaneous::getUniqueFileName($originalFileName, 123);

        /*
         * Without object ID
         */
        $uniqueFileName2 = Miscellaneous::getUniqueFileName($originalFileName);

        $isCorrect1 = (bool)preg_match($pattern, $uniqueFileName1);
        $isCorrect2 = (bool)preg_match($pattern, $uniqueFileName2);

        self::assertTrue($isCorrect1);
        self::assertTrue($isCorrect2);
    }

    public function testValue2NonNegativeInteger()
    {
        self::assertEquals(2, Miscellaneous::value2NonNegativeInteger('2'));
        self::assertEquals(0, Miscellaneous::value2NonNegativeInteger('a'));
        self::assertEquals('-', Miscellaneous::value2NonNegativeInteger('-4', '-'));
    }

    public function testIsPhpModuleLoaded()
    {
        $loadedExtensions = get_loaded_extensions();
        $firstExtension = $loadedExtensions[0];

        self::assertTrue(Miscellaneous::isPhpModuleLoaded($firstExtension));
        self::assertFalse(Miscellaneous::isPhpModuleLoaded('xyz123'));
    }

    /**
     * @param mixed $string Empty value, e.g. ""
     * @dataProvider provideEmptyValue
     */
    public function testToLatinEmptyValue($string)
    {
        self::assertEquals('', Miscellaneous::toLatin($string));
    }

    /**
     * @param string $expected        Expected/converted string
     * @param string $string          String to convert
     * @param string $replacementChar (optional) Replacement character for all non-latin characters
     *
     * @dataProvider provideStringToLatinNotLowerCaseHuman
     */
    public function testToLatinNotLowerCaseHuman($expected, $string, $replacementChar = '-')
    {
        self::assertEquals($expected, Miscellaneous::toLatin($string, false, $replacementChar));
    }

    /**
     * @param string $expected        Expected/converted string
     * @param string $string          String to convert
     * @param string $replacementChar (optional) Replacement character for all non-latin characters
     *
     * @dataProvider provideStringToLatinLowerCaseHuman
     */
    public function testToLatinLowerCaseHuman($expected, $string, $replacementChar = '-')
    {
        self::assertEquals($expected, Miscellaneous::toLatin($string, true, $replacementChar));
    }

    public function testGetUniqueString()
    {
        $prefix = '';
        $hashed = false;
        self::assertEquals(23, strlen(Miscellaneous::getUniqueString($prefix, $hashed)));

        $prefix = 'xyz';
        $hashed = false;
        self::assertEquals(26, strlen(Miscellaneous::getUniqueString($prefix, $hashed)));

        $hashed = true;
        self::assertEquals(40, strlen(Miscellaneous::getUniqueString($prefix, $hashed)));
    }

    /**
     * @param string|array $search An empty value to find
     * @dataProvider provideEmptyValue
     */
    public function testReplaceEmptyValue($search)
    {
        $replacement1 = '';
        $replacement2 = [];

        $replacement3 = [
            'commodo',
            'interdum',
        ];

        self::assertEquals($this->stringSmall, Miscellaneous::replace($this->stringSmall, $search, $replacement1));
        self::assertEquals($this->stringSmall, Miscellaneous::replace($this->stringSmall, $search, $replacement2));
        self::assertEquals($this->stringSmall, Miscellaneous::replace($this->stringSmall, $search, $replacement3));

        self::assertEquals($this->stringSmall, Miscellaneous::replace($this->stringSmall, $search, $replacement1, true));
        self::assertEquals($this->stringSmall, Miscellaneous::replace($this->stringSmall, $search, $replacement2, true));
        self::assertEquals($this->stringSmall, Miscellaneous::replace($this->stringSmall, $search, $replacement3, true));
    }

    /**
     * @param string       $description  Description of test
     * @param string|array $subject      The string or an array of strings to search and replace
     * @param string|array $search       String or pattern or array of patterns to find. It may be: string, an array
     *                                   of strings or an array of patterns.
     * @param string|array $replacement  The string or an array of strings to replace. It may be: string or an array
     *                                   of strings.
     * @param  mixed       $result       Result of replacing
     *
     * @dataProvider provideEmptyValuesToReplace
     */
    public function testReplaceUsingEmptyValues($description, $subject, $search, $replacement, $result)
    {
        static::assertSame($result, Miscellaneous::replace($subject, $search, $replacement), $description);
    }

    /**
     * @param string $description Description of test
     * @param string $subject     The string or an array of strings to search and replace
     * @param string $search      String or pattern or array of patterns to find. It may be: string, an array of
     *                            strings or an array of patterns.
     * @param string $replacement The string or an array of strings to replace. It may be: string or an array of
     *                            strings.
     * @param  mixed $result      Result of replacing
     *
     * @dataProvider provideStringsToReplace
     */
    public function testReplaceUsingStrings($description, $subject, $search, $replacement, $result)
    {
        static::assertSame($result, Miscellaneous::replace($subject, $search, $replacement), $description);
    }

    /**
     * @param string $description Description of test
     * @param string $subject     The string or an array of strings to search and replace
     * @param string $search      String or pattern or array of patterns to find. It may be: string, an array of
     *                            strings or an array of patterns.
     * @param string $replacement The string or an array of strings to replace. It may be: string or an array of
     *                            strings.
     * @param  mixed $result      Result of replacing
     *
     * @dataProvider provideRegexToReplace
     */
    public function testReplaceUsingRegex($description, $subject, $search, $replacement, $result)
    {
        static::assertSame($result, Miscellaneous::replace($subject, $search, $replacement), $description);
    }

    /**
     * @param string $description Description of test
     * @param string $subject     The string or an array of strings to search and replace
     * @param string $search      String or pattern or array of patterns to find. It may be: string, an array of
     *                            strings or an array of patterns.
     * @param string $replacement The string or an array of strings to replace. It may be: string or an array of
     *                            strings.
     * @param  mixed $result      Result of replacing
     *
     * @dataProvider provideDataToReplaceWithQuoteStrings
     */
    public function testReplaceWithQuoteStrings($description, $subject, $search, $replacement, $result)
    {
        static::assertSame($result, Miscellaneous::replace($subject, $search, $replacement, true), $description);
    }

    public function testUppercaseFirst()
    {
        self::assertEquals('', Miscellaneous::uppercaseFirst(''));
        self::assertEquals('', Miscellaneous::uppercaseFirst(null));
        self::assertEquals('', Miscellaneous::uppercaseFirst(false));

        $text = 'lorEM ipsum dolor sit Amet';
        self::assertEquals('LorEM ipsum dolor sit Amet', Miscellaneous::uppercaseFirst($text));

        $restLowercase = true;
        self::assertEquals('Lorem ipsum dolor sit amet', Miscellaneous::uppercaseFirst($text, $restLowercase));

        $restLowercase = false;
        self::assertEquals('LOREM IPSUM DOLOR SIT AMET', Miscellaneous::uppercaseFirst($text, $restLowercase));
    }

    public function testLowercaseFirst()
    {
        self::assertEquals('', Miscellaneous::lowercaseFirst(''));
        self::assertEquals('', Miscellaneous::lowercaseFirst(null));
        self::assertEquals('', Miscellaneous::lowercaseFirst(false));

        $text = 'LorEM ipsum dolor sit Amet';
        self::assertEquals('lorEM ipsum dolor sit Amet', Miscellaneous::lowercaseFirst($text));

        $restLowercase = true;
        self::assertEquals('lorem ipsum dolor sit amet', Miscellaneous::lowercaseFirst($text, $restLowercase));

        $restLowercase = false;
        self::assertEquals('lOREM IPSUM DOLOR SIT AMET', Miscellaneous::lowercaseFirst($text, $restLowercase));
    }

    public function testGetNewFileName()
    {
        self::assertEquals('test.jpg', Miscellaneous::getNewFileName('test.jpg', '', ''));
        self::assertEquals('my-test.jpg', Miscellaneous::getNewFileName('test.jpg', 'my-', ''));
        self::assertEquals('test-file.jpg', Miscellaneous::getNewFileName('test.jpg', '', '-file'));
        self::assertEquals('my-test-file.jpg', Miscellaneous::getNewFileName('test.jpg', 'my-', '-file'));
    }

    public function testGetOperatingSystemNameServer()
    {
        /*
         * While running Docker OS is a Linux
         */
        self::assertEquals('Linux', Miscellaneous::getOperatingSystemNameServer());
    }

    public function testSubstringToWord()
    {
        $suffix = '...';

        self::assertEquals('Lorem ipsum' . $suffix, Miscellaneous::substringToWord($this->stringCommaSeparated, 20));
        self::assertEquals('Lorem ipsum dolor sit' . $suffix, Miscellaneous::substringToWord($this->stringCommaSeparated, 25));

        self::assertEquals('Lorem ipsum dolor', Miscellaneous::substringToWord($this->stringCommaSeparated, 20, ''));
        self::assertEquals('Lorem ipsum dolor sit amet, consectetur', Miscellaneous::substringToWord($this->stringCommaSeparated, 40, ''));
    }

    public function testBreakLongText()
    {
        self::assertEquals('Lorem ipsum dolor sit<br>amet, consectetur<br>adipiscing<br>elit', Miscellaneous::breakLongText($this->stringCommaSeparated, 20));
        self::assertEquals('Lorem ipsum dolor sit---amet, consectetur---adipiscing---elit', Miscellaneous::breakLongText($this->stringCommaSeparated, 20, '---'));
        self::assertEquals('LoremIpsum<br>DolorSitAm<br>etConsecte<br>turAdipisc<br>ingElit', Miscellaneous::breakLongText($this->stringWithoutSpaces, 10));
    }

    public function testRemoveDirectoryUsingNotExistingDirectory()
    {
        self::assertNull(Miscellaneous::removeDirectory('/abc/def/ghi'));
    }

    public function testRemoveDirectoryUsingNoDirectory()
    {
        $directoryPath = sys_get_temp_dir() . '/ipsum.txt';
        touch($directoryPath);
        self::assertTrue(Miscellaneous::removeDirectory($directoryPath));
    }

    public function testRemoveDirectoryUsingSimpleDirectory()
    {
        $directoryPath = sys_get_temp_dir() . '/lorem/ipsum';
        mkdir($directoryPath, 0777, true);
        self::assertTrue(Miscellaneous::removeDirectory($directoryPath));
    }

    public function testRemoveDirectoryUsingComplexDirectory()
    {
        $directory1Path = sys_get_temp_dir() . '/lorem/ipsum';
        $directory2Path = sys_get_temp_dir() . '/lorem/dolor/sit';

        mkdir($directory1Path, 0777, true);
        mkdir($directory2Path, 0777, true);

        self::assertTrue(Miscellaneous::removeDirectory(sys_get_temp_dir() . '/lorem'));
    }

    /**
     * @param string $text Empty value, e.g. ""
     * @dataProvider provideEmptyValue
     */
    public function testRemoveStartingDirectorySeparatorEmptyValue($text)
    {
        self::assertEquals('', Miscellaneous::removeStartingDirectorySeparator($text));
    }

    /**
     * @param string $text      Text that may contain a directory's separator at the start / beginning
     * @param string $separator The directory's separator, e.g. "/"
     * @param string $expected  Text without the starting / beginning directory's separator
     *
     * @dataProvider providePathsToRemoveStartingDirectorySeparator
     */
    public function testRemoveStartingDirectorySeparator($text, $separator, $expected)
    {
        self::assertEquals($expected, Miscellaneous::removeStartingDirectorySeparator($text, $separator));
    }

    /**
     * @param string $text Empty value, e.g. ""
     * @dataProvider provideEmptyValue
     */
    public function testRemoveEndingDirectorySeparatorEmptyValue($text)
    {
        self::assertEquals('', Miscellaneous::removeEndingDirectorySeparator($text));
    }

    /**
     * @param string $text      Text that may contain a directory's separator at the end
     * @param string $separator (optional) The directory's separator, e.g. "/"
     * @param string $expected  Text without the ending directory's separator
     *
     * @dataProvider providePathsToRemoveEndingDirectorySeparator
     */
    public function testRemoveEndingDirectorySeparator($text, $separator, $expected)
    {
        self::assertEquals($expected, Miscellaneous::removeEndingDirectorySeparator($text, $separator));
    }

    public function testIsDecimal()
    {
        self::assertTrue(Miscellaneous::isDecimal(1.2));
        self::assertTrue(Miscellaneous::isDecimal('1.2'));
        self::assertFalse(Miscellaneous::isDecimal('a'));
        self::assertFalse(Miscellaneous::isDecimal(1));
    }

    public function testIsFilePath()
    {
        $filePath = __FILE__;
        $directoryPath = dirname($filePath);

        self::assertTrue(Miscellaneous::isFilePath($filePath));
        self::assertFalse(Miscellaneous::isFilePath($directoryPath));
    }

    /**
     * @param string $string The string to convert e.g. this-is-eXamplE (return: thisIsExample)
     * @dataProvider provideEmptyValue
     */
    public function testGetCamelCaseEmptyValue($string)
    {
        self::assertEquals('', Miscellaneous::getCamelCase($string));
    }

    /**
     * @param string $string    The string to convert e.g. this-is-eXamplE (return: thisIsExample)
     * @param string $separator (optional) Separator used to find parts of the string, e.g. '-' or ','
     * @param string $expected  String in camel case
     *
     * @dataProvider provideStringToCamelCase
     */
    public function testGetCamelCase($string, $separator, $expected)
    {
        self::assertEquals($expected, Miscellaneous::getCamelCase($string, $separator));
    }

    public function testQuoteValue()
    {
        self::assertEquals(123, Miscellaneous::quoteValue(123));
        self::assertEquals('\'lorem ipsum\'', Miscellaneous::quoteValue('lorem ipsum'));
        self::assertEquals('"lorem ipsum"', Miscellaneous::quoteValue('lorem ipsum', false));
    }

    public function testGetHumanReadableSize()
    {
        Locale::setLocale(LC_ALL, 'en', 'US');

        self::assertEquals('400 B', Miscellaneous::getHumanReadableSize(400));
        self::assertEquals('1 KB', Miscellaneous::getHumanReadableSize(1024));
        self::assertEquals('1 MB', Miscellaneous::getHumanReadableSize(1024 * 1024));
        self::assertEquals('1.75 MB', Miscellaneous::getHumanReadableSize(1024 * 1024 * 1.75));
    }

    public function testGetLastElementOfString()
    {
        self::assertEquals('elit', Miscellaneous::getLastElementOfString($this->stringCommaSeparated, ' '));
        self::assertEquals('consectetur adipiscing elit', Miscellaneous::getLastElementOfString($this->stringCommaSeparated, ','));
        self::assertEquals(null, Miscellaneous::getLastElementOfString($this->stringCommaSeparated, ';'));
        self::assertEquals(null, Miscellaneous::getLastElementOfString($this->stringCommaSeparated, '.'));
    }

    public function testTrimSmart()
    {
        self::assertNull(Miscellaneous::trimSmart(null));
        self::assertEquals(' ', Miscellaneous::trimSmart(' '));
        self::assertEquals('lorem ipsum', Miscellaneous::trimSmart(' lorem ipsum'));
        self::assertEquals('lorem ipsum', Miscellaneous::trimSmart(' lorem ipsum     '));
    }

    /**
     * @param mixed $emptyPaths Empty paths co concatenate
     * @dataProvider provideEmptyValue
     */
    public function testConcatenatePathsWithEmptyPaths($emptyPaths)
    {
        self::assertEquals('', Miscellaneous::concatenatePaths($emptyPaths));
    }

    public function testConcatenatePathsWithOneEmptyPath()
    {
        $paths = [
            'first/directory',
            'second/one',
            '',
            'and/the/third',
        ];

        $concatenated = Miscellaneous::concatenatePaths($paths);
        unset($paths[2]);
        $imploded = implode('/', $paths);

        self::assertEquals('/' . $imploded, $concatenated);
    }

    public function testConcatenatePathsInNixOs()
    {
        /*
         * For *nix operating system
         */
        $paths1 = [
            'first/directory',
            'second/one',
            'and/the/third',
        ];

        self::assertEquals('/' . implode('/', $paths1), Miscellaneous::concatenatePaths($paths1));
        self::assertEquals('/' . implode('/', $paths1), Miscellaneous::concatenatePaths($paths1[0], $paths1[1], $paths1[2]));
    }

    public function testConcatenatePathsInWindowsOs()
    {
        /*
         * For Windows operating system
         */
        $paths2 = [
            'C:\first\directory',
            'second\one',
            'and\the\third',
        ];

        self::assertEquals(implode('\\', $paths2), Miscellaneous::concatenatePaths($paths2));
    }

    public function testIncludeFileExtension()
    {
        $fileName = 'lorem-ipsum.jpg';

        self::assertEquals($fileName, Miscellaneous::includeFileExtension($fileName, 'jpg'));
        self::assertEquals(sprintf('%s.%s', $fileName, 'txt'), Miscellaneous::includeFileExtension($fileName, 'txt'));
    }

    public function testGetStringElements()
    {
        $elements = [
            'Lorem ipsum dolor sit amet',
            ' consectetur adipiscing elit',
        ];

        self::assertEquals($elements, Miscellaneous::getStringElements($this->stringCommaSeparated, ','));
        self::assertEquals([], Miscellaneous::getStringElements($this->stringCommaSeparated, ';'));
    }

    public function testGetStringWithoutLastElement()
    {
        self::assertEquals('Lorem ipsum dolor sit', Miscellaneous::getStringWithoutLastElement($this->stringSmall, ' '));
        self::assertEquals('', Miscellaneous::getStringWithoutLastElement($this->stringSmall, ';'));
    }

    public function testGetSafelyGlobalVariable()
    {
        self::assertEquals('', Miscellaneous::getSafelyGlobalVariable(INPUT_GET, 'lorem'));
        self::assertEquals('', Miscellaneous::getSafelyGlobalVariable(INPUT_POST, 'lorem'));
        self::assertEquals('', Miscellaneous::getSafelyGlobalVariable(INPUT_COOKIE, 'lorem'));
        self::assertEquals('', Miscellaneous::getSafelyGlobalVariable(INPUT_SERVER, 'lorem'));
        self::assertEquals('', Miscellaneous::getSafelyGlobalVariable(INPUT_ENV, 'lorem'));

        $_GET['lorem'] = 123;
        self::assertEquals(123, Miscellaneous::getSafelyGlobalVariable(INPUT_GET, 'lorem'));
    }

    public function testIsBetween()
    {
        /*
         * Negative cases
         */
        self::assertFalse(Miscellaneous::isBetween(0, 0, 0));
        self::assertFalse(Miscellaneous::isBetween('0', '0', '0'));
        self::assertFalse(Miscellaneous::isBetween(0, 0, 1));
        self::assertFalse(Miscellaneous::isBetween(-1, -1, -1));
        self::assertFalse(Miscellaneous::isBetween(1.2, 0.1, 1.1));

        /*
         * Positive cases
         */
        self::assertTrue(Miscellaneous::isBetween(1, 0, 2));
        self::assertTrue(Miscellaneous::isBetween('1', '0', '2'));
        self::assertTrue(Miscellaneous::isBetween(-1, -2, 2));
        self::assertTrue(Miscellaneous::isBetween(1.1, 0.1, 1.2));
    }

    public function testGetType()
    {
        self::assertEquals('NULL', Miscellaneous::getType(null));
        self::assertEquals('string', Miscellaneous::getType($this->stringSmall));
        self::assertEquals('integer', Miscellaneous::getType(123));
        self::assertEquals('double', Miscellaneous::getType(1.23));
        self::assertEquals('array', Miscellaneous::getType([]));
        self::assertEquals('stdClass', Miscellaneous::getType(new stdClass()));
        self::assertEquals(__CLASS__, Miscellaneous::getType(new self()));
    }

    public function testGetValidColorComponent()
    {
        /*
         * Negative cases
         */
        self::assertEquals(0, Miscellaneous::getValidColorComponent(null));
        self::assertEquals(0, Miscellaneous::getValidColorComponent(''));
        self::assertEquals(0, Miscellaneous::getValidColorComponent('0'));
        self::assertEquals(0, Miscellaneous::getValidColorComponent(0));
        self::assertEquals(0, Miscellaneous::getValidColorComponent(256));
        self::assertEquals(0, Miscellaneous::getValidColorComponent(256, false));

        /*
         * Positive cases - part 1
         */
        self::assertEquals(1, Miscellaneous::getValidColorComponent(1));
        self::assertEquals('0a', Miscellaneous::getValidColorComponent(10));
        self::assertEquals('0f', Miscellaneous::getValidColorComponent(15));
        self::assertEquals(64, Miscellaneous::getValidColorComponent(100));
        self::assertEquals('ff', Miscellaneous::getValidColorComponent(255));

        /*
         * Positive cases - part 2
         */
        self::assertEquals(1, Miscellaneous::getValidColorComponent(1, false));
        self::assertEquals(10, Miscellaneous::getValidColorComponent(10, false));
        self::assertEquals(15, Miscellaneous::getValidColorComponent(15, false));
        self::assertEquals(100, Miscellaneous::getValidColorComponent(100, false));
        self::assertEquals(255, Miscellaneous::getValidColorComponent(255, false));
    }

    public function testGetInvertedColorWithIncorrectLength()
    {
        $this->setExpectedException(IncorrectColorHexLengthException::class);

        Miscellaneous::getInvertedColor(null);
        Miscellaneous::getInvertedColor('');
        Miscellaneous::getInvertedColor(1);
        Miscellaneous::getInvertedColor(12);
        Miscellaneous::getInvertedColor(1234567);
        Miscellaneous::getInvertedColor('1');
        Miscellaneous::getInvertedColor('12');
        Miscellaneous::getInvertedColor('1234567');
    }

    public function testGetInvertedColorWithInvalidValue()
    {
        $this->setExpectedException(InvalidColorHexValueException::class);

        Miscellaneous::getInvertedColor('0011zz');
        Miscellaneous::getInvertedColor('001#zz');
        Miscellaneous::getInvertedColor('001!zz');
        Miscellaneous::getInvertedColor('001-zz');
        Miscellaneous::getInvertedColor('00ppqq');
    }

    public function testGetInvertedColor()
    {
        /*
         * Simple cases
         */
        self::assertEquals('000000', Miscellaneous::getInvertedColor('fff'));
        self::assertEquals('ffffff', Miscellaneous::getInvertedColor('000'));
        self::assertEquals('000000', Miscellaneous::getInvertedColor('ffffff'));
        self::assertEquals('ffffff', Miscellaneous::getInvertedColor('000000'));
        self::assertEquals('#000000', Miscellaneous::getInvertedColor('#ffffff'));
        self::assertEquals('#ffffff', Miscellaneous::getInvertedColor('#000000'));

        /*
         * Advanced cases - part 1
         */
        self::assertEquals('ffffee', Miscellaneous::getInvertedColor('001'));
        self::assertEquals('ffeeff', Miscellaneous::getInvertedColor('010'));
        self::assertEquals('eeffff', Miscellaneous::getInvertedColor('100'));
        self::assertEquals('333333', Miscellaneous::getInvertedColor('ccc'));
        self::assertEquals('333333', Miscellaneous::getInvertedColor('CCC'));

        /*
         * Advanced cases - part 2
         */
        self::assertEquals('3e3e3e', Miscellaneous::getInvertedColor('c1c1c1'));
        self::assertEquals('3e3e3e', Miscellaneous::getInvertedColor('C1C1C1'));
        self::assertEquals('#dd5a01', Miscellaneous::getInvertedColor('#22a5fe'));
        self::assertEquals('#22dbb3', Miscellaneous::getInvertedColor('#dd244c'));
        self::assertEquals('#464646', Miscellaneous::getInvertedColor('#b9b9b9'));
        self::assertEquals('#080808', Miscellaneous::getInvertedColor('#f7f7f7'));

        /*
         * Advanced cases - verification
         */
        self::assertEquals('000011', Miscellaneous::getInvertedColor('ffffee'));
        self::assertEquals('cccccc', Miscellaneous::getInvertedColor('333333'));
        self::assertEquals('#22a5fe', Miscellaneous::getInvertedColor('#dd5a01'));
        self::assertEquals('#22a5fe', Miscellaneous::getInvertedColor('#DD5A01'));
        self::assertEquals('#f7f7f7', Miscellaneous::getInvertedColor('#080808'));
    }

    /**
     * @param mixed $number Number for who the "0" characters should be inserted
     * @dataProvider provideEmptyValueToFillMissingZeros
     */
    public function testFillMissingZerosEmptyValue($number)
    {
        self::assertEquals('', Miscellaneous::fillMissingZeros($number, 1));
    }

    /**
     * @param mixed  $number   Number for who the "0" characters should be inserted
     * @param int    $length   Wanted length of final number
     * @param bool   $before   If false, 0 characters will be inserted after given number
     * @param string $expected String with added missing the "0" characters
     *
     * @dataProvider provideNumberToFillMissingZeros
     */
    public function testFillMissingZeros($number, $length, $before, $expected)
    {
        self::assertSame($expected, Miscellaneous::fillMissingZeros($number, $length, $before));
    }

    public function testGetProjectRootPath()
    {
        self::assertNotEmpty(Miscellaneous::getProjectRootPath());
    }

    /**
     * Provides string to convert characters to latin characters and not lower cased and not human-readable
     *
     * @return Generator
     */
    public function provideStringToLatinNotLowerCaseHuman()
    {
        yield[
            'asuo',
            'ąśüö',
        ];

        yield[
            'eoaslzzcn',
            'ęóąśłżźćń',
        ];

        yield[
            'loremipsum',
            'loremipsum',
        ];

        yield[
            'LoremIpsum',
            'LoremIpsum',
        ];

        yield[
            'Lorem.Ipsum',
            'Lorem Ipsum',
            '.',
        ];

        yield[
            'Lorem.Ipsum',
            'Lorem=Ipsum',
            '.',
        ];

        yield[
            'LoremIpsumD',
            'LoremIpsumD',
        ];

        yield[
            'LoremIpsumD',
            'LoremIpsumD',
            '.',
        ];

        yield[
            'lorem-ipsum',
            'lorem ipsum',
        ];

        yield[
            'lorem-ipsum',
            'lorem;ipsum',
        ];

        yield[
            'lorem1ipsum2',
            'lorem1ipsum2',
        ];

        yield[
            'lorem_ipsum',
            'lorem ipsum',
            '_',
        ];

        yield[
            'LoremIpsum',
            'LoremIpsum',
        ];

        yield[
            'Lorem Ipsum',
            'Lorem!Ipsum',
            ' ',
        ];

        yield[
            'Lorem Ipsum',
            'Lorem.Ipsum',
            ' ',
        ];

        yield[
            'Lorem|Ipsum',
            'Lorem.Ipsum',
            '|',
        ];
    }

    /**
     * Provides string to convert characters to latin characters and lower cased and human-readable
     *
     * @return Generator
     */
    public function provideStringToLatinLowerCaseHuman()
    {
        yield[
            'asuo',
            'ąśüö',
        ];

        yield[
            'eoaslzzcn',
            'ęóąśłżźćń',
        ];

        yield[
            'loremipsum',
            'loremipsum',
        ];

        yield[
            'lorem-ipsum',
            'lorem ipsum',
        ];

        yield[
            'lorem-ipsum',
            'lorem;ipsum',
        ];

        yield[
            'lorem1ipsum2',
            'lorem1ipsum2',
        ];

        yield[
            'lorem_ipsum',
            'lorem ipsum',
            '_',
        ];

        yield[
            'lorem-ipsum',
            'lorem-ipsum',
        ];

        yield[
            'lorem ipsum',
            'Lorem!Ipsum',
            ' ',
        ];

        yield[
            'lorem ipsum',
            'Lorem.Ipsum',
            ' ',
        ];

        yield[
            'lorem|ipsum',
            'Lorem.Ipsum',
            '|',
        ];

        yield[
            'lorem-ipsum',
            'LoremIpsum',
        ];

        yield[
            'lorem.ipsum',
            'Lorem Ipsum',
            '.',
        ];

        yield[
            'lorem.ipsum',
            'Lorem=Ipsum',
            '.',
        ];

        yield[
            'lorem-ipsum-d',
            'LoremIpsumD',
        ];

        yield[
            'lorem.ipsum.d',
            'LoremIpsumD',
            '.',
        ];
    }

    /**
     * Provides names of files
     *
     * @return Generator
     */
    public function provideFileNames()
    {
        yield[
            'Lorem.ipsum-dolor.sit.JPG',
            'Lorem.ipsum-dolor.sit',
        ];

        yield[
            'lets-test.doc',
            'lets-test',
        ];

        yield[
            'something/else.txt',
            'something/else',
        ];

        yield[
            'public/js/user.js',
            'public/js/user',
        ];
    }

    /**
     * Provides string to convert to camel case
     *
     * @return Generator
     */
    public function provideStringToCamelCase()
    {
        yield[
            'lorem ipsum',
            ' ',
            'loremIpsum',
        ];

        yield[
            'Lorem ipSum Dolor',
            ' ',
            'loremIpsumDolor',
        ];

        yield[
            'abc;def;ghi',
            ';',
            'abcDefGhi',
        ];
    }

    /**
     * Provides path used to remove the starting / beginning directory's separator
     *
     * @return Generator
     */
    public function providePathsToRemoveStartingDirectorySeparator()
    {
        yield[
            '/lorem/ipsum/dolor',
            '/',
            'lorem/ipsum/dolor',
        ];

        yield[
            'lorem/ipsum/dolor',
            '/',
            'lorem/ipsum/dolor',
        ];

        yield[
            '\\lorem\ipsum\dolor',
            '\\',
            'lorem\ipsum\dolor',
        ];

        yield[
            'lorem\ipsum\dolor',
            '\\',
            'lorem\ipsum\dolor',
        ];

        yield[
            ';lorem;ipsum;dolor',
            ';',
            'lorem;ipsum;dolor',
        ];

        yield[
            'lorem;ipsum;dolor',
            ';',
            'lorem;ipsum;dolor',
        ];
    }

    /**
     * Provides path used to remove the ending directory's separator
     *
     * @return Generator
     */
    public function providePathsToRemoveEndingDirectorySeparator()
    {
        yield[
            'lorem/ipsum/dolor/',
            '/',
            'lorem/ipsum/dolor',
        ];

        yield[
            'lorem/ipsum/dolor',
            '/',
            'lorem/ipsum/dolor',
        ];

        yield[
            'lorem\ipsum\dolor\\',
            '\\',
            'lorem\ipsum\dolor',
        ];

        yield[
            'lorem\ipsum\dolor',
            '\\',
            'lorem\ipsum\dolor',
        ];

        yield[
            'lorem;ipsum;dolor;',
            ';',
            'lorem;ipsum;dolor',
        ];

        yield[
            'lorem;ipsum;dolor',
            ';',
            'lorem;ipsum;dolor',
        ];
    }

    /**
     * Provides empty value used to fill missing zeros
     *
     * @return Generator
     */
    public function provideEmptyValueToFillMissingZeros()
    {
        yield[''];
        yield['   '];
        yield[null];
        yield[false];
        yield[[]];
    }

    /**
     * Provides number used to fill missing zeros
     *
     * @return Generator
     */
    public function provideNumberToFillMissingZeros()
    {
        yield[
            0,
            0,
            true,
            '0',
        ];

        yield[
            0,
            0,
            false,
            '0',
        ];

        yield[
            1,
            0,
            true,
            '1',
        ];

        yield[
            1,
            0,
            false,
            '1',
        ];

        yield[
            1,
            1,
            true,
            '1',
        ];

        yield[
            1,
            1,
            false,
            '1',
        ];

        yield[
            123,
            5,
            true,
            '00123',
        ];

        yield[
            123,
            5,
            false,
            '12300',
        ];
    }

    public function provideEmptyValuesToReplace()
    {
        yield[
            'An empty string as subject',
            '',
            'test',
            'another test',
            '',
        ];

        yield[
            'An empty array as subject',
            [],
            'test',
            'another test',
            [],
        ];

        yield[
            'Null as subject',
            null,
            'test',
            'another test',
            null,
        ];

        yield[
            'An empty string to search',
            'test',
            '',
            'another test',
            'test',
        ];

        yield[
            'An empty array to search',
            'test',
            [],
            'another test',
            'test',
        ];

        yield[
            'Null to search',
            'test',
            null,
            'another test',
            'test',
        ];

        yield[
            'An empty string as replacement',
            'test',
            'another test',
            '',
            'test',
        ];

        yield[
            'An empty array as replacement',
            'test',
            'another test',
            [],
            'test',
        ];

        yield[
            'Null as replacement',
            'test',
            'another test',
            null,
            'test',
        ];
    }

    public function provideStringsToReplace()
    {
        yield[
            'Different count of strings to search and replace - 1st part',
            'Lorem ipsum dolor sit amet',
            [
                'ipsum',
            ],
            'commodo',
            'Lorem ipsum dolor sit amet',
        ];

        yield[
            'Different count of strings to search and replace - 2nd part',
            'Lorem ipsum dolor sit amet',
            'ipsum',
            [
                'commodo',
            ],
            'Lorem commodo dolor sit amet',
        ];

        yield[
            'Replace 1 not existing word in 1 sentence (nothing to replace)',
            'Lorem ipsum dolor sit amet',
            'plum',
            'commodo',
            'Lorem ipsum dolor sit amet',
        ];

        yield[
            'Replace 1 word in 1 sentence',
            'Lorem ipsum dolor sit amet',
            'ipsum',
            'commodo',
            'Lorem commodo dolor sit amet',
        ];

        yield[
            'Replace 1 not existing word in 2 sentences (nothing to replace)',
            [
                'Lorem ipsum dolor sit amet',
                'Maecenas sed diam eget risus varius blandit sit amet',
            ],
            'plum',
            'commodo',
            [
                'Lorem ipsum dolor sit amet',
                'Maecenas sed diam eget risus varius blandit sit amet',
            ],
        ];

        yield[
            'Replace 1 word in 2 sentences',
            [
                'Lorem ipsum dolor sit amet',
                'Maecenas sed diam eget risus varius blandit sit amet',
            ],
            'amet',
            'commodo',
            [
                'Lorem ipsum dolor sit commodo',
                'Maecenas sed diam eget risus varius blandit sit commodo',
            ],
        ];
    }

    public function provideRegexToReplace()
    {
        yield[
            'Different count of strings to search and replace - 1st part',
            'Lorem ipsum dolor sit amet',
            [
                '|ipsum|',
            ],
            'commodo',
            'Lorem ipsum dolor sit amet',
        ];

        yield[
            'Different count of strings to search and replace - 2nd part',
            'Lorem ipsum dolor sit amet',
            '|ipsum|',
            [
                'commodo',
            ],
            'Lorem ipsum dolor sit amet',
        ];

        yield[
            '1 pattern (word -> "")',
            'Lorem ipsum dolor sit amet',
            '|ipsum|',
            '',
            'Lorem  dolor sit amet',
        ];

        yield[
            '1 pattern (word -> word)',
            'Lorem ipsum dolor sit amet',
            '|ipsum|',
            'commodo',
            'Lorem commodo dolor sit amet',
        ];

        yield[
            '2 patterns (word -> word)',
            'Lorem ipsum dolor sit amet',
            [
                '|ipsum|',
                '|amet|',
            ],
            [
                'commodo',
                'egestas',
            ],
            'Lorem commodo dolor sit egestas',
        ];

        yield[
            '1 word in 2 sentences',
            [
                'Lorem ipsum dolor sit amet',
                'Maecenas sed diam eget risus varius blandit sit amet',
            ],
            '|amet|',
            'commodo',
            [
                'Lorem ipsum dolor sit commodo',
                'Maecenas sed diam eget risus varius blandit sit commodo',
            ],
        ];

        yield[
            '2 words in 2 sentences',
            [
                'Lorem ipsum dolor sit amet',
                'Maecenas sed diam eget risus varius blandit sit amet',
            ],
            [
                '|ipsum|',
                '|amet|',
            ],
            [
                'commodo',
                'egestas',
            ],
            [
                'Lorem commodo dolor sit egestas',
                'Maecenas sed diam eget risus varius blandit sit egestas',
            ],
        ];
    }

    public function provideDataToReplaceWithQuoteStrings()
    {
        yield[
            'An empty string as subject',
            '',
            'test',
            'another test',
            '',
        ];

        yield[
            'An empty string to search',
            'test',
            '',
            'another test',
            'test',
        ];

        yield[
            'An empty string as replacement',
            'test',
            'another test',
            '',
            'test',
        ];

        yield[
            'Replace 1 not existing word in 1 sentence (nothing to replace)',
            'Lorem ipsum dolor sit amet',
            'plum',
            'commodo',
            'Lorem ipsum dolor sit amet',
        ];

        yield[
            'Replace 1 word in 1 sentence',
            'Lorem ipsum dolor sit amet',
            'ipsum',
            'commodo',
            'Lorem \'commodo\' dolor sit amet',
        ];

        yield[
            'Replace 1 word in 2 sentences',
            [
                'Lorem ipsum dolor sit amet',
                'Maecenas sed diam eget risus varius blandit sit amet',
            ],
            'amet',
            'commodo',
            [
                'Lorem ipsum dolor sit \'commodo\'',
                'Maecenas sed diam eget risus varius blandit sit \'commodo\'',
            ],
        ];

        yield[
            '1 pattern (word -> "")',
            'Lorem ipsum dolor sit amet',
            '|ipsum|',
            '',
            'Lorem \'\' dolor sit amet',
        ];

        yield[
            '1 pattern (word -> word)',
            'Lorem ipsum dolor sit amet',
            '|ipsum|',
            'commodo',
            'Lorem \'commodo\' dolor sit amet',
        ];

        yield[
            '2 patterns (word -> word)',
            'Lorem ipsum dolor sit amet',
            [
                '|ipsum|',
                '|amet|',
            ],
            [
                'commodo',
                'egestas',
            ],
            'Lorem \'commodo\' dolor sit \'egestas\'',
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->stringSmall = 'Lorem ipsum dolor sit amet.';
        $this->stringCommaSeparated = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit';
        $this->stringDotSeparated = 'Etiam ullamcorper. Suspendisse a pellentesque dui, non felis.';
        $this->stringWithoutSpaces = 'LoremIpsumDolorSitAmetConsecteturAdipiscingElit';
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        parent::tearDown();
        unset($this->stringSmall, $this->stringCommaSeparated, $this->stringDotSeparated, $this->stringWithoutSpaces);
    }
}
