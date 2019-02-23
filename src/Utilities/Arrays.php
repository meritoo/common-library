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
    const POSITION_KEY_NAME = 'position';

    /**
     * Converts given array's column to string.
     * Recursive call is made for multi-dimensional arrays.
     *
     * @param array      $array          Data to be converted
     * @param string|int $arrayColumnKey (optional) Column name. Default: "".
     * @param string     $separator      (optional) Separator used between values. Default: ",".
     * @return string|null
     */
    public static function values2string(array $array, $arrayColumnKey = '', $separator = ',')
    {
        /*
         * No elements?
         * Nothing to do
         */
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

        /*
         * No values found?
         * Nothing to do
         */
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
     * @return string|null
     */
    public static function valuesKeys2string(
        array $array,
        $separator = ',',
        $valuesKeysSeparator = '=',
        $valuesWrapper = ''
    ) {
        /*
         * No elements?
         * Nothing to do
         */
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

            $result .= $key . $valuesKeysSeparator . $value;
        }

        return $result;
    }

    /**
     * Converts given array's rows to csv string
     *
     * @param array  $array     Data to be converted. It have to be an array that represents database table.
     * @param string $separator (optional) Separator used between values. Default: ",".
     * @return string|null
     */
    public static function values2csv(array $array, $separator = ',')
    {
        /*
         * No elements?
         * Nothing to do
         */
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
     * Returns information if given element is the first one
     *
     * @param array $array          The array to get the first element of
     * @param mixed $element        The element to check / verify
     * @param bool  $firstLevelOnly (optional) If is set to true, first element is returned. Otherwise - totally
     *                              first element is returned (first of the First array).
     * @return bool
     */
    public static function isFirstElement(array $array, $element, $firstLevelOnly = true)
    {
        $firstElement = self::getFirstElement($array, $firstLevelOnly);

        return $element === $firstElement;
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
    public static function getFirstElement(array $array, $firstLevelOnly = true)
    {
        /*
         * No elements?
         * Nothing to do
         */
        if (empty($array)) {
            return null;
        }

        $firstKey = self::getFirstKey($array);
        $first = $array[$firstKey];

        if (!$firstLevelOnly && is_array($first)) {
            $first = self::getFirstElement($first, $firstLevelOnly);
        }

        return $first;
    }

    /**
     * Returns first key of array
     *
     * @param array $array The array to get the first key of
     * @return mixed
     */
    public static function getFirstKey(array $array)
    {
        /*
         * No elements?
         * Nothing to do
         */
        if (empty($array)) {
            return null;
        }

        $keys = array_keys($array);

        return $keys[0];
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
    public static function isLastElement(array $array, $element, $firstLevelOnly = true)
    {
        $lastElement = self::getLastElement($array, $firstLevelOnly);

        return $element === $lastElement;
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
    public static function getLastElement(array $array, $firstLevelOnly = true)
    {
        /*
         * No elements?
         * Nothing to do
         */
        if (empty($array)) {
            return null;
        }

        $last = end($array);

        if (!$firstLevelOnly && is_array($last)) {
            $last = self::getLastElement($last, $firstLevelOnly);
        }

        return $last;
    }

    /**
     * Returns breadcrumb (a path) to the last element of array
     *
     * @param array  $array     Data to get the breadcrumb
     * @param string $separator (optional) Separator used to stick the elements. Default: "/".
     * @return string|null
     */
    public static function getLastElementBreadCrumb(array $array, $separator = '/')
    {
        /*
         * No elements?
         * Nothing to do
         */
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

        return $breadCrumb . $separator . $crumb;
    }

    /**
     * Returns the last row of array
     *
     * @param array $array The array to get the last row of
     * @return mixed
     */
    public static function getLastRow(array $array)
    {
        /*
         * No elements?
         * Nothing to do
         */
        if (empty($array)) {
            return null;
        }

        $effect = [];
        $last = end($array);

        if (is_array($last)) {
            /*
             * We've got an array, so looking for the last row of array will be done recursively
             */
            $effect = self::getLastRow($last);

            /*
             * The last row is not an array or it's an empty array?
             * Let's use the previous candidate
             */
            if (!is_array($effect) || (is_array($effect) && empty($effect))) {
                $effect = $last;
            }
        }

        return $effect;
    }

    /**
     * Replaces array keys that match given pattern with new key name
     *
     * @param array  $dataArray     The array
     * @param string $oldKeyPattern Old key pattern
     * @param string $newKey        New key name
     * @return array
     */
    public static function replaceArrayKeys($dataArray, $oldKeyPattern, $newKey)
    {
        $effect = [];

        if (is_array($dataArray) && !empty($dataArray)) {
            foreach ($dataArray as $key => $value) {
                if (preg_match($oldKeyPattern, $key)) {
                    $key = $newKey;
                }

                if (is_array($value)) {
                    $value = self::replaceArrayKeys($value, $oldKeyPattern, $newKey);
                }

                $effect[$key] = $value;
            }
        }

        return $effect;
    }

    /**
     * Generates JavaScript code for given PHP array
     *
     * @param array  $array           The array that should be generated to JavaScript
     * @param string $jsVariableName  (optional) Name of the variable that will be in generated JavaScript code
     * @param bool   $preserveIndexes (optional) If is set to true and $jsVariableName isn't empty, indexes also
     *                                will be added to the JavaScript code. Otherwise not.
     * @return string|null
     */
    public static function array2JavaScript(array $array, $jsVariableName = '', $preserveIndexes = false)
    {
        /*
         * No elements?
         * Nothing to do
         */
        if (empty($array)) {
            return null;
        }

        $result = '';
        $counter = 0;

        $arrayCount = count($array);
        $array = self::quoteStrings($array);
        $isMultiDimensional = self::isMultiDimensional($array);

        /*
         * Name of the variable was not provided and it's a multi dimensional array?
         * Let's create the name, because variable is required for later usage (related to multi dimensional array)
         */
        if (empty($jsVariableName) && $isMultiDimensional) {
            $jsVariableName = 'autoGeneratedVariable';
        }

        if (!empty($jsVariableName) && is_string($jsVariableName)) {
            $result .= sprintf('var %s = ', $jsVariableName);
        }

        $result .= 'new Array(';

        if ($preserveIndexes || $isMultiDimensional) {
            $result .= $arrayCount;
            $result .= ');';
        }

        foreach ($array as $index => $value) {
            ++$counter;

            if (is_array($value)) {
                $variable = $index;

                if (is_int($index)) {
                    $variable = 'value_' . $variable;
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

                    $result .= $value . "\n";
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

        if (!$preserveIndexes && !$isMultiDimensional) {
            $result .= ');';
        }

        return $result;
    }

    /**
     * Quotes (adds quotes) to elements that are strings and returns new array (with quoted elements)
     *
     * @param array $array The array to check for string values
     * @return array|null
     */
    public static function quoteStrings(array $array)
    {
        /*
         * No elements?
         * Nothing to do
         */
        if (empty($array)) {
            return null;
        }

        $result = [];

        foreach ($array as $index => $value) {
            if (is_array($value)) {
                $value = self::quoteStrings($value);
            } elseif (is_string($value)) {
                if (!Regex::isQuoted($value)) {
                    $value = '\'' . $value . '\'';
                }
            }

            $result[$index] = $value;
        }

        return $result;
    }

    /**
     * Removes marginal element (first or last)
     *
     * @param string|array $item The item which should be shortened
     * @param bool         $last (optional) If is set to true, last element is removed. Otherwise - first.
     * @return string|array
     */
    public static function removeMarginalElement($item, $last = true)
    {
        if (is_string($item)) {
            if ($last) {
                $item = substr($item, 0, -1);
            } else {
                $item = substr($item, 1);
            }
        } elseif (is_array($item)) {
            $key = self::getFirstKey($item);

            if ($last) {
                $key = self::getLastKey($item);
            }

            unset($item[$key]);
        }

        return $item;
    }

    /**
     * Returns last key of array
     *
     * @param array $array The array to get the last key of
     * @return mixed
     */
    public static function getLastKey(array $array)
    {
        /*
         * No elements?
         * Nothing to do
         */
        if (empty($array)) {
            return null;
        }

        $keys = array_keys($array);

        return end($keys);
    }

    /**
     * Removes element / item of given array
     *
     * @param array $array The array that contains element / item which should be removed
     * @param mixed $item  The element / item which should be removed
     * @return bool|array
     */
    public static function removeElement(array $array, $item)
    {
        /*
         * No elements or the element does not exist?
         * Nothing to do
         */
        if (empty($array) || !in_array($item, $array, true)) {
            return false;
        }

        /*
         * Flip the array to make it looks like: value => key
         */
        $arrayFlipped = array_flip($array);

        /*
         * Take the key of element / item that should be removed
         */
        $key = $arrayFlipped[$item];

        /*
         * ...and remove the element / item
         */
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
    public static function removeElements(array &$array, $needle, $before = true)
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

                    if ($isArray && empty($value)) {
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

            if (!$before) {
                $array = array_reverse($array, true);
            }
        }
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
     * @return array|null
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
    public static function setKeysAsValues(array $array, $ignoreDuplicatedValues = true)
    {
        /*
         * No elements?
         * Nothing to do
         */
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

            /*
             * Duplicated values shouldn't be ignored and processed value is used as key already?
             * Let's use an array and that will contain all values (to avoid ignoring / overriding duplicated values)
             */
            if (!$ignoreDuplicatedValues && isset($replaced[$value])) {
                $existing = self::makeArray($replaced[$value]);

                $replaced[$value] = array_merge($existing, [
                    $key,
                ]);

                continue;
            }

            /*
             * Standard behaviour
             */
            $replaced[$value] = $key;
        }

        return $replaced;
    }

    /**
     * Applies ksort() function recursively in the given array
     *
     * @param array $array     The array to sort
     * @param int   $sortFlags (optional) Options of ksort() function
     * @return array|null
     */
    public static function ksortRecursive(array &$array, $sortFlags = SORT_REGULAR)
    {
        /*
         * No elements?
         * Nothing to do
         */
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
     * Returns count / amount of elements that are not array
     *
     * @param array $array The array to count
     * @return int|null
     */
    public static function getNonArrayElementsCount(array $array)
    {
        /*
         * No elements?
         * Nothing to do
         */
        if (empty($array)) {
            return null;
        }

        $count = 0;

        foreach ($array as &$value) {
            if (is_array($value)) {
                $count += self::getNonArrayElementsCount($value);
                continue;
            }

            ++$count;
        }

        return $count;
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
     * @return array
     */
    public static function string2array($string, $separator = '|', $valuesKeysSeparator = ':')
    {
        /*
         * Empty string?
         * Nothing to do
         */
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
     * Returns information if given keys exist in given array
     *
     * @param array $keys     The keys to find
     * @param array $array    The array that maybe contains keys
     * @param bool  $explicit (optional) If is set to true, all keys should exist in given array. Otherwise - not all.
     * @return bool
     */
    public static function areKeysInArray(array $keys, array $array, $explicit = true)
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
     * Returns paths of the last elements
     *
     * @param array        $array           The array with elements
     * @param string       $separator       (optional) Separator used between elements. Default: ".".
     * @param string       $parentPath      (optional) Path of the parent element. Default: "".
     * @param string|array $stopIfMatchedBy (optional) Patterns of keys or paths that matched will stop the process
     *                                      of path building and including children of those keys or paths (recursive
     *                                      will not be used for keys in lower level of given array). Default: "".
     * @return array|null
     *
     * Examples - $stopIfMatchedBy argument:
     * a) "\d+"
     * b) [
     *      "lorem\-",
     *      "\d+",
     * ];
     */
    public static function getLastElementsPaths(array $array, $separator = '.', $parentPath = '', $stopIfMatchedBy = '')
    {
        /*
         * No elements?
         * Nothing to do
         */
        if (empty($array)) {
            return null;
        }

        if (!empty($stopIfMatchedBy)) {
            $stopIfMatchedBy = self::makeArray($stopIfMatchedBy);
        }

        $paths = [];

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
             * - it's not an array
             * or
             * - the process is stopped, recursive is not used
             */
            if (!$valueIsArray || ($valueIsArray && empty($value)) || $stopRecursion) {
                $paths[$path] = $value;
                continue;
            }

            /*
             * Let's iterate through the next level, using recursive
             */
            if ($valueIsArray) {
                $recursivePaths = self::getLastElementsPaths($value, $separator, $path, $stopIfMatchedBy);
                $paths += $recursivePaths;
            }
        }

        return $paths;
    }

    /**
     * Makes and returns an array for given variable
     *
     * @param mixed $variable Variable that should be an array
     * @return array
     */
    public static function makeArray($variable)
    {
        if (is_array($variable)) {
            return $variable;
        }

        return [$variable];
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
    public static function areAllKeysMatchedByPattern(array $array, $pattern, $firstLevelOnly = false)
    {
        /*
         * No elements?
         * Nothing to do
         */
        if (empty($array)) {
            return false;
        }

        /*
         * I suppose that all are keys are matched
         * and then I have to look for keys that don't matches
         */
        $areMatched = true;

        /*
         * Building the pattern
         */
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
     * Returns information if keys / indexes of given array are integers, in other words if the array contains
     * zero-based keys / indexes
     *
     * @param array $array          The array to check
     * @param bool  $firstLevelOnly (optional) If is set to true, all keys / indexes are checked. Otherwise - from the
     *                              first level only (default behaviour).
     * @return bool
     */
    public static function areAllKeysIntegers(array $array, $firstLevelOnly = false)
    {
        $pattern = '\d+';

        return self::areAllKeysMatchedByPattern($array, $pattern, $firstLevelOnly);
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
        /*
         * No elements?
         * Nothing to do
         */
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
    public static function issetRecursive(array $array, array $keys)
    {
        /*
         * No elements?
         * Nothing to do
         */
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
     * Returns all values of given key.
     * It may be useful when you want to retrieve all values of one column.
     *
     * @param array  $array The array which should contain values of the key
     * @param string $key   The key
     * @return array|null
     */
    public static function getAllValuesOfKey(array $array, $key)
    {
        /*
         * No elements?
         * Nothing to do
         */
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
     * Sets positions for each element / child of given array and returns the array
     *
     * Position for the 1st element / child of a parent is set to 1 and incremented for the next element and
     * so on. Each parent is treated as separate array, so its elements are treated as positioned at 1st level.
     *
     * @param array  $array         The array which should has values of position for each element
     * @param string $keyName       (optional) Name of key which will contain the position value
     * @param int    $startPosition (optional) Default, start value of the position for main / given array, not the
     *                              children
     * @return array|null
     */
    public static function setPositions(array $array, $keyName = self::POSITION_KEY_NAME, $startPosition = null)
    {
        /*
         * No elements?
         * Nothing to do
         */
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
     * Trims string values of given array and returns the new array
     *
     * @param array $array The array which values should be trimmed
     * @return array
     */
    public static function trimRecursive(array $array)
    {
        /*
         * No elements?
         * Nothing to do
         */
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
     * @return array|null
     */
    public static function sortByCustomKeysOrder(array $array, array $keysOrder)
    {
        /*
         * No elements?
         * Nothing to do
         */
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
     * Returns smartly imploded string
     *
     * Separators located at the beginning or end of elements are removed.
     * It's required to avoid problems with duplicated separator, e.g. "first//second/third", where separator is a
     * "/" string.
     *
     * @param array  $array     The array with elements to implode
     * @param string $separator Separator used to stick together elements of given array
     * @return string|null
     */
    public static function implodeSmart(array $array, $separator)
    {
        /*
         * No elements?
         * Nothing to do
         */
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
     * Returns information if given array is empty, iow. information if all elements of given array are empty
     *
     * @param array $array      The array to verify
     * @param bool  $strictNull (optional) If is set to true elements are verified if they are null. Otherwise - only
     *                          if they are empty (e.g. null, '', 0, array()).
     * @return bool
     */
    public static function areAllValuesEmpty(array $array, $strictNull = false)
    {
        /*
         * No elements?
         * Nothing to do
         */
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
    public static function arrayDiffRecursive(array $array1, array $array2, $valuesOnly = false)
    {
        $effect = [];

        /*
         * Values should be compared only and both arrays are one-dimensional?
         * Let's find difference by using simple function
         */
        if ($valuesOnly && 1 === self::getDimensionsCount($array1) && 1 === self::getDimensionsCount($array2)) {
            return array_diff($array1, $array2);
        }

        foreach ($array1 as $key => $value) {
            $array2HasKey = array_key_exists($key, $array2);

            /*
             * Values should be compared only?
             */
            if ($valuesOnly) {
                $difference = null;

                if (is_array($value)) {
                    if ($array2HasKey && is_array($array2[$key])) {
                        $difference = self::arrayDiffRecursive($value, $array2[$key], $valuesOnly);
                    }
                } elseif (!$array2HasKey || ($array2HasKey && $value !== $array2[$key])) {
                    /*
                     * We are here, because:
                     * a) 2nd array hasn't key from 1st array
                     * OR
                     * b) key exists in both, 1st and 2nd array, but values are different
                     */
                    $difference = $value;
                }

                if (null !== $difference) {
                    $effect[] = $difference;
                }

                /*
                 * The key exists in 2nd array?
                 */
            } elseif ($array2HasKey) {
                /*
                 * The value it's an array (it's a nested array)?
                 */
                if (is_array($value)) {
                    $diff = [];

                    if (is_array($array2[$key])) {
                        /*
                         * Let's verify the nested array
                         */
                        $diff = self::arrayDiffRecursive($value, $array2[$key], $valuesOnly);
                    }

                    if (empty($diff)) {
                        continue;
                    }

                    $effect[$key] = $diff;

                    /*
                     * Value is different than in 2nd array?
                     * OKay, I've got difference
                     */
                } elseif ($value !== $array2[$key]) {
                    $effect[$key] = $value;
                }
            } else {
                /*
                 * OKay, I've got difference
                 */
                $effect[$key] = $value;
            }
        }

        return $effect;
    }

    /**
     * Returns an index / key of given element in given array
     *
     * @param array $array   The array to verify
     * @param mixed $element The element who index / key is needed
     * @return bool|null|mixed
     */
    public static function getIndexOf(array $array, $element)
    {
        /*
         * No elements?
         * Nothing to do
         */
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
     * Returns an array with incremented indexes / keys
     *
     * @param array    $array         The array which indexes / keys should be incremented
     * @param int|null $startIndex    (optional) Index from which incrementation should be started. If not provided,
     *                                the first index / key will be used.
     * @param int      $incrementStep (optional) Value used for incrementation. The step of incrementation.
     * @return array|null
     */
    public static function incrementIndexes(array $array, $startIndex = null, $incrementStep = 1)
    {
        /*
         * No elements?
         * Nothing to do
         */
        if (empty($array)) {
            return null;
        }

        $valuesToIncrement = [];

        /*
         * Start index not provided?
         * Let's look for the first index / key of given array
         */
        if (null === $startIndex) {
            $startIndex = self::getFirstKey($array);
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
                    $newIndex = $oldIndex + $incrementStep;
                    $array[$newIndex] = $value;
                }
            }
        }

        return $array;
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
     * Returns information if given array is a multi dimensional array
     *
     * @param array $array The array to verify
     * @return bool|null
     */
    public static function isMultiDimensional(array $array)
    {
        /*
         * No elements?
         * Nothing to do
         */
        if (empty($array)) {
            return null;
        }

        return count($array) !== count($array, COUNT_RECURSIVE);
    }

    /**
     * Returns count of dimensions, maximum nesting level actually, in given array
     *
     * @param array $array The array to verify
     * @return int
     */
    public static function getDimensionsCount(array $array)
    {
        /*
         * No elements?
         * Nothing to do
         */
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

    /**
     * Returns non-empty values, e.g. without "" (empty string), null or []
     *
     * @param array $values The values to filter
     * @return array|null
     */
    public static function getNonEmptyValues(array $values)
    {
        /*
         * No values?
         * Nothing to do
         */
        if (empty($values)) {
            return null;
        }

        return array_filter($values, function ($value) {
            $nonEmptyScalar = is_scalar($value) && '' !== $value;
            $nonEmptyArray = is_array($value) && !empty($value);

            return $nonEmptyScalar || $nonEmptyArray || is_object($value);
        });
    }

    /**
     * Returns non-empty values concatenated by given separator
     *
     * @param array  $values    The values to filter
     * @param string $separator (optional) Separator used to implode the values. Default: ", ".
     * @return string|null
     */
    public static function getNonEmptyValuesAsString(array $values, $separator = ', ')
    {
        /*
         * No elements?
         * Nothing to do
         */
        if (empty($values)) {
            return null;
        }

        $nonEmpty = self::getNonEmptyValues($values);

        /*
         * No values?
         * Nothing to do
         */
        if (empty($nonEmpty)) {
            return '';
        }

        return implode($separator, $nonEmpty);
    }

    /**
     * Returns neighbour (next or previous element) for given element
     *
     * @param array $array   The array with elements
     * @param mixed $element Element for who next element should be returned
     * @param bool  $next    (optional) If is set to true, returns next neighbour. Otherwise - previous.
     * @return mixed|null
     */
    private static function getNeighbour(array $array, $element, $next = true)
    {
        /*
         * No elements?
         * Nothing to do
         */
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
        if ($noPrevious || $noNext || empty($array) || !in_array($element, $array, true)) {
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

        /*
         * Looking for key of the neighbour (next or previous element)
         */
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
