<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Test\Utilities;

use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Utilities\Arrays;

/**
 * Test case of the useful arrays methods
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
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

    public function testValues2string()
    {
        /*
         * Simple array / string
         */
        $simpleString = 'Lorem,ipsum,dolor,sit,amet';
        $simpleStringWithDots = str_replace(',', '.', $simpleString);

        self::assertEquals($simpleString, Arrays::values2string($this->simpleArray));
        self::assertEquals('ipsum', Arrays::values2string($this->simpleArray, 1));
        self::assertEquals($simpleStringWithDots, Arrays::values2string($this->simpleArray, '', '.'));

        /*
         * Complex array / string
         */
        $complexString = 'sit,egestas,adipiscing,1234,,donec,quis,elit,iaculis,primis';
        $complexStringWithDots = str_replace(',', '.', $complexString);

        self::assertEquals($complexString, Arrays::values2string($this->complexArray));
        self::assertEquals($complexStringWithDots, Arrays::values2string($this->complexArray, '', '.'));

        /*
         * Other cases
         */
        self::assertEquals('', Arrays::values2string([]));
    }

    public function testValuesKeys2string()
    {
        self::assertEquals('0=Lorem,1=ipsum,2=dolor,3=sit,4=amet', Arrays::valuesKeys2string($this->simpleArray));
        self::assertEquals('0=Lorem;1=ipsum;2=dolor;3=sit;4=amet', Arrays::valuesKeys2string($this->simpleArray, ';'));
        self::assertEquals('0=Lorem 1=ipsum 2=dolor 3=sit 4=amet', Arrays::valuesKeys2string($this->simpleArray, ' '));

        self::assertEquals('0="Lorem" 1="ipsum" 2="dolor" 3="sit" 4="amet"', Arrays::valuesKeys2string($this->simpleArray, ' ', '=', '"'));
        self::assertEquals('0="Lorem", 1="ipsum", 2="dolor", 3="sit", 4="amet"', Arrays::valuesKeys2string($this->simpleArray, ', ', '=', '"'));
    }

    public function testValues2csv()
    {
        self::assertEquals('', Arrays::values2csv($this->simpleArray));

        self::assertEquals("lorem,ipsum,dolor,sit,amet\n"
            . "consectetur,adipiscing,elit\n"
            . 'donec,sagittis,fringilla,eleifend', Arrays::values2csv($this->twoDimensionsArray));
    }

    public function testGetFirstKey()
    {
        /*
         * Negative cases
         */
        self::assertNull(Arrays::getFirstKey([]));

        /*
         * Positive cases
         */
        self::assertEquals(0, Arrays::getFirstKey($this->simpleArray));
        self::assertEquals('lorem', Arrays::getFirstKey($this->complexArray));
    }

    public function testGetLastKey()
    {
        self::assertEquals(4, Arrays::getLastKey($this->simpleArray));
        self::assertEquals('amet', Arrays::getLastKey($this->complexArray));
    }

    public function testGetFirstElement()
    {
        /*
         * Negative cases
         */
        self::assertNull(Arrays::getFirstElement([]));

        /*
         * Positive cases
         */
        self::assertEquals('Lorem', Arrays::getFirstElement($this->simpleArray));
        self::assertEquals('Lorem', Arrays::getFirstElement($this->simpleArray));
        self::assertEquals('lorem', Arrays::getFirstElement($this->twoDimensionsArray, false));
        self::assertEquals('sit', Arrays::getFirstElement($this->complexArray, false));
    }

    public function testIsFirstElement()
    {
        self::assertTrue(Arrays::isFirstElement($this->simpleArray, 'Lorem'));
        self::assertFalse(Arrays::isFirstElement($this->simpleArray, 'dolor'));
        self::assertFalse(Arrays::isFirstElement($this->simpleArray, ' '));
        self::assertFalse(Arrays::isFirstElement($this->simpleArray, null));
    }

    public function testGetLastElement()
    {
        /*
         * Negative cases
         */
        self::assertNull(Arrays::getLastElement([]));

        /*
         * Positive cases
         */
        self::assertEquals('amet', Arrays::getLastElement($this->simpleArray));
        self::assertEquals('eleifend', Arrays::getLastElement($this->twoDimensionsArray, false));
        self::assertEquals('primis', Arrays::getLastElement($this->complexArray, false));
    }

    public function testIsLastElement()
    {
        self::assertTrue(Arrays::isLastElement($this->simpleArray, 'amet'));
        self::assertFalse(Arrays::isLastElement($this->simpleArray, 'ipsum'));
        self::assertFalse(Arrays::isLastElement($this->simpleArray, ''));
        self::assertFalse(Arrays::isLastElement($this->simpleArray, null));
    }

    public function testGetLastElementBreadCrumb()
    {
        self::assertEquals('4/amet', Arrays::getLastElementBreadCrumb($this->simpleArray));
        self::assertEquals('2/3/eleifend', Arrays::getLastElementBreadCrumb($this->twoDimensionsArray));
        self::assertEquals('amet/1/primis', Arrays::getLastElementBreadCrumb($this->complexArray));
    }

    public function testGetLastRow()
    {
        /*
         * Negative cases
         */
        self::assertNull(Arrays::getLastRow([]));

        /*
         * Positive cases
         */
        self::assertEquals([], Arrays::getLastRow($this->simpleArray));
        self::assertEquals([], Arrays::getLastRow($this->simpleArrayWithKeys));

        self::assertEquals([
            'donec',
            'sagittis',
            'fringilla',
            'eleifend',
        ], Arrays::getLastRow($this->twoDimensionsArray));

        self::assertEquals([
            'iaculis',
            'primis',
        ], Arrays::getLastRow($this->complexArray));
    }

    public function testReplaceArrayKeys()
    {
        $effect = [
            'nullam' => 'donec',
            'x'      => [
                'vitae' => [
                    'x' => 'quis',
                ],
            ],
            'elit',
        ];

        $dataArray = $this->complexArray['sit'];
        self::assertEquals($effect, Arrays::replaceArrayKeys($dataArray, '|.*li.*|', 'x'));

        self::assertEquals([
            'x' => 'sit',
            4   => 'amet',
        ], Arrays::replaceArrayKeys($this->simpleArray, '|[0-3]+|', 'x'));
    }

    public function testMakeArray()
    {
        self::assertSame($this->simpleArray, Arrays::makeArray($this->simpleArray));
        self::assertSame(['test'], Arrays::makeArray('test'));
    }

    public function testArray2JavaScript()
    {
        /*
         * Negative cases
         */
        self::assertNull(Arrays::array2JavaScript([]));

        /*
         * Positive cases
         */
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

    public function testRemoveMarginalElement()
    {
        $array = $this->simpleArray;
        $string = 'Lorem ipsum';

        /*
         * Removing first element
         */
        self::assertSame([
            0 => 'Lorem',
            1 => 'ipsum',
            2 => 'dolor',
            3 => 'sit',
        ], Arrays::removeMarginalElement($array));
        self::assertEquals('Lorem ipsu', Arrays::removeMarginalElement($string));

        /*
         * Removing last element
         */
        self::assertSame([
            1 => 'ipsum',
            2 => 'dolor',
            3 => 'sit',
            4 => 'amet',
        ], Arrays::removeMarginalElement($array, false));
        self::assertEquals('orem ipsum', Arrays::removeMarginalElement($string, false));
    }

    public function testRemoveElements()
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
        self::assertEquals([], Arrays::setKeysAsValues([]));
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

    public function testGetNonArrayElementsCount()
    {
        /*
         * Negative cases
         */
        self::assertNull(Arrays::getNonArrayElementsCount([]));

        /*
         * Positive cases
         */
        self::assertEquals(5, Arrays::getNonArrayElementsCount($this->simpleArray));
        self::assertEquals(3, Arrays::getNonArrayElementsCount($this->simpleArrayWithKeys));
        self::assertEquals(12, Arrays::getNonArrayElementsCount($this->twoDimensionsArray));
    }

    public function testString2array()
    {
        /*
         * Negative cases
         */
        self::assertNull(Arrays::string2array(''));
        self::assertNull(Arrays::string2array(null));

        /*
         * Positive cases
         */
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

    public function testAreKeysInArray()
    {
        /*
         * Negative cases
         */
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

        /*
         * Positive cases
         */
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

    public function testGetLastElementsPathsUsingEmptyArray()
    {
        self::assertSame([], Arrays::getLastElementsPaths([]));
    }

    public function testGetLastElementsPathsUsingDefaults()
    {
        /*
         * Using default separator and other default arguments
         */
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

    public function testGetLastElementsPathsUsingCustomSeparator()
    {
        /*
         * Using custom separator
         */
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
     * @param string|array $stopIfMatchedBy Patterns of keys or paths that matched will stop the process of path
     *                                      building and including children of those keys or paths (recursive will
     *                                      not be used for keys in lower level of given array)
     * @param string       $separator       Separator used in resultant strings. Default: ".".
     * @param array        $expected        Expected array
     *
     * @dataProvider provideStopIfMatchedByForGetLastElementsPaths
     */
    public function testGetLastElementsPathsUsingStopIfMatchedBy($stopIfMatchedBy, $separator, array $expected)
    {
        self::assertEquals($expected, Arrays::getLastElementsPaths($this->superComplexArray, $separator, '', $stopIfMatchedBy));
    }

    public function testAreAllKeysMatchedByPattern()
    {
        $pattern = '\d+';

        /*
         * Complex array with strings and integers as keys
         */
        self::assertFalse(Arrays::areAllKeysMatchedByPattern($this->complexArray, $pattern));

        /*
         * Simple array with integers as keys only
         */
        self::assertTrue(Arrays::areAllKeysMatchedByPattern($this->simpleArray, $pattern));

        /*
         * Empty array
         */
        self::assertFalse(Arrays::areAllKeysMatchedByPattern([], $pattern));

        $array = [
            'a' => 'b',
            'c' => 'd',
        ];

        /*
         * Yet another simple array, but with strings as keys
         */
        self::assertFalse(Arrays::areAllKeysMatchedByPattern($array, $pattern));

        /*
         * The same array with another pattern
         */
        $pattern = '\w+';
        self::assertTrue(Arrays::areAllKeysMatchedByPattern($array, $pattern));

        /*
         * The same array with mixed keys (strings and integers as keys)
         */
        $array[1] = 'x';
        $pattern = '\d+';
        self::assertFalse(Arrays::areAllKeysMatchedByPattern($array, $pattern));

        /*
         * Multidimensional array - negative case
         */
        $array['e'] = ['f' => 'g'];
        self::assertFalse(Arrays::areAllKeysMatchedByPattern($array, $pattern));

        /*
         * Multidimensional array - positive case
         */
        unset($array[1]);
        $pattern = '\w+';
        self::assertTrue(Arrays::areAllKeysMatchedByPattern($array, $pattern));
    }

    public function testAreAllKeysIntegers()
    {
        self::assertEquals(1, Arrays::areAllKeysIntegers($this->simpleArray));
        self::assertEquals(2, Arrays::areAllKeysIntegers($this->simpleArray));
        self::assertEquals('', Arrays::areAllKeysIntegers($this->complexArray));
    }

    public function testIssetRecursive()
    {
        /*
         * Negative cases
         */
        self::assertFalse(Arrays::issetRecursive([], []));

        /*
         * Positive cases
         */
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
        /*
         * Negative cases
         */
        self::assertNull(Arrays::getValueByKeysPath([], []));

        /*
         * Positive cases
         */
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
        /*
         * Positive case - 1-dimension array
         */
        self::assertEquals(['ipsum'], Arrays::getAllValuesOfKey($this->simpleArray, 1));

        /*
         * Positive case - 2-dimensions array
         */
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

        /*
         * Positive case - multi-dimensions array
         */
        self::assertEquals(['primis'], Arrays::getAllValuesOfKey($this->complexArray, 1));

        /*
         * Positive case - multi-dimensions array
         */
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
        /*
         * Negative cases
         */
        $array = [];
        self::assertNull(Arrays::ksortRecursive($array));

        /*
         * Positive cases
         */
        self::assertEquals($this->simpleArray, Arrays::ksortRecursive($this->simpleArray));
        self::assertEquals($this->simpleArray, Arrays::ksortRecursive($this->simpleArray, SORT_NUMERIC));
        self::assertEquals($this->twoDimensionsArray, Arrays::ksortRecursive($this->twoDimensionsArray));

        /*
         * Positive case - multi-dimensions array
         */
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

        /*
         * Positive case - multi-dimensions array - with options of ksort() function
         */
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
        /*
         * Negative cases
         */
        self::assertEmpty(Arrays::setPositions([]));
        self::assertEquals([], Arrays::setPositions([]));

        /*
         * Positive case - 1-dimension array
         */
        $array = [
            'abc',
        ];

        self::assertEquals($array, Arrays::setPositions($array));

        /*
         * Positive case - 2-dimensions array
         */
        $effect = $this->twoDimensionsArray;
        $effect[0][Arrays::POSITION_KEY_NAME] = 1;
        $effect[1][Arrays::POSITION_KEY_NAME] = 2;
        $effect[2][Arrays::POSITION_KEY_NAME] = 3;

        self::assertEquals($effect, Arrays::setPositions($this->twoDimensionsArray));

        /*
         * Positive case - multi-level array
         */
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

        /*
         * Positive case - non-default name of key with position value & 2-dimensions array
         */
        $keyName = 'level';
        $effect = $this->twoDimensionsArray;

        $effect[0][$keyName] = 1;
        $effect[1][$keyName] = 2;
        $effect[2][$keyName] = 3;

        self::assertEquals($effect, Arrays::setPositions($this->twoDimensionsArray, $keyName));

        /*
         * Positive case - non-default start value of position & 2-dimensions array
         */
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
        /*
         * Negative cases
         */
        self::assertEquals([], Arrays::trimRecursive([]));

        /*
         * Positive cases
         */
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
        /*
         * Negative cases
         */
        self::assertNull(Arrays::sortByCustomKeysOrder([], []));

        /*
         * Positive cases
         */
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

        /*
         * Empty array
         */
        self::assertEmpty(Arrays::implodeSmart([], $separator));

        /*
         * Simple, one-dimension array
         */
        self::assertEquals(implode($separator, $this->simpleArray), Arrays::implodeSmart($this->simpleArray, $separator));

        /*
         * An array with elements that contain separator
         */
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

        /*
         * Complex array
         */
        self::assertEquals(implode($separator, [
            'donec',
            'quis',
            'elit',
        ]), Arrays::implodeSmart($this->complexArray['sit'], $separator));
    }

    public function testGetNextElement()
    {
        /*
         * Negative cases
         */
        self::assertNull(Arrays::getNextElement($this->simpleArray, 'amet'));
        self::assertNull(Arrays::getNextElement($this->simpleArray, 'xyz'));
        self::assertNull(Arrays::getNextElement($this->simpleArray, 0));
        self::assertNull(Arrays::getNextElement([], ''));
        self::assertNull(Arrays::getNextElement([], null));

        /*
         * Positive cases
         */
        self::assertEquals('ipsum', Arrays::getNextElement($this->simpleArray, 'Lorem'));
        self::assertEquals('sit', Arrays::getNextElement($this->simpleArray, 'dolor'));
    }

    public function testGetPreviousElement()
    {
        /*
         * Negative cases
         */
        self::assertNull(Arrays::getPreviousElement($this->simpleArray, 'Lorem'));
        self::assertNull(Arrays::getPreviousElement($this->simpleArray, 'xyz'));
        self::assertNull(Arrays::getPreviousElement($this->simpleArray, 0));
        self::assertNull(Arrays::getPreviousElement([], ''));
        self::assertNull(Arrays::getPreviousElement([], null));

        /*
         * Positive cases
         */
        self::assertEquals('ipsum', Arrays::getPreviousElement($this->simpleArray, 'dolor'));
        self::assertEquals('sit', Arrays::getPreviousElement($this->simpleArray, 'amet'));
    }

    public function testGetIndexOf()
    {
        /*
         * Negative cases
         */
        self::assertFalse(Arrays::getIndexOf([], 'a'));
        self::assertNull(Arrays::getIndexOf($this->simpleArray, 'loremmm'));

        /*
         * Positive cases
         */
        self::assertEquals(1, Arrays::getIndexOf($this->simpleArray, 'ipsum'));
        self::assertEquals('dolor', Arrays::getIndexOf($this->simpleArrayWithKeys, 'sit'));
        self::assertEquals('mollis', Arrays::getIndexOf($this->complexArray, 1234));
    }

    public function testIncrementIndexes()
    {
        /*
         * Negative cases
         */
        self::assertEquals([], Arrays::incrementIndexes([]));

        /*
         * Positive cases
         */
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
        /*
         * Negative cases
         */
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

        /*
         * Positive cases
         */
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
        /*
         * Negative cases
         */
        self::assertEquals([], Arrays::arrayDiffRecursive([], []));
        self::assertEquals([], Arrays::arrayDiffRecursive([], [], true));

        /*
         * Positive cases - full comparison (keys and values)
         */
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

        /*
         * Positive cases - simple comparison (values only)
         */
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
        /*
         * Basic cases
         */
        self::assertEquals(1, Arrays::getDimensionsCount([]));
        self::assertEquals(1, Arrays::getDimensionsCount(['']));

        /*
         * Simple cases
         */
        self::assertEquals(1, Arrays::getDimensionsCount($this->simpleArray));
        self::assertEquals(1, Arrays::getDimensionsCount($this->simpleArrayWithKeys));

        /*
         * Complex cases
         */
        self::assertEquals(2, Arrays::getDimensionsCount($this->twoDimensionsArray));
        self::assertEquals(4, Arrays::getDimensionsCount($this->complexArray));
    }

    public function testIsMultiDimensional()
    {
        /*
         * Negative cases
         */
        self::assertNull(Arrays::isMultiDimensional([]));

        /*
         * Positive cases
         */
        self::assertFalse(Arrays::isMultiDimensional($this->simpleArray));
        self::assertFalse(Arrays::isMultiDimensional($this->simpleArrayWithKeys));

        self::assertTrue(Arrays::isMultiDimensional($this->twoDimensionsArray));
        self::assertTrue(Arrays::isMultiDimensional($this->complexArray));
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
     * @return \Generator
     */
    public function provideStopIfMatchedByForGetLastElementsPaths()
    {
        /*
         * Special exception: do not use, stop recursive on the "diam" key
         */
        yield[
            'diam',
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

        /*
         * Stop building of paths on more sophisticated keys
         */
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

        /*
         * Stop building of paths on these paths (verify paths only)
         */
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

        /*
         * Stop building of paths if path contains any of these part (verify part of paths only)
         */
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
     * {@inheritdoc}
     */
    protected function setUp()
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
    protected function tearDown()
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
