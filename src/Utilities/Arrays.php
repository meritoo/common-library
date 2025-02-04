<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Utilities;

/**
 * Useful methods related to arrays
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class Arrays
{
    /**
     * Name of the array's key used to store position of element of the array
     *
     * @var string
     */
    public const POSITION_KEY_NAME = 'position';

    /**
     * Returns information if keys / indexes of given array are integers, in other words if the array contains
     * zero-based keys / indexes
     *
     * @param array $array          The array to check
     * @param bool  $firstLevelOnly (optional) If is set to true, all keys / indexes are checked. Otherwise - from the
     *                              first level only (default behaviour).
     * @return bool
     */
    public static function areAllKeysIntegers(array $array, bool $firstLevelOnly = false): bool
    {
        $pattern = '\d+';

        return self::areAllKeysMatchedByPattern($array, $pattern, $firstLevelOnly);
    }

    /**
     * Returns information if keys / indexes of given array are matched by given pattern
     *
     * @param array  $array          The array to check
     * @param string $pattern        The pattern which keys / indexes should match, e.g. "\d+"
     * @param bool   $firstLevelOnly (optional) If is set to true, all keys / indexes are checked. Otherwise - from the
     *                               first level only.
     * @return bool
     */
    public static function areAllKeysMatchedByPattern(array $array, string $pattern, bool $firstLevelOnly = false): bool
    {
        // No elements? Nothing to do
        if (empty($array)) {
            return false;
        }

        /*
         * I suppose that all are keys are matched
         * and then I have to look for keys that don't matches
         */
        $areMatched = true;

        // Building the pattern
        $rawPattern = $pattern;
        $pattern = sprintf('|%s|', $rawPattern);

        foreach ($array as $key => $value) {
            /*
             * Not matched? So I have to stop the iteration, because one not matched key
             * means that not all keys are matched by given pattern
             */
            if (!preg_match($pattern, $key)) {
                $areMatched = false;

                break;
            }

            /*
             * The not matching key was not found and the value is an array?
             * Let's begin recursive looking for result
             */
            if ($areMatched && is_array($value) && !$firstLevelOnly) {
                $areMatched = self::areAllKeysMatchedByPattern($value, $rawPattern, $firstLevelOnly);
            }
        }

        return $areMatched;
    }

    /**
     * Returns information if given array is empty, iow. information if all elements of given array are empty
     *
     * @param array $array      The array to verify
     * @param bool  $strictNull (optional) If is set to true elements are verified if they are null. Otherwise - only
     *                          if they are empty (e.g. null, '', 0, array()).
     * @return bool
     */
    public static function areAllValuesEmpty(array $array, bool $strictNull = false): bool
    {
        // No elements? Nothing to do
        if (empty($array)) {
            return false;
        }

        foreach ($array as $element) {
            /*
             * If elements are verified if they are exactly null and the element is:
             * - not an array
             * - not null
             * or elements are NOT verified if they are exactly null and the element is:
             * - not empty (e.g. null, '', 0, array())
             *
             * If one of the above is true, not all elements of given array are empty
             */
            if ((!is_array($element) && $strictNull && null !== $element) || !empty($element)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Returns information if given keys exist in given array
     *
     * @param array $keys     The keys to find
     * @param array $array    The array that maybe contains keys
     * @param bool  $explicit (optional) If is set to true, all keys should exist in given array. Otherwise - not all.
     * @return bool
     */
    public static function areKeysInArray(array $keys, array $array, bool $explicit = true): bool
    {
        $result = false;

        if (!empty($array)) {
            $firstKey = true;

            foreach ($keys as $key) {
                $exists = array_key_exists($key, $array);

                if ($firstKey) {
                    $result = $exists;
                    $firstKey = false;
                } elseif ($explicit) {
                    $result = $result && $exists;

                    if (!$result) {
                        break;
                    }
                } else {
                    $result = $result || $exists;

                    if ($result) {
                        break;
                    }
                }
            }
        }

        return $result;
    }

    /**
     * Generates JavaScript code for given PHP array
     *
     * @param array  $array           The array that should be generated to JavaScript
     * @param string $jsVariableName  (optional) Name of the variable that will be in generated JavaScript code.
     *                                Default: "autoGeneratedVariable".
     * @param bool   $preserveIndexes (optional) If is set to true and $jsVariableName isn't empty, indexes also
     *                                will be added to the JavaScript code. Otherwise not (default behaviour).
     * @return null|string
     */
    public static function array2JavaScript(
        array $array,
        string $jsVariableName = '',
        bool $preserveIndexes = false
    ): ?string {
        // No elements? Nothing to do
        if (empty($array)) {
            return null;
        }

        $result = '';
        $counter = 0;

        $arrayCount = count($array);
        $arrayPrepared = self::quoteStrings($array);
        $isMultiDimensional = false;

        if (null !== $arrayPrepared) {
            $isMultiDimensional = self::isMultiDimensional($arrayPrepared);
        }

        /*
         * Name of the variable was not provided and it's a multi dimensional array?
         * Let's create the name, because variable is required for later usage (related to multi dimensional array)
         */
        if (empty($jsVariableName) && $isMultiDimensional) {
            $jsVariableName = 'autoGeneratedVariable';
        }

        if (!empty($jsVariableName)) {
            $result .= sprintf('var %s = ', $jsVariableName);
        }

        $result .= 'new Array(';

        if ($preserveIndexes || $isMultiDimensional) {
            $result .= $arrayCount;
            $result .= ');';
        }

        if (null !== $arrayPrepared) {
            foreach ($arrayPrepared as $index => $value) {
                ++$counter;

                if (is_array($value)) {
                    $variable = $index;

                    if (is_int($index)) {
                        $variable = 'value_'.$variable;
                    }

                    $value = self::array2JavaScript($value, $variable, $preserveIndexes);

                    if (null !== $value && '' !== $value) {
                        /*
                         * Add an empty line for the 1st iteration only. Required to avoid missing empty line after
                         * declaration of variable:
                         *
                         * var autoGeneratedVariable = new Array(...);autoGeneratedVariable[0] = new Array(...);
                         * autoGeneratedVariable[1] = new Array(...);
                         */
                        if (1 === $counter) {
                            $result .= "\n";
                        }

                        $result .= $value."\n";
                        $result .= sprintf('%s[%s] = %s;', $jsVariableName, Miscellaneous::quoteValue($index), $variable);

                        if ($counter !== $arrayCount) {
                            $result .= "\n";
                        }
                    }
                } elseif ($preserveIndexes) {
                    if (!empty($jsVariableName)) {
                        $index = Miscellaneous::quoteValue($index);
                        $result .= sprintf("\n%s[%s] = %s;", $jsVariableName, $index, $value);
                    }
                } else {
                    $format = '%s';

                    if ($counter < $arrayCount) {
                        $format .= ', ';
                    }

                    $result .= sprintf($format, $value);
                }
            }
        }

        if (!$preserveIndexes && !$isMultiDimensional) {
            $result .= ');';
        }

        return $result;
    }

    /**
     * Returns an array containing all the entries from 1st array that are not present in 2nd array.
     * An item from 1st array is the same as in 2nd array if both, keys and values, are the same.
     *
     * Example of difference:
     * $array1 = [
     *      1 => 'Lorem',
     *      2 => 'ipsum,
     * ];
     *
     * $array2 = [
     *      1 => 'Lorem',
     *      5 => 'ipsum,        // <-- The same values, but different key. Here we got 5, in 1st array - 2.
     * ];
     *
     * @param array $array1     The 1st array to verify
     * @param array $array2     The 2nd array to verify
     * @param bool  $valuesOnly (optional) If is set to true, compares values only. Otherwise - keys and values
     *                          (default behaviour).
     * @return array
     */
    public static function arrayDiffRecursive(array $array1, array $array2, bool $valuesOnly = false): array
    {
        $result = [];

        /*
         * Values should be compared only and both arrays are one-dimensional?
         * Let's find difference by using simple function
         */
        if ($valuesOnly && 1 === self::getDimensionsCount($array1) && 1 === self::getDimensionsCount($array2)) {
            return array_diff($array1, $array2);
        }

        foreach ($array1 as $key => $value) {
            $array2HasKey = array_key_exists($key, $array2);

            // Values should be compared only?
            if ($valuesOnly) {
                $difference = null;

                if (is_array($value)) {
                    if ($array2HasKey && is_array($array2[$key])) {
                        $difference = self::arrayDiffRecursive($value, $array2[$key], $valuesOnly);
                    }
                } elseif (!$array2HasKey || $value !== $array2[$key]) {
                    /*
                     * We are here, because:
                     * a) 2nd array hasn't key from 1st array
                     * OR
                     * b) key exists in both, 1st and 2nd array, but values are different
                     */
                    $difference = $value;
                }

                if (null !== $difference) {
                    $result[] = $difference;
                }

                // The key exists in 2nd array?
            } elseif ($array2HasKey) {
                // The value it's an array (it's a nested array)?
                if (is_array($value)) {
                    $diff = [];

                    if (is_array($array2[$key])) {
                        // Let's verify the nested array
                        $diff = self::arrayDiffRecursive($value, $array2[$key], $valuesOnly);
                    }

                    if (empty($diff)) {
                        continue;
                    }

                    $result[$key] = $diff;
                } elseif ($value !== $array2[$key]) {
                    // Value is different than in 2nd array?
                    // OKay, I've got difference
                    $result[$key] = $value;
                }
            } else {
                // OKay, I've got difference
                $result[$key] = $value;
            }
        }

        return $result;
    }

    public static function containsEmptyStringsOnly(array $array): bool
    {
        if (empty($array)) {
            return false;
        }

        return '' === trim(implode('', $array));
    }

    /**
     * Returns all values of given key.
     * It may be useful when you want to retrieve all values of one column.
     *
     * @param array $array The array which should contain values of the key
     * @param mixed $key   The key
     * @return null|array
     */
    public static function getAllValuesOfKey(array $array, $key): ?array
    {
        // No elements? Nothing to do
        if (empty($array)) {
            return null;
        }

        $values = [];

        foreach ($array as $index => $value) {
            if ($index === $key) {
                $values[] = $value;

                continue;
            }

            if (is_array($value)) {
                $recursiveValues = self::getAllValuesOfKey($value, $key);

                if (!empty($recursiveValues)) {
                    $merged = array_merge($values, $recursiveValues);
                    $values = $merged;
                }
            }
        }

        return $values;
    }

    /**
     * Returns count of dimensions, maximum nesting level actually, in given array
     *
     * @param array $array The array to verify
     * @return int
     */
    public static function getDimensionsCount(array $array): int
    {
        // No elements? Nothing to do
        if (empty($array)) {
            return 0;
        }

        $dimensionsCount = 1;

        foreach ($array as $value) {
            if (is_array($value)) {
                /*
                 * I have to increment returned value, because that means we've got 1 level more (if the value is an
                 * array)
                 */
                $count = self::getDimensionsCount($value) + 1;

                if ($count > $dimensionsCount) {
                    $dimensionsCount = $count;
                }
            }
        }

        return $dimensionsCount;
    }

    public static function getElementsFromLevel(array $array, int $level, ?string $childrenKey = null): ?array
    {
        if (empty($array) || $level <= 0) {
            return null;
        }

        $result = [];

        foreach ($array as $key => $value) {
            // This is the expected level (the deepest). Comparing with 1, because level will be decreased by 1 (later),
            // and finally we will get the latest/deepest level that equals 1.
            if ($level === 1) {
                // No key of children (next level) provided or this is the same key as processed?
                // We've got the expected value
                if ($childrenKey === null || $key === $childrenKey) {
                    $result[] = $value;
                }

                continue;
            }

            // There is no deeper level
            if (!is_array($value)) {
                continue;
            }

            // Let's dive one level down/deeper
            $elements = self::getElementsFromLevel($value, $level - 1, $childrenKey);

            if ($elements === null) {
                continue;
            }

            // I have to load each element separately to avoid issue with incorrectly nested values
            foreach ($elements as $element) {
                $result[] = $element;
            }
        }

        return $result;
    }

    /**
     * Returns the first element of given array
     *
     * It may be first element of given array or the totally first element from the all elements (first element of the
     * first array).
     *
     * @param array $array          The array to get the first element of
     * @param bool  $firstLevelOnly (optional) If is set to true, first element is returned. Otherwise - totally
     *                              first element is returned (first of the first array).
     * @return mixed
     */
    public static function getFirstElement(array $array, bool $firstLevelOnly = true)
    {
        // No elements? Nothing to do
        if (empty($array)) {
            return null;
        }

        $firstKey = static::getFirstKey($array);
        $result = $array[$firstKey];

        if (!$firstLevelOnly && is_array($result)) {
            $result = static::getFirstElement($result, $firstLevelOnly);
        }

        return $result;
    }

    /**
     * Returns first key of array
     *
     * @param array $array The array to get the first key of
     * @return int|string|null
     */
    public static function getFirstKey(array $array)
    {
        // No elements? Nothing to do
        if (empty($array)) {
            return null;
        }

        $keys = array_keys($array);

        return $keys[0];
    }

    /**
     * Returns an index / key of given element in given array
     *
     * @param array $array   The array to verify
     * @param mixed $element The element who index / key is needed
     * @return false|int|string|null
     */
    public static function getIndexOf(array $array, $element)
    {
        // No elements? Nothing to do
        if (empty($array)) {
            return false;
        }

        foreach ($array as $index => $value) {
            if ($value === $element) {
                return $index;
            }
        }

        return null;
    }

    /**
     * Returns the last element of given array
     *
     * It may be last element of given array or the totally last element from the all elements (last element of the
     * latest array).
     *
     * @param array $array          The array to get the last element of
     * @param bool  $firstLevelOnly (optional) If is set to true, last element is returned. Otherwise - totally
     *                              last element is returned (last of the latest array).
     * @return mixed
     */
    public static function getLastElement(array $array, bool $firstLevelOnly = true)
    {
        // No elements? Nothing to do
        if (empty($array)) {
            return null;
        }

        $last = end($array);

        if (!$firstLevelOnly && is_array($last)) {
            $last = static::getLastElement($last, $firstLevelOnly);
        }

        return $last;
    }

    /**
     * Returns breadcrumb (a path) to the last element of array
     *
     * @param array  $array     Data to get the breadcrumb
     * @param string $separator (optional) Separator used to stick the elements. Default: "/".
     * @return null|string
     */
    public static function getLastElementBreadCrumb(array $array, string $separator = '/'): ?string
    {
        // No elements? Nothing to do
        if (empty($array)) {
            return null;
        }

        $keys = array_keys($array);
        $keysCount = count($keys);

        $lastKey = $keys[$keysCount - 1];
        $last = end($array);

        $breadCrumb = $lastKey;

        if (is_array($last)) {
            $crumb = self::getLastElementBreadCrumb($last, $separator);
        } else {
            $crumb = $last;
        }

        return $breadCrumb.$separator.$crumb;
    }

    /**
     * Returns paths of the last elements
     *
     * @param array  $array           The array with elements
     * @param string $separator       (optional) Separator used between elements. Default: ".".
     * @param string $parentPath      (optional) Path of the parent element. Default: "".
     * @param array  $stopIfMatchedBy (optional) Patterns of keys or paths when matched will stop the process of path
     *                                building and including children of those keys or paths (recursive will not be
     *                                used for keys in lower level of given array). Default: [].
     * @return null|array
     *
     * Examples - $stopIfMatchedBy argument:
     * a) "\d+"
     * b) [
     *      "lorem\-",
     *      "\d+",
     * ];
     */
    public static function getLastElementsPaths(
        array $array,
        string $separator = '.',
        string $parentPath = '',
        array $stopIfMatchedBy = []
    ): ?array {
        // No elements? Nothing to do
        if (empty($array)) {
            return null;
        }

        $result = [];

        foreach ($array as $key => $value) {
            $path = $key;
            $stopRecursion = false;
            $valueIsArray = is_array($value);

            /*
             * If the path of parent element is delivered,
             * I have to use it and build longer path
             */
            if (!empty($parentPath)) {
                $pathTemplate = '%s%s%s';
                $path = sprintf($pathTemplate, $parentPath, $separator, $key);
            }

            /*
             * Check if the key or current path matches one of patterns at which the process should be stopped,
             * the recursive not used. It means that I have to pass current value and stop processing of the
             * array (don't go to the next step).
             */
            if (!empty($stopIfMatchedBy)) {
                foreach ($stopIfMatchedBy as $rawPattern) {
                    $pattern = sprintf('|%s|', $rawPattern);

                    if (preg_match($pattern, $key) || preg_match($pattern, $path)) {
                        $stopRecursion = true;

                        break;
                    }
                }
            }

            /*
             * The value is passed to the returned array if:
             * - the process is stopped, recursive is not used
             * or
             * - it's not an array
             * or
             * - it's an array, but empty
             */
            if ($stopRecursion || !$valueIsArray || self::isEmptyArray($value)) {
                $result[$path] = $value;

                continue;
            }

            // Let's iterate through the next level, using recursive
            $recursivePaths = self::getLastElementsPaths($value, $separator, $path, $stopIfMatchedBy);

            if (null !== $recursivePaths) {
                $result += $recursivePaths;
            }
        }

        return $result;
    }

    /**
     * Returns last key of array
     *
     * @param array $array The array to get the last key of
     * @return mixed
     */
    public static function getLastKey(array $array)
    {
        // No elements? Nothing to do
        if (empty($array)) {
            return null;
        }

        $keys = array_keys($array);

        return end($keys);
    }

    /**
     * Returns the last row of array
     *
     * @param array $array The array to get the last row of
     * @return mixed
     */
    public static function getLastRow(array $array): ?array
    {
        // No elements? Nothing to do
        if (empty($array)) {
            return null;
        }

        $result = [];
        $last = end($array);

        if (is_array($last)) {
            // We've got an array, so looking for the last row of array will be done recursively
            $result = static::getLastRow($last);

            /*
             * The last row is not an array or it's an empty array?
             * Let's use the previous candidate
             */
            if (!is_array($result) || static::isEmptyArray($result)) {
                $result = $last;
            }
        }

        return $result;
    }

    /**
     * Returns next element of given array related to given element
     *
     * @param array $array   The array with elements
     * @param mixed $element Element for who next element should be returned
     * @return null|mixed
     */
    public static function getNextElement(array $array, $element)
    {
        return self::getNeighbour($array, $element);
    }

    /**
     * Returns count / amount of elements that are not array
     *
     * @param array $array The array to count
     * @return null|int
     */
    public static function getNonArrayElementsCount(array $array): ?int
    {
        // No elements? Nothing to do
        if (empty($array)) {
            return null;
        }

        $count = 0;

        foreach ($array as $value) {
            if (is_array($value)) {
                $count += (int) self::getNonArrayElementsCount($value);

                continue;
            }

            ++$count;
        }

        return $count;
    }

    /**
     * Returns non-empty values, e.g. without "" (empty string), null or []
     *
     * @param array $values The values to filter
     * @return null|array
     */
    public static function getNonEmptyValues(array $values): ?array
    {
        // No values? Nothing to do
        if (empty($values)) {
            return null;
        }

        return array_filter($values, static function ($value): bool {
            $nonEmptyScalar = is_scalar($value) && '' !== $value;
            $nonEmptyArray = self::isNotEmptyArray($value);

            return $nonEmptyScalar || $nonEmptyArray || is_object($value);
        });
    }

    /**
     * Returns non-empty values concatenated by given separator
     *
     * @param array  $values    The values to filter
     * @param string $separator (optional) Separator used to implode the values. Default: ", ".
     * @return null|string
     */
    public static function getNonEmptyValuesAsString(array $values, string $separator = ', '): ?string
    {
        // No elements? Nothing to do
        if (empty($values)) {
            return null;
        }

        $nonEmpty = self::getNonEmptyValues($values);

        // No values? Nothing to do
        if (empty($nonEmpty)) {
            return '';
        }

        return implode($separator, $nonEmpty);
    }

    /**
     * Returns previous element of given array related to given element
     *
     * @param array $array   The array with elements
     * @param mixed $element Element for who previous element should be returned
     * @return null|mixed
     */
    public static function getPreviousElement(array $array, $element)
    {
        return self::getNeighbour($array, $element, false);
    }

    /**
     * Returns value of given array set under given path of keys, of course if the value exists.
     * The keys should be delivered in the same order as used by source array.
     *
     * @param array $array The array which should contains a value
     * @param array $keys  Keys, path of keys, to find in given array
     * @return mixed
     *
     * Examples:
     * a) $array
     * [
     *      'some key' => [
     *          'another some key' => [
     *              'yet another key' => 123,
     *          ],
     *          'some different key' => 456,
     *      ]
     * ]
     *
     * b) $keys
     * [
     *      'some key',
     *      'another some key',
     *      'yet another key',
     * ]
     *
     * Based on the above examples will return:
     * 123
     */
    public static function getValueByKeysPath(array $array, array $keys)
    {
        // No elements? Nothing to do
        if (empty($array)) {
            return null;
        }

        $value = null;

        if (self::issetRecursive($array, $keys)) {
            foreach ($keys as $key) {
                $value = $array[$key];
                array_shift($keys);

                if (is_array($value) && !empty($keys)) {
                    $value = self::getValueByKeysPath($value, $keys);
                }

                break;
            }
        }

        return $value;
    }

    /**
     * Returns smartly imploded string
     *
     * Separators located at the beginning or end of elements are removed.
     * It's required to avoid problems with duplicated separator, e.g. "first//second/third", where separator is a
     * "/" string.
     *
     * @param array  $array     The array with elements to implode
     * @param string $separator Separator used to stick together elements of given array
     * @return null|string
     */
    public static function implodeSmart(array $array, string $separator): ?string
    {
        // No elements? Nothing to do
        if (empty($array)) {
            return null;
        }

        foreach ($array as &$element) {
            if (is_array($element)) {
                $element = self::implodeSmart($element, $separator);
            }

            if (Regex::startsWith($element, $separator)) {
                $element = substr($element, 1);
            }

            if (Regex::endsWith($element, $separator)) {
                $element = substr($element, 0, -1);
            }
        }

        return implode($separator, $array);
    }

    /**
     * Returns an array with incremented indexes / keys
     *
     * @param array    $array         The array which indexes / keys should be incremented
     * @param null|int $startIndex    (optional) Index from which incrementation should be started. If not provided,
     *                                the first index / key will be used.
     * @param int      $incrementStep (optional) Value used for incrementation. The step of incrementation.
     * @return null|array
     */
    public static function incrementIndexes(array $array, ?int $startIndex = null, int $incrementStep = 1): ?array
    {
        // No elements? Nothing to do
        if (empty($array)) {
            return null;
        }

        $valuesToIncrement = [];

        /*
         * Start index not provided?
         * Let's look for the first index / key of given array
         */
        if (null === $startIndex) {
            $firstKey = self::getFirstKey($array);

            if ($firstKey !== null) {
                /** @var int $firstKey */
                $startIndex = $firstKey;
            }
        }

        /*
         * Is the start index a numeric value?
         * Other indexes / keys cannot be incremented
         */
        if (is_numeric($startIndex)) {
            /*
             * 1st step:
             * Get values which indexes should be incremented and remove those values from given array
             */
            foreach ($array as $index => $value) {
                if ($index < $startIndex) {
                    continue;
                }

                $valuesToIncrement[$index] = $value;
                unset($array[$index]);
            }

            /*
             * 2nd step:
             * Increment indexes of gathered values
             */
            if (!empty($valuesToIncrement)) {
                foreach ($valuesToIncrement as $oldIndex => $value) {
                    $newIndex = (int) $oldIndex + $incrementStep;
                    $array[$newIndex] = $value;
                }
            }
        }

        return $array;
    }

    /**
     * Returns information if given value is an array and is empty
     *
     * @param mixed $value The value to verify
     * @return bool
     */
    public static function isEmptyArray($value): bool
    {
        return is_array($value) && empty($value);
    }

    /**
     * Returns information if given element is the first one
     *
     * @param array $array          The array to get the first element of
     * @param mixed $element        The element to check / verify
     * @param bool  $firstLevelOnly (optional) If is set to true, first element is returned. Otherwise - totally
     *                              first element is returned (first of the First array).
     * @return bool
     */
    public static function isFirstElement(array $array, $element, bool $firstLevelOnly = true): bool
    {
        $firstElement = static::getFirstElement($array, $firstLevelOnly);

        return $element === $firstElement;
    }

    /**
     * Returns information if given element is the last one
     *
     * @param array $array          The array to get the last element of
     * @param mixed $element        The element to check / verify
     * @param bool  $firstLevelOnly (optional) If is set to true, last element is returned. Otherwise - totally
     *                              last element is returned (last of the latest array).
     * @return bool
     */
    public static function isLastElement(array $array, $element, bool $firstLevelOnly = true): bool
    {
        $lastElement = static::getLastElement($array, $firstLevelOnly);

        return $element === $lastElement;
    }

    /**
     * Returns information if given array is a multi dimensional array
     *
     * @param array $array The array to verify
     * @return null|bool
     */
    public static function isMultiDimensional(array $array): ?bool
    {
        // No elements? Nothing to do
        if (empty($array)) {
            return null;
        }

        return count($array) !== count($array, COUNT_RECURSIVE);
    }

    /**
     * Returns information if given value is non-empty array
     *
     * @param mixed $value The value to verify
     * @return bool
     */
    public static function isNotEmptyArray($value): bool
    {
        return is_array($value) && !empty($value);
    }

    /**
     * Returns information if given path of keys are set is given array.
     * The keys should be delivered in the same order as used by source array.
     *
     * @param array $array The array to check
     * @param array $keys  Keys, path of keys, to find in given array
     * @return bool
     *
     * Examples:
     * a) $array
     * [
     *      'some key' => [
     *          'another some key' => [
     *              'yet another key' => 123,
     *          ],
     *          'some different key' => 456,
     *      ]
     * ]
     *
     * b) $keys
     * [
     *      'some key',
     *      'another some key',
     *      'yet another key',
     * ]
     */
    public static function issetRecursive(array $array, array $keys): bool
    {
        // No elements? Nothing to do
        if (empty($array)) {
            return false;
        }

        $isset = false;

        foreach ($keys as $key) {
            $isset = isset($array[$key]);

            if ($isset) {
                $newArray = $array[$key];
                array_shift($keys);

                if (is_array($newArray) && !empty($keys)) {
                    $isset = self::issetRecursive($newArray, $keys);
                }
            }

            break;
        }

        return $isset;
    }

    /**
     * Applies ksort() function recursively in the given array
     *
     * @param array $array     The array to sort
     * @param int   $sortFlags (optional) Options of ksort() function
     * @return null|array
     */
    public static function ksortRecursive(array &$array, int $sortFlags = SORT_REGULAR): ?array
    {
        // No elements? Nothing to do
        if (empty($array)) {
            return null;
        }

        $effect = &$array;
        ksort($effect, $sortFlags);

        foreach ($effect as &$value) {
            if (is_array($value)) {
                ksort($value, $sortFlags);
            }
        }

        return $effect;
    }

    /**
     * Makes and returns an array for given variable
     *
     * @param mixed $variable Variable that should be an array
     * @return array
     */
    public static function makeArray($variable): array
    {
        if (is_array($variable)) {
            return $variable;
        }

        return [$variable];
    }

    /**
     * Quotes (adds quotes) to elements that are strings and returns new array (with quoted elements)
     *
     * @param array $array The array to check for string values
     * @return null|array
     */
    public static function quoteStrings(array $array): ?array
    {
        // No elements? Nothing to do
        if (empty($array)) {
            return null;
        }

        $result = [];

        foreach ($array as $index => $value) {
            if (is_array($value)) {
                $value = self::quoteStrings($value);
            } elseif (is_string($value) && !Regex::isQuoted($value)) {
                $value = '\''.$value.'\'';
            }

            $result[$index] = $value;
        }

        return $result;
    }

    /**
     * Removes element / item of given array
     *
     * @param array $array The array that contains element / item which should be removed
     * @param mixed $item  The element / item which should be removed
     * @return array|bool
     */
    public static function removeElement(array $array, $item)
    {
        // No elements or the element does not exist? Nothing to do
        if (empty($array) || !in_array($item, $array, true)) {
            return false;
        }

        // Flip the array to make it looks like: value => key
        $arrayFlipped = array_flip($array);

        // Take the key of element / item that should be removed
        $key = $arrayFlipped[$item];

        // ...and remove the element / item
        unset($array[$key]);

        return $array;
    }

    /**
     * Removes items from given array starting at given element (before or after the element)
     *
     * @param array $array  The array which contains items to remove
     * @param mixed $needle The element which is start point of deletion
     * @param bool  $before (optional) If is set to true, all elements before given needle are removed. Otherwise - all
     *                      after needle.
     */
    public static function removeElements(array &$array, $needle, $before = true): void
    {
        if (!empty($array)) {
            if (!$before) {
                $array = array_reverse($array, true);
            }

            foreach ($array as $key => &$value) {
                $remove = false;
                $isArray = is_array($value);

                if ($isArray) {
                    self::removeElements($value, $needle, $before);

                    if (empty($value)) {
                        $remove = true;
                    }
                } elseif ($value === $needle) {
                    break;
                } else {
                    $remove = true;
                }

                if ($remove) {
                    unset($array[$key]);
                }
            }

            unset($value);

            if (!$before) {
                $array = array_reverse($array, true);
            }
        }
    }

    /**
     * Removes marginal element (first or last) from given array
     *
     * @param array $array The array which should be shortened
     * @param bool  $last  (optional) If is set to true, last element is removed (default behaviour). Otherwise - first.
     * @return null|array
     */
    public static function removeMarginalElement(array $array, bool $last = true): ?array
    {
        // No elements? Nothing to do
        if (empty($array)) {
            return null;
        }

        $key = self::getFirstKey($array);

        if ($last) {
            $key = self::getLastKey($array);
        }

        unset($array[$key]);

        return $array;
    }

    /**
     * Replaces array keys that match given pattern with new key name
     *
     * @param array  $array         Array which keys should be replaced
     * @param string $oldKeyPattern Regular expression of the old key
     * @param string $newKey        Name of the new key
     * @return null|array
     */
    public static function replaceKeys(array $array, string $oldKeyPattern, string $newKey): ?array
    {
        if (empty($array)) {
            return null;
        }

        $effect = [];

        foreach ($array as $key => $value) {
            if (preg_match($oldKeyPattern, $key)) {
                $key = $newKey;
            }

            if (is_array($value)) {
                $value = self::replaceKeys($value, $oldKeyPattern, $newKey);
            }

            $effect[$key] = $value;
        }

        return $effect;
    }

    /**
     * Sets keys as values and values as keys in given array.
     * Replaces keys with values.
     *
     * @param array $array                  The array to change values with keys
     * @param bool  $ignoreDuplicatedValues (optional) If is set to true, duplicated values are ignored. This means that
     *                                      when there is more than 1 value and that values become key, only the last
     *                                      value will be used with it's key, because other will be overridden.
     *                                      Otherwise - values are preserved and keys assigned to that values are
     *                                      returned as an array.
     * @return null|array
     *
     * Example of $ignoreDuplicatedValues = false:
     * - provided array
     * $array = [
     *      'lorem' => 100,     // <-- Duplicated value
     *      'ipsum' => 200,
     *      'dolor' => 100,     // <-- Duplicated value
     * ];
     *
     * - result
     * $replaced = [
     *      100 => [
     *          'lorem',        // <-- Key of duplicated value
     *          'dolor',        // <-- Key of duplicated value
     *      ],
     *      200 => 'ipsum',
     * ];
     */
    public static function setKeysAsValues(array $array, bool $ignoreDuplicatedValues = true): ?array
    {
        // No elements? Nothing to do
        if (empty($array)) {
            return null;
        }

        $replaced = [];

        foreach ($array as $key => $value) {
            /*
             * The value it's an array?
             * Let's replace keys with values in this array first
             */
            if (is_array($value)) {
                $replaced[$key] = self::setKeysAsValues($value, $ignoreDuplicatedValues);

                continue;
            }

            // Duplicated values shouldn't be ignored and processed value is used as key already?
            // Let's use an array and that will contain all values (to avoid ignoring / overriding duplicated values)
            if (!$ignoreDuplicatedValues && isset($replaced[$value])) {
                $existing = self::makeArray($replaced[$value]);

                $replaced[$value] = array_merge($existing, [
                    $key,
                ]);

                continue;
            }

            // Standard behaviour
            $replaced[$value] = $key;
        }

        return $replaced;
    }

    /**
     * Sets positions for each element / child of given array and returns the array
     *
     * Position for the 1st element / child of a parent is set to 1 and incremented for the next element and
     * so on. Each parent is treated as separate array, so its elements are treated as positioned at 1st level.
     *
     * @param array    $array         The array which should has values of position for each element
     * @param string   $keyName       (optional) Name of key which will contain the position value
     * @param int|null $startPosition (optional) Default, start value of the position for main / given array, not the
     *                                children
     * @return null|array
     */
    public static function setPositions(
        array $array,
        string $keyName = self::POSITION_KEY_NAME,
        int $startPosition = null
    ): ?array {
        // No elements? Nothing to do
        if (empty($array)) {
            return null;
        }

        $childPosition = 1;

        if (null !== $startPosition) {
            $array[$keyName] = $startPosition;
        }

        foreach ($array as &$value) {
            if (is_array($value)) {
                $value = self::setPositions($value, $keyName, $childPosition);
                ++$childPosition;
            }
        }

        return $array;
    }

    /**
     * Sorts an array by keys given in second array as values.
     * Keys which are not in array with order are pushed after sorted elements.
     *
     * Example:
     * - array to sort:
     * <code>
     *      array(
     *          'lorem' => array(
     *              'ipsum'
     *          ),
     *          'dolor' => array(
     *              'sit',
     *              'amet'
     *          ),
     *          'neque' => 'neque'
     *      )
     * </code>
     * - keys order:
     * <code>
     *      array(
     *          'dolor',
     *          'lorem'
     *      )
     * </code>
     * - the result:
     * <code>
     *      array(
     *          'dolor' => array(
     *              'sit',
     *              'amet'
     *          ),
     *          'lorem' => array(
     *              'ipsum'
     *          ),
     *          'neque' => 'neque'        // <-- the rest, values of other keys
     *      )
     * </code>
     *
     * @param array $array     An array to sort
     * @param array $keysOrder An array with keys of the 1st argument in proper / required order
     * @return null|array
     */
    public static function sortByCustomKeysOrder(array $array, array $keysOrder): ?array
    {
        // No elements? Nothing to do
        if (empty($array)) {
            return null;
        }

        $ordered = [];

        /*
         * 1st iteration:
         * Get elements in proper / required order
         */
        if (!empty($keysOrder)) {
            foreach ($keysOrder as $key) {
                if (isset($array[$key])) {
                    $ordered[$key] = $array[$key];
                    unset($array[$key]);
                }
            }
        }

        /*
         * 2nd iteration:
         * Get the rest of elements
         */
        if (!empty($array)) {
            foreach ($array as $key => $element) {
                $ordered[$key] = $element;
            }
        }

        return $ordered;
    }

    /**
     * Converts given string with special separators to array
     *
     * Example:
     * ~ string:
     * "light:jasny|dark:ciemny"
     *
     * ~ array as a result:
     * [
     *      'light' => 'jasny',
     *      'dark'  => 'ciemny',
     * ]
     *
     * @param string $string              The string to be converted
     * @param string $separator           (optional) Separator used between name-value pairs in the string.
     *                                    Default: "|".
     * @param string $valuesKeysSeparator (optional) Separator used between name and value in the string. Default: ":".
     * @return null|array
     */
    public static function string2array(
        string $string,
        string $separator = '|',
        string $valuesKeysSeparator = ':'
    ): ?array {
        // Empty string? Nothing to do
        if (empty($string)) {
            return null;
        }

        $array = [];
        $exploded = explode($separator, $string);

        foreach ($exploded as $item) {
            $exploded2 = explode($valuesKeysSeparator, $item);

            if (2 === count($exploded2)) {
                $key = trim($exploded2[0]);
                $value = trim($exploded2[1]);

                $array[$key] = $value;
            }
        }

        return $array;
    }

    /**
     * Trims string values of given array and returns the new array
     *
     * @param array $array The array which values should be trimmed
     * @return array
     */
    public static function trimRecursive(array $array): array
    {
        // No elements? Nothing to do
        if (empty($array)) {
            return [];
        }

        $result = [];

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result[$key] = self::trimRecursive($value);

                continue;
            }

            if (is_string($value)) {
                $value = trim($value);
            }

            $result[$key] = $value;
        }

        return $result;
    }

    /**
     * Converts given array's rows to csv string
     *
     * @param array $array Data to be converted. It has to be an array that represents database table.
     * @param string $separator (optional) Separator used between values. Default: ",".
     *
     * @return null|string
     */
    public static function values2csv(array $array, string $separator = ','): ?string
    {
        // No elements? Nothing to do
        if (empty($array)) {
            return null;
        }

        $rows = [];
        $lineSeparator = "\n";

        foreach ($array as $row) {
            /*
             * I have to use html_entity_decode() function here, because some string values can contain
             * entities with semicolon and this can destroy the CSV column order.
             */

            if (is_array($row) && !empty($row)) {
                foreach ($row as $key => $value) {
                    if (empty($value)) {
                        continue;
                    }

                    $row[$key] = html_entity_decode($value);
                }

                $rows[] = implode($separator, $row);
            }
        }

        if (empty($rows)) {
            return '';
        }

        return implode($lineSeparator, $rows);
    }

    /**
     * Converts given array's column to string.
     * Recursive call is made for multi-dimensional arrays.
     *
     * @param array      $array          Data to be converted
     * @param int|string $arrayColumnKey (optional) Column name. Default: "".
     * @param string     $separator      (optional) Separator used between values. Default: ",".
     * @return null|string
     */
    public static function values2string(array $array, $arrayColumnKey = '', string $separator = ','): ?string
    {
        // No elements? Nothing to do
        if (empty($array)) {
            return null;
        }

        $values = [];

        foreach ($array as $key => $value) {
            $appendMe = null;

            if (is_array($value)) {
                $appendMe = self::values2string($value, $arrayColumnKey, $separator);
            } elseif (empty($arrayColumnKey)) {
                $appendMe = $value;
            } elseif ($key === $arrayColumnKey) {
                $appendMe = $array[$arrayColumnKey];
            }

            /*
             * Part to append is unknown?
             * Let's go to next part
             */
            if (null === $appendMe) {
                continue;
            }

            $values[] = $appendMe;
        }

        // No values found? Nothing to do
        if (empty($values)) {
            return null;
        }

        return implode($separator, $values);
    }

    /**
     * Converts given array to string with keys, e.g. abc=1&def=2 or abc="1" def="2"
     *
     * @param array  $array               Data to be converted
     * @param string $separator           (optional) Separator used between name-value pairs. Default: ",".
     * @param string $valuesKeysSeparator (optional) Separator used between name and value. Default: "=".
     * @param string $valuesWrapper       (optional) Wrapper used to wrap values, e.g. double-quote: key="value".
     *                                    Default: "".
     * @return null|string
     */
    public static function valuesKeys2string(
        array $array,
        string $separator = ',',
        string $valuesKeysSeparator = '=',
        string $valuesWrapper = ''
    ): ?string {
        // No elements? Nothing to do
        if (empty($array)) {
            return null;
        }

        $result = '';

        foreach ($array as $key => $value) {
            if (!empty($result)) {
                $result .= $separator;
            }

            if (!empty($valuesWrapper)) {
                $value = sprintf('%s%s%s', $valuesWrapper, $value, $valuesWrapper);
            }

            $result .= $key.$valuesKeysSeparator.$value;
        }

        return $result;
    }

    /**
     * Returns neighbour (next or previous element) for given element
     *
     * @param array $array   The array with elements
     * @param mixed $element Element for who next element should be returned
     * @param bool  $next    (optional) If is set to true, returns next neighbour. Otherwise - previous.
     * @return null|mixed
     */
    private static function getNeighbour(array $array, $element, bool $next = true)
    {
        // No elements? Nothing to do
        if (empty($array)) {
            return null;
        }

        $noNext = $next && self::isLastElement($array, $element);
        $noPrevious = !$next && self::isFirstElement($array, $element);

        /*
         * Previous neighbour should be returned and given element is first?
         * OR
         * Next neighbour should be returned and given element is last?
         * OR
         * No elements?
         * OR
         * Given element does not exist in given array?
         *
         * Nothing to do
         */
        if ($noPrevious || $noNext || !in_array($element, $array, true)) {
            return null;
        }

        $neighbourKey = null;
        $keys = array_keys($array);
        $elementKey = self::getIndexOf($array, $element);
        $indexOfKey = self::getIndexOf($keys, $elementKey);

        /*
         * Index of element or of element's key is unknown?
         * Probably the element does not exist in given array, so... nothing to do
         */
        if (null === $elementKey || null === $indexOfKey) {
            return null;
        }

        // Looking for key of the neighbour (next or previous element)
        if ($next) {
            ++$indexOfKey;
        } else {
            --$indexOfKey;
        }

        /*
         * Let's prepare key of the neighbour and...
         * ...we've got the neighbour :)
         */
        $neighbourKey = $keys[$indexOfKey];

        return $array[$neighbourKey];
    }
}
