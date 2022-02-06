<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Test\Common\Utilities;

use Generator;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Utilities\Arrays;
use Meritoo\Common\Utilities\Locale;
use Meritoo\Test\Common\Utilities\Arrays\SimpleToString;

/**
 * Test case of the useful arrays methods
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 *
 * @internal
 * @covers    \Meritoo\Common\Utilities\Arrays
 */
class ArraysTest extends BaseTestCase
{
    private $simpleArray;
    private $simpleArrayWithKeys;
    private $twoDimensionsArray;
    private $complexArray;
    private $superComplexArray;

    public function testConstructor()
    {
        static::assertHasNoConstructor(Arrays::class);
    }

    /**
     * @param string $description    Description of test
     * @param string $expected       Expected array converted to string
     * @param array  $array          Data to be converted
     * @param string $arrayColumnKey (optional) Column name. Default: "".
     * @param string $separator      (optional) Separator used between values. Default: ",".
     *
     * @dataProvider provideArrayValues2string
     */
    public function testValues2string($description, $expected, array $array, $arrayColumnKey = '', $separator = ',')
    {
        // Required to avoid failure:
        //
        // Failed asserting that two strings are identical
        // 1,2,3,test 1,test 2,test 3,,test 4,,bbb,3.45 - expected
        // 1,2,3,test 1,test 2,test 3,,test 4,,bbb,3,45 - actual
        Locale::setLocale(LC_ALL, 'en', 'US');

        self::assertSame($expected, Arrays::values2string($array, $arrayColumnKey, $separator), $description);
    }

    /**
     * @param string $description         Description of test
     * @param string $expected            Expected array converted to string
     * @param array  $array               Data to be converted
     * @param string $separator           (optional) Separator used between name-value pairs. Default: ",".
     * @param string $valuesKeysSeparator (optional) Separator used between name and value. Default: "=".
     * @param string $valuesWrapper       (optional) Wrapper used to wrap values, e.g. double-quote: key="value".
     *                                    Default: "".
     *
     * @dataProvider provideArrayValuesKeysConverted2string
     */
    public function testValuesKeys2string(
        $description,
        $expected,
        array $array,
        $separator = ',',
        $valuesKeysSeparator = '=',
        $valuesWrapper = ''
    ) {
        // Required to avoid failure:
        //
        // Failed asserting that two strings are identical
        // test_1=test test,test_2=2,test_3=3.45 - expected
        // test_1=test test,test_2=2,test_3=3,45 - actual
        Locale::setLocale(LC_ALL, 'en', 'US');

        self::assertSame(
            $expected,
            Arrays::valuesKeys2string($array, $separator, $valuesKeysSeparator, $valuesWrapper),
            $description
        );

        self::assertSame(
            '0=Lorem,1=ipsum,2=dolor,3=sit,4=amet',
            Arrays::valuesKeys2string($this->simpleArray),
            'Simple array'
        );

        self::assertSame(
            '0=Lorem;1=ipsum;2=dolor;3=sit;4=amet',
            Arrays::valuesKeys2string($this->simpleArray, ';'),
            'Simple array (with custom separator)'
        );

        self::assertSame(
            '0=Lorem 1=ipsum 2=dolor 3=sit 4=amet',
            Arrays::valuesKeys2string($this->simpleArray, ' '),
            'Simple array (with custom separator)'
        );

        self::assertSame(
            '0="Lorem" 1="ipsum" 2="dolor" 3="sit" 4="amet"',
            Arrays::valuesKeys2string($this->simpleArray, ' ', '=', '"'),
            'Simple array (with custom separators)'
        );

        self::assertSame(
            '0="Lorem", 1="ipsum", 2="dolor", 3="sit", 4="amet"',
            Arrays::valuesKeys2string($this->simpleArray, ', ', '=', '"'),
            'Simple array (with custom separators)'
        );
    }

    /**
     * @param string $description Description of test
     * @param string $expected    Expected array converted to csv string
     * @param array  $array       Data to be converted. It have to be an array that represents database table.
     * @param string $separator   (optional) Separator used between values. Default: ",".
     *
     * @dataProvider provideArrayValues2csv
     */
    public function testValues2csv(string $description, ?string $expected, array $array, string $separator = ','): void
    {
        // Required to avoid failure:
        //
        // Failed asserting that two strings are identical
        // 1,2,3.45 - expected
        // 1,2,3,45 - actual
        Locale::setLocale(LC_ALL, 'en', 'US');

        static::assertSame($expected, Arrays::values2csv($array, $separator), $description);
    }

    public function testGetFirstKey()
    {
        // Negative cases
        self::assertNull(Arrays::getFirstKey([]));

        // Positive cases
        self::assertEquals(0, Arrays::getFirstKey($this->simpleArray));
        self::assertEquals('lorem', Arrays::getFirstKey($this->complexArray));
    }

    public function testGetLastKey()
    {
        self::assertNull(Arrays::getLastKey([]));
        self::assertEquals(4, Arrays::getLastKey($this->simpleArray));
        self::assertEquals('amet', Arrays::getLastKey($this->complexArray));
    }

    /**
     * @param string    $description
     * @param           $expected
     * @param array     $array
     * @param null|bool $firstLevelOnly
     *
     * @dataProvider provideFirstElement
     */
    public function testGetFirstElement(
        string $description,
        $expected,
        array $array,
        ?bool $firstLevelOnly = null
    ): void {
        if (null === $firstLevelOnly) {
            static::assertSame($expected, Arrays::getFirstElement($array), $description);

            return;
        }

        static::assertSame($expected, Arrays::getFirstElement($array, $firstLevelOnly), $description);
    }

    /**
     * @param string $description
     * @param bool   $expected
     * @param array  $array
     * @param        $element
     * @param bool   $firstLevelOnly
     *
     * @dataProvider provideIsFirstElement
     */
    public function testIsFirstElement(
        string $description,
        bool $expected,
        array $array,
        $element,
        ?bool $firstLevelOnly = null
    ): void {
        if (null === $firstLevelOnly) {
            static::assertSame($expected, Arrays::isFirstElement($array, $element), $description);

            return;
        }

        static::assertSame($expected, Arrays::isFirstElement($array, $element, $firstLevelOnly), $description);
    }

    /**
     * @param string    $description
     * @param           $expected
     * @param array     $array
     * @param null|bool $firstLevelOnly
     *
     * @dataProvider provideLastElement
     */
    public function testGetLastElement(
        string $description,
        $expected,
        array $array,
        ?bool $firstLevelOnly = null
    ): void {
        if (null === $firstLevelOnly) {
            static::assertSame($expected, Arrays::getLastElement($array), $description);

            return;
        }

        static::assertSame($expected, Arrays::getLastElement($array, $firstLevelOnly), $description);
    }

    /**
     * @param string    $description
     * @param bool      $expected
     * @param array     $array
     * @param           $element
     * @param null|bool $firstLevelOnly
     *
     * @dataProvider provideIsLastElement
     */
    public function testIsLastElement(
        string $description,
        bool $expected,
        array $array,
        $element,
        ?bool $firstLevelOnly = null
    ): void {
        if (null === $firstLevelOnly) {
            static::assertSame($expected, Arrays::isLastElement($array, $element), $description);

            return;
        }

        static::assertSame($expected, Arrays::isLastElement($array, $element, $firstLevelOnly), $description);
    }

    public function testGetLastElementBreadCrumb()
    {
        self::assertNull(Arrays::getLastElementBreadCrumb([]));
        self::assertEquals('4/amet', Arrays::getLastElementBreadCrumb($this->simpleArray));
        self::assertEquals('2/3/eleifend', Arrays::getLastElementBreadCrumb($this->twoDimensionsArray));
        self::assertEquals('amet/1/primis', Arrays::getLastElementBreadCrumb($this->complexArray));
    }

    /**
     * @param string     $description
     * @param null|array $expected
     * @param array      $array
     *
     * @dataProvider provideLastRow
     */
    public function testGetLastRow(string $description, ?array $expected, array $array): void
    {
        static::assertSame($expected, Arrays::getLastRow($array), $description);
    }

    /**
     * @param string $description   Description of test case
     * @param array  $array         Array which keys should be replaced
     * @param string $oldKeyPattern Regular expression of the old key
     * @param string $newKey        Name of the new key
     * @param array  $expected      Expected result
     *
     * @dataProvider provideArrayToReplaceKeys
     */
    public function testReplaceKeys(
        string $description,
        array $array,
        string $oldKeyPattern,
        string $newKey,
        ?array $expected
    ): void {
        self::assertSame($expected, Arrays::replaceKeys($array, $oldKeyPattern, $newKey), $description);
    }

    public function testMakeArray(): void
    {
        self::assertSame($this->simpleArray, Arrays::makeArray($this->simpleArray));
        self::assertSame(['test'], Arrays::makeArray('test'));
    }

    public function testArray2JavaScript(): void
    {
        // Negative cases
        self::assertNull(Arrays::array2JavaScript([]));

        // Positive cases
        self::assertEquals('new Array(\'Lorem\', \'ipsum\', \'dolor\', \'sit\', \'amet\');', Arrays::array2JavaScript($this->simpleArray));
        self::assertEquals('var letsTest = new Array(\'Lorem\', \'ipsum\', \'dolor\', \'sit\', \'amet\');', Arrays::array2JavaScript($this->simpleArray, 'letsTest'));

        $effect = 'var letsTest = new Array(5);
letsTest[0] = \'Lorem\';
letsTest[1] = \'ipsum\';
letsTest[2] = \'dolor\';
letsTest[3] = \'sit\';
letsTest[4] = \'amet\';';

        self::assertEquals($effect, Arrays::array2JavaScript($this->simpleArray, 'letsTest', true));

        self::assertEquals('new Array(\'ipsum\', \'sit\', \'consectetur\');', Arrays::array2JavaScript($this->simpleArrayWithKeys));
        self::assertEquals('var letsTest = new Array(\'ipsum\', \'sit\', \'consectetur\');', Arrays::array2JavaScript($this->simpleArrayWithKeys, 'letsTest'));

        $effect = 'var letsTest = new Array(3);
letsTest[\'Lorem\'] = \'ipsum\';
letsTest[\'dolor\'] = \'sit\';
letsTest[\'amet\'] = \'consectetur\';';

        self::assertEquals($effect, Arrays::array2JavaScript($this->simpleArrayWithKeys, 'letsTest', true));

        $effect = 'var autoGeneratedVariable = new Array(3);
var value_0 = new Array(\'lorem\', \'ipsum\', \'dolor\', \'sit\', \'amet\');
autoGeneratedVariable[0] = value_0;
var value_1 = new Array(\'consectetur\', \'adipiscing\', \'elit\');
autoGeneratedVariable[1] = value_1;
var value_2 = new Array(\'donec\', \'sagittis\', \'fringilla\', \'eleifend\');
autoGeneratedVariable[2] = value_2;';

        self::assertEquals($effect, Arrays::array2JavaScript($this->twoDimensionsArray));

        $effect = 'var letsTest = new Array(3);
var value_0 = new Array(\'lorem\', \'ipsum\', \'dolor\', \'sit\', \'amet\');
letsTest[0] = value_0;
var value_1 = new Array(\'consectetur\', \'adipiscing\', \'elit\');
letsTest[1] = value_1;
var value_2 = new Array(\'donec\', \'sagittis\', \'fringilla\', \'eleifend\');
letsTest[2] = value_2;';

        self::assertEquals($effect, Arrays::array2JavaScript($this->twoDimensionsArray, 'letsTest'));

        $effect = 'var letsTest = new Array(3);
var value_0 = new Array(5);
value_0[0] = \'lorem\';
value_0[1] = \'ipsum\';
value_0[2] = \'dolor\';
value_0[3] = \'sit\';
value_0[4] = \'amet\';
letsTest[0] = value_0;
var value_1 = new Array(3);
value_1[0] = \'consectetur\';
value_1[1] = \'adipiscing\';
value_1[2] = \'elit\';
letsTest[1] = value_1;
var value_2 = new Array(4);
value_2[0] = \'donec\';
value_2[1] = \'sagittis\';
value_2[2] = \'fringilla\';
value_2[3] = \'eleifend\';
letsTest[2] = value_2;';

        self::assertEquals($effect, Arrays::array2JavaScript($this->twoDimensionsArray, 'letsTest', true));
    }

    /**
     * @param string     $description Description of test case
     * @param null|array $expected    Expected new array (with quoted elements)
     * @param array      $array       The array to check for string values
     *
     * @dataProvider provideArrayToQuoteStrings
     */
    public function testQuoteStrings(string $description, ?array $expected, array $array): void
    {
        self::assertSame($expected, Arrays::quoteStrings($array), $description);
    }

    /**
     * @param string     $description Description of test case
     * @param array      $array       The array which should be shortened
     * @param bool       $last        If is set to true, last element is removed (default behaviour). Otherwise - first.
     * @param null|array $expected    Expected result
     *
     * @dataProvider provideArrayToRemoveMarginalElement
     */
    public function testRemoveMarginalElement(string $description, array $array, bool $last, ?array $expected): void
    {
        self::assertSame($expected, Arrays::removeMarginalElement($array, $last), $description);
    }

    public function testRemoveElements(): void
    {
        $array1 = $this->simpleArray;
        $array2 = $this->simpleArray;

        Arrays::removeElements($array1, 'ipsum');
        self::assertSame([
            1 => 'ipsum',
            2 => 'dolor',
            3 => 'sit',
            4 => 'amet',
        ], $array1);

        Arrays::removeElements($array2, 'sit', false);
        self::assertSame([
            0 => 'Lorem',
            1 => 'ipsum',
            2 => 'dolor',
            3 => 'sit',
        ], $array2);

        Arrays::removeElements($this->complexArray['lorem'], 'sit', false);
        self::assertSame(['ipsum' => ['dolor' => 'sit']], $this->complexArray['lorem']);
    }

    public function testRemoveElement()
    {
        self::assertFalse(Arrays::removeElement($this->simpleArray, 'eeee'));
        self::assertTrue(is_array(Arrays::removeElement($this->simpleArray, 'Lorem')));

        Arrays::removeElement($this->simpleArray, 'amet');
        self::assertFalse(isset($this->simpleArray['amet']));
    }

    public function testSetKeysAsValuesEmptyArray()
    {
        self::assertNull(Arrays::setKeysAsValues([]));
    }

    public function testSetKeysAsValuesSameKeysValues()
    {
        $array = [
            0,
            1,
            2,
            3,
        ];

        self::assertEquals($array, Arrays::setKeysAsValues($array));
    }

    /**
     * @param array $array    The array to change values with keys
     * @param array $replaced The array with replaced values with keys
     *
     * @dataProvider provideSimpleArrayToSetKeysAsValues
     */
    public function testSetKeysAsValuesSimpleArray($array, $replaced)
    {
        self::assertEquals($replaced, Arrays::setKeysAsValues($array));
    }

    public function testSetKeysAsValuesTwoDimensionsArray()
    {
        $replaced = [
            [
                'lorem' => 0,
                'ipsum' => 1,
                'dolor' => 2,
                'sit'   => 3,
                'amet'  => 4,
            ],
            [
                'consectetur' => 0,
                'adipiscing'  => 1,
                'elit'        => 2,
            ],
            [
                'donec'     => 0,
                'sagittis'  => 1,
                'fringilla' => 2,
                'eleifend'  => 3,
            ],
        ];

        self::assertEquals($replaced, Arrays::setKeysAsValues($this->twoDimensionsArray));
    }

    /**
     * @param array $array    The array to change values with keys
     * @param array $replaced The array with replaced values with keys
     *
     * @dataProvider provideArrayWithDuplicatedValuesToSetKeysAsValues
     */
    public function testSetKeysAsValuesDuplicatedValues($array, $replaced)
    {
        self::assertEquals($replaced, Arrays::setKeysAsValues($array, false));
    }

    public function testGetNonArrayElementsCount(): void
    {
        // Negative cases
        self::assertNull(Arrays::getNonArrayElementsCount([]));

        // Positive cases
        self::assertEquals(5, Arrays::getNonArrayElementsCount($this->simpleArray));
        self::assertEquals(3, Arrays::getNonArrayElementsCount($this->simpleArrayWithKeys));
        self::assertEquals(12, Arrays::getNonArrayElementsCount($this->twoDimensionsArray));
    }

    public function testString2array(): void
    {
        // Negative cases
        self::assertNull(Arrays::string2array(''));

        // Positive cases
        $array = [
            'light' => '#fff',
            'dark'  => '#000',
        ];

        self::assertEquals($array, Arrays::string2array('light:#fff|dark:#000'));
        self::assertEquals($array, Arrays::string2array('light: #fff | dark: #000'));

        $array = [
            'red'   => '#f00',
            'green' => '#0f0',
            'blue'  => '#00f',
        ];

        self::assertEquals($array, Arrays::string2array('red:#f00|green:#0f0|blue:#00f'));
        self::assertEquals($array, Arrays::string2array('red: #f00 | green: #0f0 | blue: #00f'));
        self::assertEquals($array, Arrays::string2array('red : #f00 | green : #0f0 | blue : #00f'));
    }

    public function testAreKeysInArray(): void
    {
        // Negative cases
        self::assertFalse(Arrays::areKeysInArray([], []));
        self::assertFalse(Arrays::areKeysInArray([null], $this->simpleArray));
        self::assertFalse(Arrays::areKeysInArray([''], $this->simpleArray));
        self::assertFalse(Arrays::areKeysInArray(['dolorrr'], $this->simpleArrayWithKeys));

        $keys1 = [
            1,
            3,
            9,
        ];

        self::assertFalse(Arrays::areKeysInArray($keys1, $this->simpleArray));
        self::assertFalse(Arrays::areKeysInArray($keys1, $this->complexArray));
        self::assertFalse(Arrays::areKeysInArray($keys1, $this->complexArray, false));

        // Positive cases
        $keys12 = [
            2,
            'mollis',
        ];

        $keys13 = [
            1,
            3,
        ];

        $keys14 = [
            'dolor',
            'amet',
        ];

        $keys15 = [
            'dolor',
            'amet',
        ];

        $keys16 = [
            'a' => 'lorem',
            11  => 'amet',
        ];

        $keys17 = [
            'a' => 'lorem',
            11  => 'amet',
            'c' => 'sit__',
        ];

        self::assertTrue(Arrays::areKeysInArray([1], $this->simpleArray));
        self::assertTrue(Arrays::areKeysInArray($keys12, $this->simpleArray, false));
        self::assertTrue(Arrays::areKeysInArray($keys12, $this->complexArray));
        self::assertTrue(Arrays::areKeysInArray($keys13, $this->simpleArray));
        self::assertTrue(Arrays::areKeysInArray($keys14, $this->simpleArrayWithKeys));
        self::assertTrue(Arrays::areKeysInArray($keys15, $this->simpleArrayWithKeys));

        self::assertTrue(Arrays::areKeysInArray(['a' => 'dolor'], $this->simpleArrayWithKeys));
        self::assertTrue(Arrays::areKeysInArray(['a' => 'dolor'], $this->simpleArrayWithKeys, false));

        self::assertTrue(Arrays::areKeysInArray($keys16, $this->complexArray));
        self::assertTrue(Arrays::areKeysInArray($keys17, $this->complexArray, false));
    }

    public function testGetLastElementsPathsUsingEmptyArray(): void
    {
        self::assertNull(Arrays::getLastElementsPaths([]));
    }

    public function testGetLastElementsPathsUsingDefaults(): void
    {
        // Using default separator and other default arguments
        $expected = [
            'lorem.ipsum.dolor'        => 'sit',
            'lorem.ipsum.diam.non'     => 'egestas',
            'consectetur'              => 'adipiscing',
            'mollis'                   => 1234,
            2                          => [],
            'sit.nullam'               => 'donec',
            'sit.aliquet.vitae.ligula' => 'quis',
            'sit.0'                    => 'elit',
            'amet.0'                   => 'iaculis',
            'amet.1'                   => 'primis',
        ];

        self::assertEquals($expected, Arrays::getLastElementsPaths($this->complexArray));
    }

    public function testGetLastElementsPathsUsingCustomSeparator(): void
    {
        // Using custom separator
        $separator = ' -> ';
        $expected = [
            sprintf('lorem%sipsum%sdolor', $separator, $separator)                     => 'sit',
            sprintf('lorem%sipsum%sdiam%snon', $separator, $separator, $separator)     => 'egestas',
            'consectetur'                                                              => 'adipiscing',
            'mollis'                                                                   => 1234,
            2                                                                          => [],
            sprintf('sit%snullam', $separator)                                         => 'donec',
            sprintf('sit%saliquet%svitae%sligula', $separator, $separator, $separator) => 'quis',
            sprintf('sit%s0', $separator)                                              => 'elit',
            sprintf('amet%s0', $separator)                                             => 'iaculis',
            sprintf('amet%s1', $separator)                                             => 'primis',
        ];

        self::assertEquals($expected, Arrays::getLastElementsPaths($this->complexArray, $separator));
    }

    /**
     * @param array  $stopIfMatchedBy Patterns of keys or paths that matched will stop the process of path building and
     *                                including children of those keys or paths (recursive will not be used for keys in
     *                                lower level of given array)
     * @param string $separator       Separator used in resultant strings. Default: ".".
     * @param array  $expected        Expected array
     *
     * @dataProvider provideStopIfMatchedByForGetLastElementsPaths
     */
    public function testGetLastElementsPathsUsingStopIfMatchedBy(
        array $stopIfMatchedBy,
        string $separator,
        array $expected
    ): void {
        $paths = Arrays::getLastElementsPaths($this->superComplexArray, $separator, '', $stopIfMatchedBy);
        self::assertEquals($expected, $paths);
    }

    public function testAreAllKeysMatchedByPattern(): void
    {
        $pattern = '\d+';

        // Empty array
        self::assertFalse(Arrays::areAllKeysMatchedByPattern([], $pattern));

        // Simple array with integers as keys only
        self::assertTrue(Arrays::areAllKeysMatchedByPattern($this->simpleArray, $pattern));

        // Complex array with strings and integers as keys
        self::assertFalse(Arrays::areAllKeysMatchedByPattern($this->complexArray, $pattern));

        $array = [
            'a' => 'b',
            'c' => 'd',
        ];

        // Yet another simple array, but with strings as keys
        self::assertFalse(Arrays::areAllKeysMatchedByPattern($array, $pattern));

        // The same array with another pattern
        $pattern = '\w+';
        self::assertTrue(Arrays::areAllKeysMatchedByPattern($array, $pattern));

        // The same array with mixed keys (strings and integers as keys)
        $array[1] = 'x';
        $pattern = '\d+';
        self::assertFalse(Arrays::areAllKeysMatchedByPattern($array, $pattern));

        // Multidimensional array - negative case
        $array['e'] = ['f' => 'g'];
        self::assertFalse(Arrays::areAllKeysMatchedByPattern($array, $pattern));

        // Multidimensional array - positive case
        unset($array[1]);
        $pattern = '\w+';
        self::assertTrue(Arrays::areAllKeysMatchedByPattern($array, $pattern));
    }

    public function testAreAllKeysIntegers()
    {
        self::assertFalse(Arrays::areAllKeysIntegers([]));
        self::assertEquals(1, Arrays::areAllKeysIntegers($this->simpleArray));
        self::assertEquals(2, Arrays::areAllKeysIntegers($this->simpleArray));
        self::assertEquals('', Arrays::areAllKeysIntegers($this->complexArray));
    }

    public function testIssetRecursive()
    {
        // Negative cases
        self::assertFalse(Arrays::issetRecursive([], []));

        // Positive cases
        $unExistingKeys = [
            'a',
            'b',
            3,
        ];

        $existingKeys = [
            'simpleArray'         => [
                1,
                3,
                4,
            ],
            'simpleArrayWithKeys' => [
                'dolor',
                'amet',
            ],
            'twoDimensionsArray'  => [
                2,
                3,
            ],
            'complexArray'        => [
                'sit',
                'aliquet',
                'vitae',
            ],
        ];

        self::assertFalse(Arrays::issetRecursive($this->simpleArray, $unExistingKeys));
        self::assertTrue(Arrays::issetRecursive($this->simpleArray, $existingKeys['simpleArray']));

        self::assertFalse(Arrays::issetRecursive($this->simpleArrayWithKeys, $unExistingKeys));
        self::assertTrue(Arrays::issetRecursive($this->simpleArrayWithKeys, $existingKeys['simpleArrayWithKeys']));

        self::assertFalse(Arrays::issetRecursive($this->twoDimensionsArray, $unExistingKeys));
        self::assertTrue(Arrays::issetRecursive($this->twoDimensionsArray, $existingKeys['twoDimensionsArray']));

        self::assertFalse(Arrays::issetRecursive($this->complexArray, $unExistingKeys));
        self::assertTrue(Arrays::issetRecursive($this->complexArray, $existingKeys['complexArray']));
    }

    public function testGetValueByKeysPath()
    {
        // Negative cases
        self::assertNull(Arrays::getValueByKeysPath([], []));

        // Positive cases
        self::assertNull(Arrays::getValueByKeysPath($this->simpleArray, []));
        self::assertEquals('ipsum', Arrays::getValueByKeysPath($this->simpleArray, [1]));
        self::assertEquals('sit', Arrays::getValueByKeysPath($this->simpleArray, [3]));
        self::assertEquals('sit', Arrays::getValueByKeysPath($this->simpleArrayWithKeys, ['dolor']));

        $keys = [
            'twoDimensionsArray' => [
                2,
                2,
            ],
            'complexArray'       => [
                [
                    'lorem',
                    'ipsum',
                    'diam',
                ],
                [
                    'sit',
                    'aliquet',
                ],
            ],
        ];

        $value = [
            [
                'non' => 'egestas',
            ],
            [
                'vitae' => [
                    'ligula' => 'quis',
                ],
            ],
        ];

        self::assertEquals('fringilla', Arrays::getValueByKeysPath($this->twoDimensionsArray, $keys['twoDimensionsArray']));
        self::assertEquals($value[0], Arrays::getValueByKeysPath($this->complexArray, $keys['complexArray'][0]));
        self::assertEquals($value[1], Arrays::getValueByKeysPath($this->complexArray, $keys['complexArray'][1]));
    }

    public function testGetAllValuesOfKeyEmptyValue()
    {
        self::assertNull(Arrays::getAllValuesOfKey([], ''));
        self::assertNull(Arrays::getAllValuesOfKey([], 'test'));
    }

    public function testGetAllValuesOfKeyNotExistingKey()
    {
        self::assertNull(Arrays::getAllValuesOfKey([], 'ipsum'));
        self::assertEquals([], Arrays::getAllValuesOfKey($this->simpleArray, 'ipsum'));
    }

    public function testGetAllValuesOfKey()
    {
        // Positive case - 1-dimension array
        self::assertEquals(['ipsum'], Arrays::getAllValuesOfKey($this->simpleArray, 1));

        // Positive case - 2-dimensions array
        $effect = [
            [
                'lorem',
                'ipsum',
                'dolor',
                'sit',
                'amet',
            ],
            1 => 'consectetur',
            2 => 'donec',
        ];

        self::assertEquals($effect, Arrays::getAllValuesOfKey($this->twoDimensionsArray, 0));

        // Positive case - multi-dimensions array
        self::assertEquals(['primis'], Arrays::getAllValuesOfKey($this->complexArray, 1));

        // Positive case - multi-dimensions array
        $effect = [
            0 => [
                'in' => [
                    'dui',
                    'dolor' => [
                        'aliquam',
                    ],
                ],
            ],
        ];

        self::assertEquals($effect, Arrays::getAllValuesOfKey($this->superComplexArray, 'tortor'));
    }

    public function testKsortRecursive()
    {
        // Negative cases
        $array = [];
        self::assertNull(Arrays::ksortRecursive($array));

        // Positive cases
        self::assertEquals($this->simpleArray, Arrays::ksortRecursive($this->simpleArray));
        self::assertEquals($this->simpleArray, Arrays::ksortRecursive($this->simpleArray, SORT_NUMERIC));
        self::assertEquals($this->twoDimensionsArray, Arrays::ksortRecursive($this->twoDimensionsArray));

        // Positive case - multi-dimensions array
        $effect = [
            'amet'        => [
                'iaculis',
                'primis',
            ],
            'consectetur' => 'adipiscing',
            'lorem'       => [
                'ipsum' => [
                    'dolor' => 'sit',
                    'diam'  => [
                        'non' => 'egestas',
                    ],
                ],
            ],
            'mollis'      => 1234,
            'sit'         => [
                'nullam'  => 'donec',
                'aliquet' => [
                    'vitae' => [
                        'ligula' => 'quis',
                    ],
                ],
                'elit',
            ],
            2             => [],
        ];

        self::assertEquals($effect, Arrays::ksortRecursive($this->complexArray));

        // Positive case - multi-dimensions array - with options of ksort() function
        $effect = [
            2             => [],
            'amet'        => [
                'iaculis',
                'primis',
            ],
            'consectetur' => 'adipiscing',
            'lorem'       => [
                'ipsum' => [
                    'dolor' => 'sit',
                    'diam'  => [
                        'non' => 'egestas',
                    ],
                ],
            ],
            'mollis'      => 1234,
            'sit'         => [
                'nullam'  => 'donec',
                'aliquet' => [
                    'vitae' => [
                        'ligula' => 'quis',
                    ],
                ],
                'elit',
            ],
        ];

        self::assertEquals($effect, Arrays::ksortRecursive($this->complexArray, SORT_STRING & SORT_NATURAL));
    }

    public function testSetPositions()
    {
        // Negative cases
        self::assertNull(Arrays::setPositions([]));

        // Positive case - 1-dimension array
        $array = [
            'abc',
        ];

        self::assertEquals($array, Arrays::setPositions($array));

        // Positive case - 2-dimensions array
        $effect = $this->twoDimensionsArray;
        $effect[0][Arrays::POSITION_KEY_NAME] = 1;
        $effect[1][Arrays::POSITION_KEY_NAME] = 2;
        $effect[2][Arrays::POSITION_KEY_NAME] = 3;

        self::assertEquals($effect, Arrays::setPositions($this->twoDimensionsArray));

        // Positive case - multidimensional array
        $array = [
            'lorem',
            'ipsum' => [
                'dolor',
                'sit',
            ],
            'amet'  => [
                'consectetur',
                'adipiscing' => [
                    'elit' => [
                        'cras',
                        'quis',
                        'ligula',
                    ],
                ],
            ],
        ];

        $effect = [
            'lorem',
            'ipsum' => [
                'dolor',
                'sit',
                Arrays::POSITION_KEY_NAME => 1,
            ],
            'amet'  => [
                'consectetur',
                'adipiscing'              => [
                    'elit'                    => [
                        'cras',
                        'quis',
                        'ligula',
                        Arrays::POSITION_KEY_NAME => 1,
                    ],
                    Arrays::POSITION_KEY_NAME => 1,
                ],
                Arrays::POSITION_KEY_NAME => 2,
            ],
        ];

        self::assertEquals($effect, Arrays::setPositions($array));

        // Positive case - non-default name of key with position value & 2-dimensions array
        $keyName = 'level';
        $effect = $this->twoDimensionsArray;

        $effect[0][$keyName] = 1;
        $effect[1][$keyName] = 2;
        $effect[2][$keyName] = 3;

        self::assertEquals($effect, Arrays::setPositions($this->twoDimensionsArray, $keyName));

        // Positive case - non-default start value of position & 2-dimensions array
        $startPosition = 5;
        $effect = $this->twoDimensionsArray;

        $effect[Arrays::POSITION_KEY_NAME] = $startPosition;
        $effect[0][Arrays::POSITION_KEY_NAME] = 1;
        $effect[1][Arrays::POSITION_KEY_NAME] = 2;
        $effect[2][Arrays::POSITION_KEY_NAME] = 3;

        self::assertEquals($effect, Arrays::setPositions($this->twoDimensionsArray, Arrays::POSITION_KEY_NAME, $startPosition));
    }

    public function testTrimRecursive()
    {
        // Negative cases
        self::assertSame([], Arrays::trimRecursive([]));

        // Positive cases
        self::assertEquals(['a'], Arrays::trimRecursive([' a ']));
        self::assertEquals([
            'a',
            'b',
        ], Arrays::trimRecursive([
            ' a ',
            'b   ',
        ]));

        $array = [
            'abc  ',
            [
                'def',
                'ghi  ',
            ],
        ];

        $result = [
            'abc',
            [
                'def',
                'ghi',
            ],
        ];

        self::assertEquals($result, Arrays::trimRecursive($array));

        $array = [
            'abc  ',
            [
                '   def ',
                'ghi  ',
                'oo' => [
                    '  ee  ',
                    'g' => 'h   hhh   ',
                ],
            ],
        ];

        $result = [
            'abc',
            [
                'def',
                'ghi',
                'oo' => [
                    'ee',
                    'g' => 'h   hhh',
                ],
            ],
        ];

        self::assertEquals($result, Arrays::trimRecursive($array));
    }

    public function testSortByCustomKeysOrder()
    {
        // Negative cases
        self::assertNull(Arrays::sortByCustomKeysOrder([], []));

        // Positive cases
        self::assertEquals([0], Arrays::sortByCustomKeysOrder([0], []));
        self::assertEquals($this->simpleArray, Arrays::sortByCustomKeysOrder($this->simpleArray, []));

        $keysOrder = [
            'amet',
            'dolor',
            'Lorem',
        ];

        $sorted = [
            'dolor' => 'sit',
            'amet'  => 'consectetur',
            'Lorem' => 'ipsum',
        ];

        self::assertEquals($sorted, Arrays::sortByCustomKeysOrder($this->simpleArrayWithKeys, $keysOrder));

        $array = [
            'Lorem',
            'ipsum',
            'dolor',
            'sit',
            'amet',
        ];

        $keysOrder = [
            0,
            3,
            1,
            2,
        ];

        $sorted = [
            0 => 'Lorem',
            3 => 'sit',
            1 => 'ipsum',
            2 => 'dolor',
            4 => 'amet',
        ];

        self::assertEquals($sorted, Arrays::sortByCustomKeysOrder($array, $keysOrder));

        $array = [
            'Lorem',
            'ipsum',
            'dolor',
            'sit',
            'amet',
        ];

        $keysOrder = [
            0,
            3,
        ];

        $sorted = [
            0 => 'Lorem',
            3 => 'sit',
            1 => 'ipsum',
            2 => 'dolor',
            4 => 'amet',
        ];

        self::assertEquals($sorted, Arrays::sortByCustomKeysOrder($array, $keysOrder));
    }

    public function testImplodeSmart()
    {
        $separator = '/';

        // Empty array
        self::assertNull(Arrays::implodeSmart([], $separator));

        // Simple, One-dimensional array
        self::assertEquals(implode($separator, $this->simpleArray), Arrays::implodeSmart($this->simpleArray, $separator));

        // An array with elements that contain separator
        $array = [
            'lorem' . $separator,
            'ipsum',
            $separator . 'dolor',
        ];

        self::assertEquals(implode($separator, [
            'lorem',
            'ipsum',
            'dolor',
        ]), Arrays::implodeSmart($array, $separator));

        // Complex array
        self::assertEquals(implode($separator, [
            'donec',
            'quis',
            'elit',
        ]), Arrays::implodeSmart($this->complexArray['sit'], $separator));
    }

    public function testGetNextElement()
    {
        // Negative cases
        self::assertNull(Arrays::getNextElement($this->simpleArray, 'amet'));
        self::assertNull(Arrays::getNextElement($this->simpleArray, 'xyz'));
        self::assertNull(Arrays::getNextElement($this->simpleArray, 0));
        self::assertNull(Arrays::getNextElement([], ''));
        self::assertNull(Arrays::getNextElement([], null));

        // Positive cases
        self::assertEquals('ipsum', Arrays::getNextElement($this->simpleArray, 'Lorem'));
        self::assertEquals('sit', Arrays::getNextElement($this->simpleArray, 'dolor'));
    }

    public function testGetPreviousElement()
    {
        // Negative cases
        self::assertNull(Arrays::getPreviousElement($this->simpleArray, 'Lorem'));
        self::assertNull(Arrays::getPreviousElement($this->simpleArray, 'xyz'));
        self::assertNull(Arrays::getPreviousElement($this->simpleArray, 0));
        self::assertNull(Arrays::getPreviousElement([], ''));
        self::assertNull(Arrays::getPreviousElement([], null));

        // Positive cases
        self::assertEquals('ipsum', Arrays::getPreviousElement($this->simpleArray, 'dolor'));
        self::assertEquals('sit', Arrays::getPreviousElement($this->simpleArray, 'amet'));
    }

    public function testGetIndexOf()
    {
        // Negative cases
        self::assertFalse(Arrays::getIndexOf([], 'a'));
        self::assertNull(Arrays::getIndexOf($this->simpleArray, 'loremmm'));

        // Positive cases
        self::assertEquals(1, Arrays::getIndexOf($this->simpleArray, 'ipsum'));
        self::assertEquals('dolor', Arrays::getIndexOf($this->simpleArrayWithKeys, 'sit'));
        self::assertEquals('mollis', Arrays::getIndexOf($this->complexArray, 1234));
    }

    public function testIncrementIndexes()
    {
        // Negative cases
        self::assertNull(Arrays::incrementIndexes([]));

        // Positive cases
        $array = [
            1 => 'Lorem',
            2 => 'ipsum',
            3 => 'dolor',
            4 => 'sit',
            5 => 'amet',
        ];
        self::assertEquals($array, Arrays::incrementIndexes($this->simpleArray));

        $array = [
            0 => 'Lorem',
            2 => 'ipsum',
            3 => 'dolor',
            4 => 'sit',
            5 => 'amet',
        ];
        self::assertEquals($array, Arrays::incrementIndexes($this->simpleArray, 1));

        $array = [
            0 => 'Lorem',
            1 => 'ipsum',
            3 => 'dolor',
            4 => 'sit',
            5 => 'amet',
        ];
        self::assertEquals($array, Arrays::incrementIndexes($this->simpleArray, 2));
    }

    public function testAreAllValuesEmpty()
    {
        // Negative cases
        self::assertFalse(Arrays::areAllValuesEmpty([]));
        self::assertFalse(Arrays::areAllValuesEmpty([], true));
        self::assertFalse(Arrays::areAllValuesEmpty($this->simpleArray));
        self::assertFalse(Arrays::areAllValuesEmpty($this->simpleArray, true));

        $array = [
            null,
            0,
        ];
        self::assertFalse(Arrays::areAllValuesEmpty($array, true));

        $array = [
            null,
            [
                null,
            ],
        ];
        self::assertFalse(Arrays::areAllValuesEmpty($array, true));

        // Positive cases
        $array = [
            '',
            0,
        ];
        self::assertTrue(Arrays::areAllValuesEmpty($array));

        $array = [
            null,
            null,
        ];
        self::assertTrue(Arrays::areAllValuesEmpty($array, true));
    }

    public function testDiffRecursive()
    {
        // Negative cases
        self::assertEquals([], Arrays::arrayDiffRecursive([], []));
        self::assertEquals([], Arrays::arrayDiffRecursive([], [], true));

        // Positive cases - full comparison (keys and values)
        self::assertEquals(['a'], Arrays::arrayDiffRecursive(['a'], []));
        self::assertEquals([], Arrays::arrayDiffRecursive([], ['a']));
        self::assertEquals([], Arrays::arrayDiffRecursive($this->simpleArray, $this->simpleArray));

        $array = [
            'Lorem',
            'ipsum',
            'dolor',
        ];
        self::assertEquals([], Arrays::arrayDiffRecursive($array, $this->simpleArray));

        $array = [
            'Lorem',
            'ipsum',
            'dolor',
        ];

        $diff = [
            3 => 'sit',
            4 => 'amet',
        ];
        self::assertEquals($diff, Arrays::arrayDiffRecursive($this->simpleArray, $array));

        $array1 = [
            [
                'a',
                'b',
                'c',
            ],
            [
                'd',
                'e',
            ],
        ];

        $array2 = [
            [
                'a',
                'b',
            ],
            'f',
        ];

        $diff = [
            [
                2 => 'c',
            ],
        ];
        self::assertEquals($diff, Arrays::arrayDiffRecursive($array1, $array2));

        self::assertEquals($this->twoDimensionsArray[1], Arrays::arrayDiffRecursive($this->twoDimensionsArray[1], $this->twoDimensionsArray));

        // Positive cases - simple comparison (values only)
        self::assertEquals(['a'], Arrays::arrayDiffRecursive(['a'], [], true));

        $array = [
            'amet',
            'sit',
            1234,
            'ipsum',
        ];

        $diff = [
            0 => 'Lorem',
            2 => 'dolor',
        ];

        self::assertEquals($diff, Arrays::arrayDiffRecursive($this->simpleArray, $array, true));

        $array = [
            [
                'lorem',
                'ipsum',
                'dolor',
            ],
            [
                'consectetur',
                'adipiscing',
            ],
            [
                'sagittis',
            ],
        ];

        $diff = [
            [
                3 => 'sit',
                4 => 'amet',
            ],
            [
                2 => 'elit',
            ],
            [
                0 => 'donec',
                2 => 'fringilla',
                3 => 'eleifend',
            ],
        ];

        self::assertEquals($diff, Arrays::arrayDiffRecursive($this->twoDimensionsArray, $array, true));

        $array = [
            [
                'lorem',
                'ipsum',
                'dolor',
            ],
            [
                'consectetur',
                'adipiscing',
            ],
            'Lorem ipsum',
        ];

        $diff = [
            [
                3 => 'sit',
                4 => 'amet',
            ],
            [
                2 => 'elit',
            ],
        ];

        self::assertEquals($diff, Arrays::arrayDiffRecursive($this->twoDimensionsArray, $array, true));

        $array = [
            'Lorem ipsum',
            [
                'lorem',
                'ipsum',
                'dolor',
            ],
            'donec sagittis',
        ];

        $diff = [
            0 => 'Lorem ipsum',
            1 => [
                'lorem',
                'ipsum',
                'dolor',
            ],
            2 => 'donec sagittis',
        ];

        self::assertEquals($diff, Arrays::arrayDiffRecursive($array, $this->twoDimensionsArray, true));
    }

    public function testGetDimensionsCount()
    {
        // Basic cases
        self::assertEquals(0, Arrays::getDimensionsCount([]));
        self::assertEquals(1, Arrays::getDimensionsCount(['']));

        // Simple cases
        self::assertEquals(1, Arrays::getDimensionsCount($this->simpleArray));
        self::assertEquals(1, Arrays::getDimensionsCount($this->simpleArrayWithKeys));

        // Complex cases
        self::assertEquals(2, Arrays::getDimensionsCount($this->twoDimensionsArray));
        self::assertEquals(4, Arrays::getDimensionsCount($this->complexArray));
    }

    public function testIsMultiDimensional()
    {
        // Negative cases
        self::assertNull(Arrays::isMultiDimensional([]));

        // Positive cases
        self::assertFalse(Arrays::isMultiDimensional($this->simpleArray));
        self::assertFalse(Arrays::isMultiDimensional($this->simpleArrayWithKeys));

        self::assertTrue(Arrays::isMultiDimensional($this->twoDimensionsArray));
        self::assertTrue(Arrays::isMultiDimensional($this->complexArray));
    }

    public function testGetNonEmptyValuesUsingEmptyArray()
    {
        self::assertNull(Arrays::getNonEmptyValues([]));
    }

    /**
     * @param string $description Description of test case
     * @param array  $values      The values to filter
     * @param array  $expected    Expected non-empty values
     *
     * @dataProvider provideValuesToFilterNonEmpty
     */
    public function testGetNonEmptyValues($description, array $values, array $expected)
    {
        self::assertSame($expected, Arrays::getNonEmptyValues($values), $description);
    }

    /**
     * @param string $description Description of test case
     * @param array  $values      The values to filter
     * @param string $expected    Expected non-empty values (as string)
     *
     * @dataProvider provideValuesToFilterNonEmptyAsStringUsingDefaultSeparator
     */
    public function testGetNonEmptyValuesAsStringUsingDefaultSeparator($description, array $values, $expected)
    {
        self::assertSame($expected, Arrays::getNonEmptyValuesAsString($values), $description);
    }

    /**
     * @param string $description Description of test case
     * @param array  $values      The values to filter
     * @param string $separator   Separator used to implode the values
     * @param string $expected    Expected non-empty values (as string)
     *
     * @dataProvider provideValuesToFilterNonEmptyAsString
     */
    public function testGetNonEmptyValuesAsString($description, array $values, $separator, $expected)
    {
        self::assertSame($expected, Arrays::getNonEmptyValuesAsString($values, $separator), $description);
    }

    /**
     * @param string $description Description of test case
     * @param mixed  $value       The value to verify
     * @param bool   $expected    Expected information
     *
     * @dataProvider provideValueToIsEmptyArray
     */
    public function testIsEmptyArray(string $description, $value, bool $expected): void
    {
        self::assertSame($expected, Arrays::isEmptyArray($value), $description);
    }

    /**
     * @param string $description Description of test case
     * @param mixed  $value       The value to verify
     * @param bool   $expected    Expected information
     *
     * @dataProvider provideValueToIsNotEmptyArray
     */
    public function testIsNotEmptyArray(string $description, $value, bool $expected): void
    {
        self::assertSame($expected, Arrays::isNotEmptyArray($value), $description);
    }

    /**
     * @param array $array
     * @param bool  $expected
     *
     * @dataProvider provideArrayToVerifyIfContainsEmptyStringsOnly
     */
    public function testContainsEmptyStringsOnly(array $array, bool $expected): void
    {
        static::assertSame($expected, Arrays::containsEmptyStringsOnly($array));
    }

    public function testGetElementsFromLevelIfArrayIsEmpty(): void
    {
        self::assertNull(Arrays::getElementsFromLevel([], -1));
        self::assertNull(Arrays::getElementsFromLevel([], 0));
        self::assertNull(Arrays::getElementsFromLevel([], 1));
    }

    public function testGetElementsFromLevelIfThereIsNoGivenLevel(): void
    {
        self::assertSame([], Arrays::getElementsFromLevel([1, 2, 3], 9999));
    }

    public function testGetElementsFromLevelIfGivenLevelIsNotPositiveValue(): void
    {
        self::assertNull(Arrays::getElementsFromLevel([1, 2, 3], -1));
        self::assertNull(Arrays::getElementsFromLevel([1, 2, 3], 0));
    }

    public function testGetElementsFromLevelIfArrayHasOneLevelOnly(): void
    {
        $array = [
            // Level 1:
            'ab',
            'cd',
            'ef',
        ];

        self::assertSame($array, Arrays::getElementsFromLevel($array, 1));
    }

    public function testGetElementsFromLevel(): void
    {
        $array = [
            // Level 1:
            'ab',
            [
                // Level 2:
                'cd',
                'ef',
            ],

            // Level 1:
            [
                // Level 2:
                'gh',
                [
                    // Level 3:
                    'ij',
                    'kl',
                ],
            ],

            // Level 1:
            [
                // Level 2:
                [
                    // Level 3:
                    'mn',
                    'op',
                ],
            ],
        ];

        $expectedLevel1 = [
            'ab',
            [
                'cd',
                'ef',
            ],
            [
                'gh',
                [
                    'ij',
                    'kl',
                ],
            ],
            [
                [
                    'mn',
                    'op',
                ],
            ],
        ];

        $expectedLevel2 = [
            'cd',
            'ef',
            'gh',
            [
                'ij',
                'kl',
            ],
            [
                'mn',
                'op',
            ],
        ];

        $expectedLevel3 = [
            'ij',
            'kl',
            'mn',
            'op',
        ];

        self::assertSame($expectedLevel1, Arrays::getElementsFromLevel($array, 1));
        self::assertSame($expectedLevel2, Arrays::getElementsFromLevel($array, 2));
        self::assertSame($expectedLevel3, Arrays::getElementsFromLevel($array, 3));
    }

    public function testGetElementsFromLevelIfGivenKeyDoesNotExist(): void
    {
        $array = [
            'test1' => [1, 2, 3],
            'test2' => [4, 5, 6],
            'test3' => [
                'xy',
                'test4' => [7, 8, 9],
                'test5' => [
                    'test6' => [10, 11, 12],
                ],
            ],
        ];

        self::assertSame([], Arrays::getElementsFromLevel($array, 2, 'X'));
    }

    public function testGetElementsFromLevelWithGivenKey(): void
    {
        $array = [
            // Level 1:
            [
                'a',
                'b',

                // Level 2:
                'c' => [
                    1,
                    2,
                    'c' => [
                        4,
                        5,
                    ],
                ],
            ],

            // Level 1:
            [
                'd',
                'e',

                // Level 2:
                'c' => [
                    6,
                    7,
                ],
            ],
        ];

        $expected = [
            [
                1,
                2,
                'c' => [
                    4,
                    5,
                ],
            ],
            [
                6,
                7,
            ],
        ];

        self::assertSame($expected, Arrays::getElementsFromLevel($array, 2, 'c'));
    }

    /**
     * Provides simple array to set/replace values with keys
     *
     * @return \Generator
     */
    public function provideSimpleArrayToSetKeysAsValues()
    {
        yield[
            [
                1,
                2,
                3,
                4,
            ],
            [
                1 => 0,
                2 => 1,
                3 => 2,
                4 => 3,
            ],
        ];

        yield[
            [
                'Lorem',
                'ipsum',
                'dolor',
                'sit',
                'amet',
            ],
            [
                'Lorem' => 0,
                'ipsum' => 1,
                'dolor' => 2,
                'sit'   => 3,
                'amet'  => 4,
            ],
        ];
    }

    /**
     * Provides an array with duplicated values to set/replace values with keys
     *
     * @return \Generator
     */
    public function provideArrayWithDuplicatedValuesToSetKeysAsValues()
    {
        yield[
            [
                'lorem' => 'ipsum',
                'dolor' => 'ipsum',
                'sit'   => 'amet',
                'diam'  => 'non',
                'elit'  => 'non',
                'in'    => 'non',
            ],
            [
                'ipsum' => [
                    'lorem',
                    'dolor',
                ],
                'amet'  => 'sit',
                'non'   => [
                    'diam',
                    'elit',
                    'in',
                ],
            ],
        ];

        yield[
            [
                'lorem'  => [
                    'diam' => 'non',
                    'elit' => 'non',
                    'in'   => 'non',
                ],
                'dolor1' => 'ipsum',
                'dolor2' => 'ipsum',
                'sit'    => 'amet',
            ],
            [
                'lorem' => [
                    'non' => [
                        'diam',
                        'elit',
                        'in',
                    ],
                ],
                'ipsum' => [
                    'dolor1',
                    'dolor2',
                ],
                'amet'  => 'sit',
            ],
        ];
    }

    /**
     * Provides patterns of keys or paths that matched will stop the process and the expected array for the
     * getLastElementsPaths() method
     *
     * @return Generator
     */
    public function provideStopIfMatchedByForGetLastElementsPaths(): ?Generator
    {
        // Special exception: do not use, stop recursive on the "diam" key
        yield[
            ['diam'],
            '.',
            [
                'ipsum.quis.vestibulum.porta-1.0'                 => 'turpis',
                'ipsum.quis.vestibulum.porta-1.1'                 => 'urna',
                'ipsum.quis.vestibulum.porta-2.tortor.in.0'       => 'dui',
                'ipsum.quis.vestibulum.porta-2.tortor.in.dolor.0' => 'aliquam',
                'ipsum.quis.vestibulum.porta-3.0'                 => 1,
                'ipsum.quis.vestibulum.porta-3.1'                 => 2,
                'ipsum.quis.vestibulum.porta-3.2'                 => 3,
                'primis.0.0'                                      => 'in',
                'primis.0.1'                                      => 'faucibus',
                'primis.0.2'                                      => 'orci',
                'primis.1.0'                                      => 'luctus',
                'primis.1.1'                                      => 'et',
                'primis.1.2'                                      => 'ultrices',
            ],
        ];

        /*
         * Stop building of paths on these keys:
         * - "tortor"
         * - "primis"
         */
        yield[
            [
                'tortor',
                'primis',
            ],
            ' . ',
            [
                'ipsum . quis . vestibulum . porta-1 . 0'      => 'turpis',
                'ipsum . quis . vestibulum . porta-1 . 1'      => 'urna',
                'ipsum . quis . vestibulum . porta-2 . tortor' => [
                    'in' => [
                        'dui',
                        'dolor' => [
                            'aliquam',
                        ],
                    ],
                ],
                'ipsum . quis . vestibulum . porta-3 . 0'      => 1,
                'ipsum . quis . vestibulum . porta-3 . 1'      => 2,
                'ipsum . quis . vestibulum . porta-3 . 2'      => 3,
                'primis'                                       => [
                    [
                        'in',
                        'faucibus',
                        'orci',
                    ],
                    [
                        'luctus',
                        'et',
                        'ultrices',
                    ],
                ],
            ],
        ];

        // Stop building of paths on more sophisticated keys
        yield[
            [
                'porta\-\d+',
                '^\d+$',
            ],
            ' > ',
            [
                'ipsum > quis > vestibulum > porta-1' => [
                    'turpis',
                    'urna',
                ],
                'ipsum > quis > vestibulum > porta-2' => [
                    'tortor' => [
                        'in' => [
                            'dui',
                            'dolor' => [
                                'aliquam',
                            ],
                        ],
                    ],
                ],
                'ipsum > quis > vestibulum > porta-3' => [
                    1,
                    2,
                    3,
                ],
                'primis > 0'                          => [
                    'in',
                    'faucibus',
                    'orci',
                ],
                'primis > 1'                          => [
                    'luctus',
                    'et',
                    'ultrices',
                ],
            ],
        ];

        /*
         * Stop building of paths on these:
         * - keys
         * and
         * - paths (verify paths too)
         */
        yield[
            [
                'porta-1',
                'porta-2 > tortor > in',
            ],
            ' > ',
            [
                'ipsum > quis > vestibulum > porta-1'               => [
                    'turpis',
                    'urna',
                ],
                'ipsum > quis > vestibulum > porta-2 > tortor > in' => [
                    'dui',
                    'dolor' => [
                        'aliquam',
                    ],
                ],
                'ipsum > quis > vestibulum > porta-3 > 0'           => 1,
                'ipsum > quis > vestibulum > porta-3 > 1'           => 2,
                'ipsum > quis > vestibulum > porta-3 > 2'           => 3,
                'primis > 0 > 0'                                    => 'in',
                'primis > 0 > 1'                                    => 'faucibus',
                'primis > 0 > 2'                                    => 'orci',
                'primis > 1 > 0'                                    => 'luctus',
                'primis > 1 > 1'                                    => 'et',
                'primis > 1 > 2'                                    => 'ultrices',
            ],
        ];

        // Stop building of paths on these paths (verify paths only)
        yield[
            [
                'ipsum > quis > vestibulum > porta-1',
                'ipsum > quis > vestibulum > porta-2 > tortor',
                'primis > 1',
            ],
            ' > ',
            [
                'ipsum > quis > vestibulum > porta-1'          => [
                    'turpis',
                    'urna',
                ],
                'ipsum > quis > vestibulum > porta-2 > tortor' => [
                    'in' => [
                        'dui',
                        'dolor' => [
                            'aliquam',
                        ],
                    ],
                ],
                'ipsum > quis > vestibulum > porta-3 > 0'      => 1,
                'ipsum > quis > vestibulum > porta-3 > 1'      => 2,
                'ipsum > quis > vestibulum > porta-3 > 2'      => 3,
                'primis > 0 > 0'                               => 'in',
                'primis > 0 > 1'                               => 'faucibus',
                'primis > 0 > 2'                               => 'orci',
                'primis > 1'                                   => [
                    'luctus',
                    'et',
                    'ultrices',
                ],
            ],
        ];

        // Stop building of paths if path contains any of these part (verify part of paths only)
        yield[
            [
                'vestibulum > porta-1',
                'tortor > in',
                '[a-z]+ > \d+',
            ],
            ' > ',
            [
                'ipsum > quis > vestibulum > porta-1'               => [
                    'turpis',
                    'urna',
                ],
                'ipsum > quis > vestibulum > porta-2 > tortor > in' => [
                    'dui',
                    'dolor' => [
                        'aliquam',
                    ],
                ],
                'ipsum > quis > vestibulum > porta-3 > 0'           => 1,
                'ipsum > quis > vestibulum > porta-3 > 1'           => 2,
                'ipsum > quis > vestibulum > porta-3 > 2'           => 3,
                'primis > 0'                                        => [
                    'in',
                    'faucibus',
                    'orci',
                ],
                'primis > 1'                                        => [
                    'luctus',
                    'et',
                    'ultrices',
                ],
            ],
        ];
    }

    /**
     * Provide values to filter and get non-empty values
     *
     * @return Generator
     */
    public function provideValuesToFilterNonEmpty(): ?Generator
    {
        $simpleObject = new SimpleToString('1234');

        yield[
            'All values are empty',
            [
                '',
                null,
                [],
            ],
            [],
        ];

        yield[
            '5 values with 2 empty strings',
            [
                'test 1',
                '',
                'test 2',
                'test 3',
                '',
            ],
            [
                0 => 'test 1',
                2 => 'test 2',
                3 => 'test 3',
            ],
        ];

        yield[
            '"0" shouldn\'t be treated like an empty value',
            [
                123,
                0,
                456,
            ],
            [
                123,
                0,
                456,
            ],
        ];

        yield[
            'Object shouldn\'t be treated like an empty value',
            [
                'test 1',
                $simpleObject,
                'test 2',
                null,
                'test 3',
            ],
            [
                0 => 'test 1',
                1 => $simpleObject,
                2 => 'test 2',
                4 => 'test 3',
            ],
        ];

        yield[
            'Mixed values (non-empty, empty, strings, integers, objects)',
            [
                'test 1',
                '',
                123,
                null,
                'test 2',
                'test 3',
                0,
                $simpleObject,
                456,
                [],
                $simpleObject,
            ],
            [
                0  => 'test 1',
                2  => 123,
                4  => 'test 2',
                5  => 'test 3',
                6  => 0,
                7  => $simpleObject,
                8  => 456,
                10 => $simpleObject,
            ],
        ];
    }

    /**
     * Provide values to filter and get non-empty values concatenated by default separator
     *
     * @return \Generator
     */
    public function provideValuesToFilterNonEmptyAsStringUsingDefaultSeparator()
    {
        yield[
            'An empty array (no values to filter)',
            [],
            null,
        ];

        yield[
            'All values are empty',
            [
                '',
                null,
                [],
            ],
            '',
        ];

        yield[
            '5 values with 2 empty strings',
            [
                'test 1',
                '',
                'test 2',
                'test 3',
                '',
            ],
            'test 1, test 2, test 3',
        ];

        yield[
            'Numbers with "0" that shouldn\'t be treated like an empty value',
            [
                123,
                0,
                456,
            ],
            '123, 0, 456',
        ];

        yield[
            'Object shouldn\'t be treated like an empty value',
            [
                'test 1',
                new SimpleToString('1234'),
                'test 2',
                null,
                'test 3',
            ],
            'test 1, Instance with ID: 1234, test 2, test 3',
        ];

        yield[
            'Mixed values (non-empty, empty, strings, integers, objects)',
            [
                'test 1',
                '',
                123,
                null,
                'test 2',
                'test 3',
                0,
                new SimpleToString('A1XC90Z'),
                456,
                [],
                new SimpleToString('FF-45-0Z'),
            ],
            'test 1, 123, test 2, test 3, 0, Instance with ID: A1XC90Z, 456, Instance with ID: FF-45-0Z',
        ];
    }

    /**
     * Provide values to filter and get non-empty values concatenated by given separator
     *
     * @return \Generator
     */
    public function provideValuesToFilterNonEmptyAsString()
    {
        yield[
            'An empty array (no values to filter)',
            [],
            ' | ',
            null,
        ];

        yield[
            'All values are empty',
            [
                '',
                null,
                [],
            ],
            ' | ',
            '',
        ];

        yield[
            '5 values with 2 empty strings',
            [
                'test 1',
                '',
                'test 2',
                'test 3',
                '',
            ],
            ' | ',
            'test 1 | test 2 | test 3',
        ];

        yield[
            'Numbers with "0" that shouldn\'t be treated like an empty value',
            [
                123,
                0,
                456,
            ],
            ' <-> ',
            '123 <-> 0 <-> 456',
        ];

        yield[
            'Object shouldn\'t be treated like an empty value',
            [
                'test 1',
                new SimpleToString('1234'),
                'test 2',
                null,
                'test 3',
            ],
            ' | ',
            'test 1 | Instance with ID: 1234 | test 2 | test 3',
        ];

        yield[
            'Mixed values (non-empty, empty, strings, integers, objects)',
            [
                'test 1',
                '',
                123,
                null,
                'test 2',
                'test 3',
                0,
                new SimpleToString('A1XC90Z'),
                456,
                [],
                new SimpleToString('FF-45-0Z'),
            ],
            ';',
            'test 1;123;test 2;test 3;0;Instance with ID: A1XC90Z;456;Instance with ID: FF-45-0Z',
        ];
    }

    public function provideArrayValuesKeysConverted2string()
    {
        yield[
            'An empty array',
            null,
            [],
        ];

        yield[
            'Empty string and null as value',
            'test_1=,test_2=,test_3=3',
            [
                'test_1' => null,
                'test_2' => '',
                'test_3' => '3',
            ],
        ];

        yield[
            'Empty string and null as value (with custom separators)',
            'test_1="" test_2="" test_3="3"',
            [
                'test_1' => null,
                'test_2' => '',
                'test_3' => '3',
            ],
            ' ',
            '=',
            '"',
        ];

        yield[
            'Empty string as key',
            '1=test_1,=test_2,3=test_3',
            [
                1   => 'test_1',
                ''  => 'test_2',
                '3' => 'test_3',
            ],
        ];

        yield[
            'Empty string as key (with custom separators)',
            '1 => "test_1";  => "test_2"; 3 => "test_3"',
            [
                1   => 'test_1',
                ''  => 'test_2',
                '3' => 'test_3',
            ],
            '; ',
            ' => ',
            '"',
        ];

        yield[
            'Mixed types of keys and values',
            'test_1=test test,test_2=2,test_3=3.45',
            [
                'test_1' => 'test test',
                'test_2' => 2,
                'test_3' => 3.45,
            ],
        ];

        yield[
            'Mixed types of keys and values (with custom separators)',
            'test_1 --> *test test* | test_2 --> *2* | test_3 --> *3.45*',
            [
                'test_1' => 'test test',
                'test_2' => 2,
                'test_3' => 3.45,
            ],
            ' | ',
            ' --> ',
            '*',
        ];
    }

    public function provideArrayValues2csv(): ?Generator
    {
        yield[
            'An empty array',
            null,
            [],
        ];

        yield[
            'Empty string, and empty array and null as row',
            "1,2,3\n5,6,",
            [
                'test_1' => '',
                'test_2' => [],
                'test_3' => null,
                'test_4' => [
                    'aa' => 1,
                    'bb' => 2,
                    'cc' => 3,
                ],
                [
                    'dd' => 5,
                    'ee' => 6,
                    'ff' => '',
                ],
            ],
        ];

        yield[
            'Empty string, and empty array and null as row (with custom separator)',
            "1, 2, 3\n5, 6, ",
            [
                'test_1' => '',
                'test_2' => [],
                'test_3' => null,
                'test_4' => [
                    'aa' => 1,
                    'bb' => 2,
                    'cc' => 3,
                ],
                [
                    'dd' => 5,
                    'ee' => 6,
                    'ff' => '',
                ],
            ],
            ', ',
        ];

        yield[
            'Empty string as key, non-array as value',
            "1,2,3\n5,6,",
            [
                ''  => 'test_1',
                1   => 'test_2',
                '3' => [
                    'aa' => 1,
                    'bb' => 2,
                    'cc' => 3,
                ],
                [
                    'dd' => 5,
                    'ee' => 6,
                    'ff' => '',
                ],
            ],
        ];

        yield[
            'Empty string as key, non-array as value (with custom separator)',
            "1 | 2 | 3\n5 | 6 | ",
            [
                ''  => 'test_1',
                1   => 'test_2',
                '3' => [
                    'aa' => 1,
                    'bb' => 2,
                    'cc' => 3,
                ],
                [
                    'dd' => 5,
                    'ee' => 6,
                    'ff' => '',
                ],
            ],
            ' | ',
        ];

        yield[
            'Invalid structure, not like database table',
            "1,2,3\n5,6\n7,8,9,10",
            [
                [
                    'aa' => 1,
                    'bb' => 2,
                    'cc' => 3,
                ],
                [
                    'dd' => 5,
                    'ee' => 6,
                ],
                [
                    7,
                    8,
                    9,
                    10,
                ],
            ],
        ];

        yield[
            'Invalid structure, not like database table (with custom separator)',
            "1 <-> 2 <-> 3\n5 <-> 6\n7 <-> 8 <-> 9 <-> 10",
            [
                [
                    'aa' => 1,
                    'bb' => 2,
                    'cc' => 3,
                ],
                [
                    'dd' => 5,
                    'ee' => 6,
                ],
                [
                    7,
                    8,
                    9,
                    10,
                ],
            ],
            ' <-> ',
        ];

        yield[
            'Mixed types of keys and values',
            "1,2,3.45\n5,6,\n7,8,9,,10",
            [
                [
                    'aa' => 1,
                    'bb' => 2,
                    'cc' => 3.45,
                ],
                [
                    'dd' => 5,
                    'ee' => 6,
                    null,
                ],
                [
                    7,
                    8,
                    'qq' => 9,
                    '',
                    10,
                ],
            ],
        ];

        yield[
            'Mixed types of keys and values (with custom separator)',
            "1 // 2 // 3.45\n5 // 6 // \n7 // 8 // 9 //  // 10",
            [
                [
                    'aa' => 1,
                    'bb' => 2,
                    'cc' => 3.45,
                ],
                [
                    'dd' => 5,
                    'ee' => 6,
                    null,
                ],
                [
                    7,
                    8,
                    'qq' => 9,
                    '',
                    10,
                ],
            ],
            ' // ',
        ];

        yield[
            'With HTML code',
            "<div>abc</div>,def,<div>ghi</div>\nc,d",
            [
                [
                    '&lt;div&gt;abc&lt;/div&gt;',
                    'def',
                    '&lt;div&gt;ghi&lt;/div&gt;',
                ],
                [
                    'c',
                    'd',
                ],
            ],
        ];
    }

    public function provideArrayValues2string()
    {
        yield[
            'An empty array',
            null,
            [],
        ];

        yield[
            'Simple array',
            'Test 1,Test 2,Test 3',
            [
                1 => 'Test 1',
                2 => 'Test 2',
                3 => 'Test 3',
            ],
        ];

        yield[
            'Simple array (with custom separator)',
            'Test 1.Test 2.Test 3',
            [
                1 => 'Test 1',
                2 => 'Test 2',
                3 => 'Test 3',
            ],
            '',
            '.',
        ];

        yield[
            'Simple array (concrete column)',
            'Test 2',
            [
                1 => 'Test 1',
                2 => 'Test 2',
                3 => 'Test 3',
            ],
            2,
        ];

        yield[
            'Simple array (concrete column with custom separator)',
            'Test 2',
            [
                1 => 'Test 1',
                2 => 'Test 2',
                3 => 'Test 3',
            ],
            2,
            '.',
        ];

        yield[
            'Complex array',
            '1,2,3,test 1,test 2,test 3,,test 4,,bbb,3.45',
            [
                [
                    1,
                    2,
                    3,
                ],
                [
                    'test 1',
                    'test 2',
                    [
                        'test 3',
                        '',
                        'test 4',
                    ],
                ],
                [],
                [
                    'a' => '',
                    'b' => 'bbb',
                    [],
                    'c' => 3.45,
                ],
            ],
        ];

        yield[
            '1st complex array (concrete column)',
            '2,test 2,',
            [
                [
                    1,
                    2,
                    3,
                ],
                [
                    'test 1',
                    'test 2',
                    [
                        'test 3',
                        '',
                        'test 4',
                    ],
                ],
                [],
                [
                    'a' => '',
                    'b' => 'bbb',
                    [],
                    'c' => 3.45,
                ],
            ],
            1,
        ];

        yield[
            '2nd complex array (concrete column)',
            'bb,1234,0xb',
            [
                [
                    1,
                    2,
                    3,
                ],
                [
                    'a' => 'aa',
                    'b' => 'bb',
                    'c' => 'cc',
                ],
                [
                    'a',
                    'b',
                    'c',
                ],
                [
                    'a' => '',
                    'b' => 1234,
                ],
                [
                    'c' => 5678,
                    'b' => '0xb',
                ],
            ],
            'b',
        ];

        yield[
            '3rd complex array (concrete column with custom separator)',
            'bb - 1234 - 3xb - bbb',
            [
                [
                    1,
                    2,
                    3,
                ],
                [
                    'a' => 'aa',
                    'b' => 'bb',
                    'c' => 'cc',
                ],
                [
                    'a',
                    'b' => [],
                    'c',
                ],
                [
                    'a' => '',
                    'b' => 1234,
                ],
                [
                    'c' => 5678,
                    'b' => [
                        'b1' => '0xb',
                        'b2' => '1xb',
                        'b'  => '3xb',
                    ],
                    [
                        1,
                        2,
                        'a' => 'aaa',
                        'b' => 'bbb',
                    ],
                ],
            ],
            'b',
            ' - ',
        ];
    }

    public function provideArrayToQuoteStrings()
    {
        yield[
            'An empty array',
            null,
            [],
        ];

        yield[
            'Simple array',
            [
                1,
                2,
                3,
                '\'1\'',
                '\'2\'',
            ],
            [
                1,
                2,
                3,
                '1',
                '2',
            ],
        ];

        yield[
            'Complex array',
            [
                123,
                '\'456\'',
                [
                    'x' => [
                        0,
                        '\'0\'',
                        1 => '\'1\'',
                        2 => 2,
                    ],
                    '\'y\'',
                ],
                444 => '\'\'',
                [
                    [
                        [
                            '\'test\'',
                        ],
                    ],
                ],
            ],
            [
                123,
                '456',
                [
                    'x' => [
                        0,
                        '0',
                        1 => '1',
                        2 => 2,
                    ],
                    'y',
                ],
                444 => '',
                [
                    [
                        [
                            'test',
                        ],
                    ],
                ],
            ],
        ];
    }

    public function provideValueToIsEmptyArray(): ?Generator
    {
        yield[
            'An empty string',
            '',
            false,
        ];

        yield[
            'Non-empty string',
            'test',
            false,
        ];

        yield[
            'Null',
            null,
            false,
        ];

        yield[
            'An integer equals 0',
            1234,
            false,
        ];

        yield[
            'An integer greater than 0',
            1234,
            false,
        ];

        yield[
            'An empty array',
            [],
            true,
        ];

        yield[
            'Non-empty array',
            [
                'test',
            ],
            false,
        ];
    }

    public function provideValueToIsNotEmptyArray(): ?Generator
    {
        yield[
            'An empty string',
            '',
            false,
        ];

        yield[
            'Non-empty string',
            'test',
            false,
        ];

        yield[
            'Null',
            null,
            false,
        ];

        yield[
            'An integer equals 0',
            1234,
            false,
        ];

        yield[
            'An integer greater than 0',
            1234,
            false,
        ];

        yield[
            'An empty array',
            [],
            false,
        ];

        yield[
            'Non-empty array',
            [
                'test',
            ],
            true,
        ];
    }

    public function provideArrayToRemoveMarginalElement(): Generator
    {
        yield[
            'An empty array - remove last element',
            [],
            true,
            null,
        ];

        yield[
            'An empty array - remove first element',
            [],
            false,
            null,
        ];

        yield[
            'One-dimensional array - remove last element',
            [
                'Lorem',
                'ipsum',
                'dolor',
                'sit',
                'amet',
            ],
            true,
            [
                0 => 'Lorem',
                1 => 'ipsum',
                2 => 'dolor',
                3 => 'sit',
            ],
        ];

        yield[
            'One-dimensional array - remove first element',
            [
                'Lorem',
                'ipsum',
                'dolor',
                'sit',
                'amet',
            ],
            false,
            [
                1 => 'ipsum',
                2 => 'dolor',
                3 => 'sit',
                4 => 'amet',
            ],
        ];

        yield[
            'Multi-dimensional array - remove last element',
            [
                'lorem'       => [
                    'ipsum' => [
                        'dolor' => 'sit',
                        'diam'  => [
                            'non' => 'egestas',
                        ],
                    ],
                ],
                'consectetur' => 'adipiscing',
                'mollis'      => 1234,
                2             => [],
                'sit'         => [
                    'nullam'  => 'donec',
                    'aliquet' => [
                        'vitae' => [
                            'ligula' => 'quis',
                        ],
                    ],
                    'elit',
                ],
                'amet'        => [
                    'iaculis',
                    'primis',
                ],
            ],
            true,
            [
                'lorem'       => [
                    'ipsum' => [
                        'dolor' => 'sit',
                        'diam'  => [
                            'non' => 'egestas',
                        ],
                    ],
                ],
                'consectetur' => 'adipiscing',
                'mollis'      => 1234,
                2             => [],
                'sit'         => [
                    'nullam'  => 'donec',
                    'aliquet' => [
                        'vitae' => [
                            'ligula' => 'quis',
                        ],
                    ],
                    'elit',
                ],
            ],
        ];

        yield[
            'Multi-dimensional array - remove first element',
            [
                'lorem'       => [
                    'ipsum' => [
                        'dolor' => 'sit',
                        'diam'  => [
                            'non' => 'egestas',
                        ],
                    ],
                ],
                'consectetur' => 'adipiscing',
                'mollis'      => 1234,
                2             => [],
                'sit'         => [
                    'nullam'  => 'donec',
                    'aliquet' => [
                        'vitae' => [
                            'ligula' => 'quis',
                        ],
                    ],
                    'elit',
                ],
                'amet'        => [
                    'iaculis',
                    'primis',
                ],
            ],
            false,
            [
                'consectetur' => 'adipiscing',
                'mollis'      => 1234,
                2             => [],
                'sit'         => [
                    'nullam'  => 'donec',
                    'aliquet' => [
                        'vitae' => [
                            'ligula' => 'quis',
                        ],
                    ],
                    'elit',
                ],
                'amet'        => [
                    'iaculis',
                    'primis',
                ],
            ],
        ];
    }

    public function provideArrayToReplaceKeys(): Generator
    {
        yield[
            'An empty array',
            [],
            '',
            '',
            null,
        ];

        yield[
            '1st case',
            [
                'nullam'  => 'donec',
                'aliquet' => [
                    'vitae' => [
                        'ligula' => 'quis',
                    ],
                ],
                'elit',
            ],
            '|.*li.*|',
            'x',
            [
                'nullam' => 'donec',
                'x'      => [
                    'vitae' => [
                        'x' => 'quis',
                    ],
                ],
                'elit',
            ],
        ];

        yield[
            '2nd case',
            [
                'Lorem',
                'ipsum',
                'dolor',
                'sit',
                'amet',
            ],
            '|[0-3]+|',
            'x',
            [
                'x' => 'sit',
                4   => 'amet',
            ],
        ];
    }

    public function provideArrayToVerifyIfContainsEmptyStringsOnly(): ?Generator
    {
        yield[
            [],
            false,
        ];

        yield[
            [
                '',
                1,
            ],
            false,
        ];

        yield[
            [
                '',
                null,
                1,
            ],
            false,
        ];

        yield[
            [
                '',
                null,
            ],
            true,
        ];

        yield[
            [
                '',
                null,
                '',
            ],
            true,
        ];
    }

    public function provideIsFirstElement(): ?Generator
    {
        yield[
            'An empty array (first level only)',
            false,
            [],
            '',
        ];

        yield[
            'An empty array',
            false,
            [],
            '',
            false,
        ];

        yield[
            'Non-existing integer in array with integers (first level only)',
            false,
            [
                1,
                2,
                3,
            ],
            4,
        ];

        yield[
            'Existing integer in array with integers (first level only)',
            true,
            [
                1,
                2,
                3,
            ],
            1,
        ];

        yield[
            'Existing integer in array with integers',
            true,
            [
                1,
                2,
                3,
            ],
            1,
            false,
        ];

        yield[
            'Non-existing integer in multidimensional array with integers (first level only)',
            false,
            [
                [
                    [
                        1,
                        2,
                        3,
                    ],
                    4,
                ],
                5,
                [
                    6,
                    7,
                    [
                        8,
                        9,
                        10,
                    ],
                ],
            ],
            9,
        ];

        yield[
            'Non-existing integer in multidimensional array with integers',
            false,
            [
                [
                    [
                        1,
                        2,
                        3,
                    ],
                    4,
                ],
                5,
                [
                    6,
                    7,
                    [
                        8,
                        9,
                        10,
                    ],
                ],
            ],
            9,
            false,
        ];

        yield[
            'Existing integer in multidimensional array with integers, but first level only checked',
            false,
            [
                [
                    [
                        1,
                        2,
                        3,
                    ],
                    4,
                ],
                5,
                [
                    6,
                    7,
                    [
                        8,
                        9,
                        10,
                    ],
                ],
            ],
            1,
        ];

        yield[
            'Existing integer in multidimensional array with integers',
            true,
            [
                [
                    [
                        1,
                        2,
                        3,
                    ],
                    4,
                ],
                5,
                [
                    6,
                    7,
                    [
                        8,
                        9,
                        10,
                    ],
                ],
            ],
            1,
            false,
        ];

        yield[
            'Non-existing element in multidimensional array (first level only)',
            false,
            [
                [
                    [
                        'abc',
                        2,
                        'def',
                    ],
                    4,
                ],
                '---',
                [
                    'ghi',
                    7,
                    [
                        'jkl',
                        '...',
                        10,
                    ],
                ],
            ],
            9,
        ];

        yield[
            'Existing element in multidimensional array, but first level only checked',
            false,
            [
                [
                    [
                        'abc',
                        2,
                        'def',
                    ],
                    4,
                ],
                '---',
                [
                    'ghi',
                    7,
                    [
                        'jkl',
                        '...',
                        10,
                    ],
                ],
            ],
            'abc',
        ];

        yield[
            'Existing element in multidimensional array',
            true,
            [
                [
                    [
                        'abc',
                        2,
                        'def',
                    ],
                    4,
                ],
                '---',
                [
                    'ghi',
                    7,
                    [
                        'jkl',
                        '...',
                        10,
                    ],
                ],
            ],
            'abc',
            false,
        ];
    }

    public function provideFirstElement(): ?Generator
    {
        yield[
            'An empty array (first level only)',
            null,
            [],
        ];

        yield[
            'An empty array',
            null,
            [],
            false,
        ];

        yield[
            'Multidimensional array (first level only)',
            [
                [
                    'abc',
                    2,
                    'def',
                ],
                4,
            ],
            [
                [
                    [
                        'abc',
                        2,
                        'def',
                    ],
                    4,
                ],
                '---',
                [
                    'ghi',
                    7,
                    [
                        'jkl',
                        '...',
                        10,
                    ],
                ],
            ],
        ];

        yield[
            'Multidimensional array',
            'abc',
            [
                [
                    [
                        'abc',
                        2,
                        'def',
                    ],
                    4,
                ],
                '---',
                [
                    'ghi',
                    7,
                    [
                        'jkl',
                        '...',
                        10,
                    ],
                ],
            ],
            false,
        ];
    }

    public function provideIsLastElement(): ?Generator
    {
        yield[
            'An empty array (first level only)',
            false,
            [],
            '',
        ];

        yield[
            'An empty array',
            false,
            [],
            '',
            false,
        ];

        yield[
            'Non-existing integer in array with integers (first level only)',
            false,
            [
                1,
                2,
                3,
            ],
            4,
        ];

        yield[
            'Existing integer in array with integers (first level only)',
            true,
            [
                1,
                2,
                3,
            ],
            3,
        ];

        yield[
            'Existing integer in array with integers',
            true,
            [
                1,
                2,
                3,
            ],
            3,
            false,
        ];

        yield[
            'Non-existing integer in multidimensional array with integers (first level only)',
            false,
            [
                [
                    [
                        1,
                        2,
                        3,
                    ],
                    4,
                ],
                5,
                [
                    6,
                    7,
                    [
                        8,
                        9,
                        10,
                    ],
                ],
            ],
            11,
        ];

        yield[
            'Non-existing integer in multidimensional array with integers',
            false,
            [
                [
                    [
                        1,
                        2,
                        3,
                    ],
                    4,
                ],
                5,
                [
                    6,
                    7,
                    [
                        8,
                        9,
                        10,
                    ],
                ],
            ],
            11,
            false,
        ];

        yield[
            'Existing integer in multidimensional array with integers, but first level only checked',
            false,
            [
                [
                    [
                        1,
                        2,
                        3,
                    ],
                    4,
                ],
                5,
                [
                    6,
                    7,
                    [
                        8,
                        9,
                        10,
                    ],
                ],
            ],
            10,
        ];

        yield[
            'Existing integer in multidimensional array with integers',
            true,
            [
                [
                    [
                        1,
                        2,
                        3,
                    ],
                    4,
                ],
                5,
                [
                    6,
                    7,
                    [
                        8,
                        9,
                        10,
                    ],
                ],
            ],
            10,
            false,
        ];

        yield[
            'Non-existing element in multidimensional array (first level only)',
            false,
            [
                [
                    [
                        'abc',
                        2,
                        'def',
                    ],
                    4,
                ],
                '---',
                [
                    'ghi',
                    7,
                    [
                        'jkl',
                        '...',
                        10,
                    ],
                ],
            ],
            9,
        ];

        yield[
            'Existing element in multidimensional array, but first level only checked',
            false,
            [
                [
                    [
                        'abc',
                        2,
                        'def',
                    ],
                    4,
                ],
                '---',
                [
                    'ghi',
                    7,
                    [
                        10,
                        '...',
                        'jkl',
                    ],
                ],
            ],
            'jkl',
        ];

        yield[
            'Existing element in multidimensional array',
            true,
            [
                [
                    [
                        'abc',
                        2,
                        'def',
                    ],
                    4,
                ],
                '---',
                [
                    'ghi',
                    7,
                    [
                        10,
                        '...',
                        'jkl',
                    ],
                ],
            ],
            'jkl',
            false,
        ];
    }

    public function provideLastElement(): ?Generator
    {
        yield[
            'An empty array (first level only)',
            null,
            [],
        ];

        yield[
            'An empty array',
            null,
            [],
            false,
        ];

        yield[
            'One-dimensional array (first level only)',
            3,
            [
                1,
                2,
                3,
            ],
        ];

        yield[
            'One-dimensional array (first level only)',
            3,
            [
                1,
                2,
                3,
            ],
            false,
        ];

        yield[
            'Multidimensional array (first level only)',
            [
                'ghi',
                7,
                [
                    'jkl',
                    '...',
                    10,
                ],
            ],
            [
                [
                    [
                        'abc',
                        2,
                        'def',
                    ],
                    4,
                ],
                '---',
                [
                    'ghi',
                    7,
                    [
                        'jkl',
                        '...',
                        10,
                    ],
                ],
            ],
        ];

        yield[
            'Multidimensional array',
            10,
            [
                [
                    [
                        'abc',
                        2,
                        'def',
                    ],
                    4,
                ],
                '---',
                [
                    'ghi',
                    7,
                    [
                        'jkl',
                        '...',
                        10,
                    ],
                ],
            ],
            false,
        ];
    }

    public function provideLastRow(): ?Generator
    {
        yield[
            'An empty array',
            null,
            [],
        ];

        yield[
            'One-dimensional array',
            [],
            [
                'a',
                'b',
                1,
                2,
            ],
        ];

        yield[
            'Multidimensional array with scalar as last element',
            [],
            [
                'a',
                [
                    'b',
                    'c',
                ],
                [
                    'e',
                    'f',
                ],
                1,
                2,
            ],
        ];

        yield[
            'Multidimensional array with an empty array as last element',
            [],
            [
                'a',
                [
                    'b',
                    'c',
                ],
                1,
                2,
                [],
            ],
        ];

        yield[
            'Multidimensional array',
            [
                'e',
                'f',
            ],
            [
                'a',
                [
                    'b',
                    'c',
                ],
                1,
                2,
                [
                    'e',
                    'f',
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->simpleArray = [
            'Lorem',
            'ipsum',
            'dolor',
            'sit',
            'amet',
        ];

        $this->simpleArrayWithKeys = [
            'Lorem' => 'ipsum',
            'dolor' => 'sit',
            'amet'  => 'consectetur',
        ];

        $this->twoDimensionsArray = [
            [
                'lorem',
                'ipsum',
                'dolor',
                'sit',
                'amet',
            ],
            [
                'consectetur',
                'adipiscing',
                'elit',
            ],
            [
                'donec',
                'sagittis',
                'fringilla',
                'eleifend',
            ],
        ];

        $this->complexArray = [
            'lorem'       => [
                'ipsum' => [
                    'dolor' => 'sit',
                    'diam'  => [
                        'non' => 'egestas',
                    ],
                ],
            ],
            'consectetur' => 'adipiscing',
            'mollis'      => 1234,
            2             => [],
            'sit'         => [
                'nullam'  => 'donec',
                'aliquet' => [
                    'vitae' => [
                        'ligula' => 'quis',
                    ],
                ],
                'elit',
            ],
            'amet'        => [
                'iaculis',
                'primis',
            ],
        ];

        $this->superComplexArray = [
            'ipsum'  => [
                'quis' => [
                    'vestibulum' => [
                        'porta-1' => [
                            'turpis',
                            'urna',
                        ],
                        'porta-2' => [
                            'tortor' => [
                                'in' => [
                                    'dui',
                                    'dolor' => [
                                        'aliquam',
                                    ],
                                ],
                            ],
                        ],
                        'porta-3' => [
                            1,
                            2,
                            3,
                        ],
                    ],
                ],
            ],
            'primis' => [
                [
                    'in',
                    'faucibus',
                    'orci',
                ],
                [
                    'luctus',
                    'et',
                    'ultrices',
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset(
            $this->simpleArray,
            $this->simpleArrayWithKeys,
            $this->twoDimensionsArray,
            $this->complexArray,
            $this->superComplexArray
        );
    }
}
