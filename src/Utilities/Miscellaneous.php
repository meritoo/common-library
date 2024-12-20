<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Utilities;

use Gedmo\Sluggable\Util\Urlizer;
use Meritoo\Common\Exception\Regex\IncorrectColorHexLengthException;
use Meritoo\Common\Exception\Regex\InvalidColorHexValueException;
use Transliterator;

/**
 * Miscellaneous methods (only static functions)
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class Miscellaneous
{
    private const LONG_TEXT_SEPARATOR = '<br>';
    private const FILE_NAME_PATTERN = '/(.+)\.(.+)/';
    private const START_WITH_UPPERCASE_LETTER_PATTERN = '/[A-Z][^A-Z]*/';
    private const SPECIAL_CHARACTERS_PATTERN = '/[^a-zA-Z0-9]/';

    /**
     * Breaks long text
     *
     * @param string $text                   The text to check and break
     * @param int    $perLine                (optional) Characters count per line. Default: 100.
     * @param string $separator              (optional) Separator that is placed between lines. Default: "<br>".
     * @param string $encoding               (optional) Character encoding. Used by mb_substr(). Default: "UTF-8".
     * @param int    $proportionalAberration (optional) Proportional aberration for chars (percent value). Default: 20.
     * @return string
     */
    public static function breakLongText(
        string $text,
        int $perLine = 100,
        string $separator = self::LONG_TEXT_SEPARATOR,
        string $encoding = Locale::UTF8_ENCODING,
        int $proportionalAberration = 20
    ): string {
        $effect = $text;
        $textLength = mb_strlen($text);

        if (!empty($text) && $textLength > $perLine) {
            /*
             * The html_entity_decode() function is used here, because while operating
             * on string that contains only special characters the string is divided
             * incorrectly, e.g. "<<<<<" -> "&lt;&lt;&lt;&lt;&<br />lt;".
             */
            //$text = htmlspecialchars_decode($text);
            $text = html_entity_decode($text, ENT_QUOTES);

            $effect = '';
            $currentPosition = 0;

            $charsAberration = ceil($perLine * ($proportionalAberration / 100));
            $charsPerLineDefault = $perLine;

            while ($currentPosition <= $textLength) {
                $insertSeparator = false;

                /*
                 * Looking for spaces before and after current position. It was done, because text wasn't
                 * broken properly and some words were breaked and placed into two lines.
                 */
                if ($charsAberration > 0) {
                    $length = $perLine + $charsAberration;
                    $lineWithAberration = mb_substr($text, $currentPosition, $length, $encoding);

                    if (!Regex::contains($lineWithAberration, ' ')) {
                        $length = $perLine - $charsAberration;
                        $lineWithAberration = mb_substr($text, $currentPosition, $length, $encoding);
                    }

                    if (Regex::startsWith($lineWithAberration, ' ')) {
                        ++$currentPosition;
                        $lineWithAberration = ltrim($lineWithAberration);
                    }

                    $spacePosition = mb_strrpos($lineWithAberration, ' ', 0, $encoding);

                    if (false !== $spacePosition && 0 < $spacePosition) {
                        $perLine = $spacePosition;
                        $insertSeparator = true;
                    }
                }

                $charsOneLine = mb_substr($text, $currentPosition, $perLine, $encoding);

                /*
                 * The htmlspecialchars() function is used here, because...
                 * Reason and comment the same as above for html_entity_decode() function.
                 */

                $effect .= htmlspecialchars($charsOneLine);
                //$effect .= $charsOneLine;

                $currentPosition += $perLine;
                $oneLineContainsSpace = Regex::contains($charsOneLine, ' ');

                if (($insertSeparator || !$oneLineContainsSpace) && $currentPosition <= $textLength) {
                    $effect .= $separator;
                }

                $perLine = $charsPerLineDefault;
            }
        }

        return $effect;
    }

    public static function calculateGreatestCommonDivisor(int $first, int $second): int
    {
        if (0 === $second) {
            return $first;
        }

        return static::calculateGreatestCommonDivisor($second, $first % $second);
    }

    /**
     * Converts checkbox value to boolean
     *
     * @param string $checkboxValue Checkbox value
     * @return bool
     */
    public static function checkboxValue2Boolean(string $checkboxValue): bool
    {
        $mapping = [
            'on' => true,
            'off' => false,
        ];

        $clearValue = mb_strtolower(trim($checkboxValue));

        return $mapping[$clearValue] ?? false;
    }

    /**
     * Converts checkbox value to integer
     *
     * @param string $checkboxValue Checkbox value
     * @return int
     */
    public static function checkboxValue2Integer(string $checkboxValue): int
    {
        return (int) self::checkboxValue2Boolean($checkboxValue);
    }

    /**
     * Returns given paths concatenated into one string
     *
     * @param string ...$paths Paths to concatenate
     *
     * @return string
     */
    public static function concatenatePaths(string ...$paths): string
    {
        $concatenated = '';
        $firstWindowsBased = false;
        $separator = DIRECTORY_SEPARATOR;

        foreach ($paths as $path) {
            $path = trim($path);

            // Empty paths are useless
            if (empty($path)) {
                continue;
            }

            // Does the first path is a Windows-based path?
            if (Arrays::isFirstElement($paths, $path)) {
                $firstWindowsBased = Regex::isWindowsBasedPath($path);

                if ($firstWindowsBased) {
                    $separator = '\\';
                }
            }

            // Remove the starting / beginning directory's separator
            $path = self::removeStartingDirectorySeparator($path, $separator);

            // Removes the ending directory's separator
            $path = self::removeEndingDirectorySeparator($path, $separator);

            /*
             * If OS is Windows, first part of the concatenated path should be the first passed path,
             * because in Windows paths starts with drive letter, e.g. "C:", and the directory separator is not
             * necessary at the beginning.
             */
            if ($firstWindowsBased && empty($concatenated)) {
                $concatenated = $path;

                continue;
            }

            // Concatenate the paths / strings with OS-related directory separator between them (slash or backslash)
            $concatenated = sprintf('%s%s%s', $concatenated, $separator, $path);
        }

        return $concatenated;
    }

    /**
     * Adds missing the "0" characters to given number until given length is reached
     *
     * Example:
     * - number: 201
     * - length: 6
     * - will be returned: 000201
     *
     * If "before" parameter is false, zeros will be inserted after given number. If given number is longer than
     * given length the number will be returned as it was given to the method.
     *
     * @param mixed $number Number for whom the "0" characters should be inserted
     * @param int   $length Wanted length of final number
     * @param bool  $before (optional) If false, 0 characters will be inserted after given number
     * @return string
     */
    public static function fillMissingZeros($number, int $length, bool $before = true): string
    {
        /*
         * It's not a number? Empty string is not a number too.
         * Nothing to do
         */
        if (!is_numeric($number)) {
            return '';
        }

        $text = trim($number);
        $textLength = mb_strlen($text);

        if ($length <= $textLength) {
            return $text;
        }

        for ($i = ($length - $textLength); 0 < $i; --$i) {
            if ($before) {
                $text = '0'.$text;

                continue;
            }

            $text .= '0';
        }

        return $text;
    }

    /**
     * Returns the string in camel case
     *
     * @param string $string    The string to convert e.g. this-is-eXamplE (return: thisIsExample)
     * @param string $separator (optional) Separator used to find parts of the string, e.g. '-' or ','
     * @return string
     */
    public static function getCamelCase(string $string, string $separator = ' '): string
    {
        if (empty($string)) {
            return '';
        }

        $effect = '';
        $members = explode($separator, $string);

        foreach ($members as $key => $value) {
            $value = mb_strtolower($value);

            if (0 === $key) {
                $effect .= self::lowercaseFirst($value);
            } else {
                $effect .= self::uppercaseFirst($value);
            }
        }

        return $effect;
    }

    /**
     * Returns directory's content (names of directories and files)
     *
     * @param string   $directoryPath Path of directory who content should be returned
     * @param bool     $recursive     (optional) If is set to true, subdirectories are also searched for content.
     *                                Otherwise - only content of given directory is returned.
     * @param int|null $maxFilesCount (optional) Maximum files that will be returned. If it's null, all files are
     *                                returned.
     * @return null|array
     */
    public static function getDirectoryContent(
        string $directoryPath,
        bool $recursive = false,
        ?int $maxFilesCount = null
    ): ?array {
        /*
         * Path of directory is unknown or does not exist and is not readable?
         * Nothing to do
         */
        if (empty($directoryPath) || !is_readable($directoryPath)) {
            return null;
        }

        $files = [];
        $startFileName = '';

        if (self::isFilePath($directoryPath)) {
            $startDirectoryPath = dirname($directoryPath);
            $startFileName = str_replace($startDirectoryPath, '', $directoryPath);

            $directoryPath = $startDirectoryPath;
        }

        $count = 0;
        $startFileFound = false;

        if (!Regex::endsWith($directoryPath, '/')) {
            $directoryPath .= '/';
        }

        if (Regex::startsWith($startFileName, '/')) {
            $startFileName = mb_substr($startFileName, 1);
        }

        $directoryContent = scandir($directoryPath, SCANDIR_SORT_ASCENDING);

        if (!empty($directoryContent)) {
            foreach ($directoryContent as $fileName) {
                if ('.' !== $fileName && '..' !== $fileName) {
                    $content = null;

                    if (!empty($startFileName) && !$startFileFound) {
                        if ($fileName === $startFileName) {
                            $startFileFound = true;
                        }

                        continue;
                    }

                    if ($recursive && is_dir($directoryPath.$fileName)) {
                        $content = self::getDirectoryContent($directoryPath.$fileName, true, $maxFilesCount - $count);
                    }

                    if (null !== $content) {
                        $files[$fileName] = $content;

                        if (null !== $maxFilesCount) {
                            $count += Arrays::getNonArrayElementsCount($content);
                        }
                    } else {
                        $files[] = $fileName;

                        if (null !== $maxFilesCount) {
                            ++$count;
                        }
                    }

                    if (null !== $maxFilesCount && $count >= $maxFilesCount) {
                        break;
                    }
                }
            }
        }

        return $files;
    }

    /**
     * Returns file extension
     *
     * @param string $fileName    File name
     * @param bool   $asLowerCase (optional) if true extension is returned as lowercase string
     * @return string
     */
    public static function getFileExtension(string $fileName, bool $asLowerCase = false): string
    {
        $extension = '';
        $matches = [];

        if (preg_match(self::FILE_NAME_PATTERN, $fileName, $matches)) {
            $extension = end($matches);
        }

        if ($asLowerCase) {
            return strtolower($extension);
        }

        return $extension;
    }

    /**
     * Returns file name from given path
     *
     * @param string $path A path that contains file name
     * @return string
     */
    public static function getFileNameFromPath(string $path): string
    {
        $matches = [];
        $pattern = Regex::getFileNamePattern();

        if (preg_match($pattern, $path, $matches)) {
            return $matches[0];
        }

        return '';
    }

    /**
     * Returns file name without extension
     *
     * @param string $fileName The file name
     * @return string
     */
    public static function getFileNameWithoutExtension(string $fileName): string
    {
        $matches = [];

        if (preg_match(self::FILE_NAME_PATTERN, $fileName, $matches)) {
            return $matches[1];
        }

        return '';
    }

    /**
     * Returns size (of file or directory) in human-readable format
     *
     * @param int $sizeInBytes The size in bytes
     * @return string
     */
    public static function getHumanReadableSize(int $sizeInBytes): string
    {
        $units = [
            'B',
            'KB',
            'MB',
            'GB',
            'TB',
            'PB',
        ];

        $index = floor(log($sizeInBytes, 1024));
        $size = round($sizeInBytes / (1024 ** $index), 2);
        $unit = $units[(int) $index];

        return sprintf('%s %s', $size, $unit);
    }

    /**
     * Returns inverted value of color for given color
     *
     * @param string $color Hexadecimal value of color to invert (with or without hash), e.g. "dd244c" or "#22a5fe"
     * @return string
     *
     * @throws IncorrectColorHexLengthException
     * @throws InvalidColorHexValueException
     */
    public static function getInvertedColor(string $color): string
    {
        // Prepare the color for later usage
        $color = trim($color);
        $withHash = Regex::startsWith($color, '#');

        /*
         * Verify and get valid value of color.
         * An exception will be thrown if the value is not a color.
         */
        $validColor = Regex::getValidColorHexValue($color);

        // Grab color's components
        $red = hexdec(substr($validColor, 0, 2));
        $green = hexdec(substr($validColor, 2, 2));
        $blue = hexdec(substr($validColor, 4, 2));

        // Calculate inverted color's components
        $redInverted = self::getValidColorComponent(255 - $red);
        $greenInverted = self::getValidColorComponent(255 - $green);
        $blueInverted = self::getValidColorComponent(255 - $blue);

        // Voila, here is the inverted color
        $invertedColor = sprintf('%s%s%s', $redInverted, $greenInverted, $blueInverted);

        if ($withHash) {
            return sprintf('#%s', $invertedColor);
        }

        return $invertedColor;
    }

    /**
     * Returns the last element of given string divided by given separator
     *
     * @param string $string    The string to check
     * @param string $separator The separator which divides elements of string
     * @return null|string
     */
    public static function getLastElementOfString(string $string, string $separator): ?string
    {
        $elements = self::getStringElements($string, $separator);

        if (empty($elements)) {
            return null;
        }

        return Arrays::getLastElement($elements);
    }

    /**
     * Returns new file name after adding prefix or suffix (or both of them) to the name
     *
     * @param string $fileName The file name
     * @param string $prefix   File name prefix
     * @param string $suffix   File name suffix
     * @return string
     */
    public static function getNewFileName(string $fileName, string $prefix, string $suffix): string
    {
        $effect = $fileName;

        if (!empty($fileName) && (!empty($prefix) || !empty($suffix))) {
            $name = self::getFileNameWithoutExtension($fileName);
            $extension = self::getFileExtension($fileName);

            $effect = sprintf('%s%s%s.%s', $prefix, $name, $suffix, $extension);
        }

        return $effect;
    }

    /**
     * Returns operating system name PHP is running on
     *
     * @return string
     */
    public static function getOperatingSystemNameServer(): string
    {
        return PHP_OS;
        /*
         * Previous version:
         * return php_uname('s');
         */
    }

    /**
     * Returns project's root path.
     * Looks for directory that contains composer.json.
     *
     * @return string
     */
    public static function getProjectRootPath(): string
    {
        $projectRootPath = '';

        $fileName = 'composer.json';
        $directoryPath = __DIR__;

        // Path of directory it's not the path of last directory?
        while (DIRECTORY_SEPARATOR !== $directoryPath) {
            $filePath = static::concatenatePaths($directoryPath, $fileName);

            /*
             * Is here file we are looking for?
             * Maybe it's a project's root path
             */
            if (file_exists($filePath)) {
                $projectRootPath = $directoryPath;
            }

            $directoryPath = dirname($directoryPath);
        }

        return $projectRootPath;
    }

    /**
     * Returns safely value of global variable, found in one of the global arrays / variables, e.g. $_GET
     *
     * @param int    $globalSourceType Represents the global array / variable. One of constants: INPUT_GET, INPUT_POST,
     *                                 INPUT_COOKIE, INPUT_SERVER, or INPUT_ENV.
     * @param string $variableName     Name of the variable to return value
     * @return mixed
     */
    public static function getSafelyGlobalVariable(int $globalSourceType, string $variableName)
    {
        $value = filter_input($globalSourceType, $variableName);

        if (null === $value) {
            $globalSource = null;

            switch ($globalSourceType) {
                case INPUT_GET:
                    $globalSource = $_GET;

                    break;
                case INPUT_POST:
                    $globalSource = $_POST;

                    break;
                case INPUT_COOKIE:
                    $globalSource = $_COOKIE;

                    break;
                case INPUT_SERVER:
                    $globalSource = $_SERVER;

                    break;
                case INPUT_ENV:
                    $globalSource = $_ENV;

                    break;
            }

            if (null !== $globalSource && isset($globalSource[$variableName])) {
                $value = $globalSource[$variableName];
            }
        }

        return $value;
    }

    /**
     * Returns elements of given string divided by given separator
     *
     * @param string $string    The string to check
     * @param string $separator The separator which divides elements of string
     * @return array
     */
    public static function getStringElements(string $string, string $separator): array
    {
        if (empty($string) || empty($separator)) {
            return [];
        }

        $matches = [];
        $pattern = sprintf('|[^\%s]+|', $separator);
        $matchCount = preg_match_all($pattern, $string, $matches);

        if ($matchCount > 1) {
            return $matches[0];
        }

        return [];
    }

    /**
     * Returns string without the last element.
     * The string should contain given separator.
     *
     * @param string $string    The string to check
     * @param string $separator The separator which divides elements of string
     * @return string
     */
    public static function getStringWithoutLastElement(string $string, string $separator): string
    {
        $elements = self::getStringElements($string, $separator);
        $lastKey = Arrays::getLastKey($elements);

        unset($elements[$lastKey]);

        return implode($separator, $elements);
    }

    /**
     * Returns type of given variable.
     * If it's an object, full class name is returned.
     *
     * @param mixed $variable Variable who type should be returned
     * @return string
     */
    public static function getType($variable): string
    {
        if (is_object($variable)) {
            return Reflection::getClassName($variable);
        }

        return gettype($variable);
    }

    /**
     * Returns unique name for file based on given original name
     *
     * @param string   $originalFileName Original name of the file
     * @param int|null $objectId         (optional) Object ID, the ID of database's row. May be included into the
     *                                   generated / unique name.
     * @return string
     */
    public static function getUniqueFileName(string $originalFileName, ?int $objectId = null): string
    {
        $withoutExtension = self::getFileNameWithoutExtension($originalFileName);
        $extension = self::getFileExtension($originalFileName, true);

        /*
         * Let's clear name of file
         *
         * Attention.
         * The name without extension should be cleared to avoid incorrect name by replacing "." with "-".
         */
        $withoutExtension = Urlizer::urlize($withoutExtension);

        // Now I have to complete the template used to build / generate unique name
        $template = '%s-%s.%s'; // [file's name]-[unique key].[file's extension]

        // Add some uniqueness
        $unique = self::getUniqueString(mt_rand());

        // Finally build and return the unique name
        if ($objectId !== null) {
            $template = '%s-%s-%s.%s'; // [file's name]-[unique key]-[object ID].[file's extension]

            return sprintf($template, $withoutExtension, $unique, $objectId, $extension);
        }

        return sprintf($template, $withoutExtension, $unique, $extension);
    }

    /**
     * Returns unique string
     *
     * @param string $prefix (optional) Prefix of the unique string. May be used while generating the unique
     *                       string simultaneously on several hosts at the same microsecond.
     * @param bool   $hashed (optional) If is set to true, the unique string is hashed additionally. Otherwise - not.
     * @return string
     */
    public static function getUniqueString(string $prefix = '', bool $hashed = false): string
    {
        $unique = uniqid($prefix, true);

        if ($hashed) {
            return sha1($unique);
        }

        return $unique;
    }

    /**
     * Returns valid value of color's component (e.g. red).
     * If given value is greater than 0, returns the value. Otherwise - 0.
     *
     * @param int  $colorComponent Color's component to verify. Decimal value, e.g. 255.
     * @param bool $asHexadecimal  (optional) If is set to true, hexadecimal value is returned (default behaviour).
     *                             Otherwise - decimal.
     * @return int|string
     */
    public static function getValidColorComponent(int $colorComponent, bool $asHexadecimal = true)
    {
        if ($colorComponent < 0 || $colorComponent > 255) {
            $colorComponent = 0;
        }

        if ($asHexadecimal) {
            $hexadecimal = dechex($colorComponent);

            if (1 === strlen($hexadecimal)) {
                return sprintf('0%s', $hexadecimal);
            }

            return $hexadecimal;
        }

        return $colorComponent;
    }

    /**
     * Returns name of file with given extension after verification if it contains the extension
     *
     * @param string $fileName  The file name to verify
     * @param string $extension The extension to verify and include
     * @return string
     */
    public static function includeFileExtension(string $fileName, string $extension): string
    {
        $fileExtension = self::getFileExtension($fileName, true);

        /*
         * File has given extension?
         * Nothing to do
         */
        if ($fileExtension === strtolower($extension)) {
            return $fileName;
        }

        return sprintf('%s.%s', $fileName, $extension);
    }

    /**
     * Returns information if given value is located in interval between given utmost left and right values
     *
     * @param float|int $value Value to verify
     * @param float|int $left  Left utmost value of interval
     * @param float|int $right Right utmost value of interval
     * @return bool
     */
    public static function isBetween($value, $left, $right): bool
    {
        return $value > $left && $value < $right;
    }

    /**
     * Returns information if value is decimal
     *
     * @param mixed $value The value to check
     * @return bool
     */
    public static function isDecimal($value): bool
    {
        return is_scalar($value) && is_numeric($value) && floor($value) !== (float) $value;
    }

    /**
     * Returns information if given path it's a file's path, if the path contains file name
     *
     * @param string $path The path to check
     * @return bool
     */
    public static function isFilePath(string $path): bool
    {
        $info = pathinfo($path);

        return isset($info['extension']) && !empty($info['extension']);
    }

    /**
     * Returns information if given PHP module is compiled and loaded
     *
     * @param string $phpModuleName PHP module name
     * @return bool
     */
    public static function isPhpModuleLoaded(string $phpModuleName): bool
    {
        $phpModulesArray = get_loaded_extensions();

        return in_array($phpModuleName, $phpModulesArray);
    }

    /**
     * Make a string's first character lowercase
     *
     * @param string    $text          The text to get first character lowercase
     * @param bool|null $restLowercase (optional) Information that to do with rest of given string
     * @return string
     *
     * Values of the $restLowercase argument:
     * - null (default): nothing is done with the string
     * - true: the rest of string is lowercased
     * - false: the rest of string is uppercased
     */
    public static function lowercaseFirst(string $text, ?bool $restLowercase = null): string
    {
        if (empty($text)) {
            return '';
        }

        $effect = $text;

        if ($restLowercase) {
            $effect = mb_strtolower($effect);
        } elseif (false === $restLowercase) {
            $effect = mb_strtoupper($effect);
        }

        return lcfirst($effect);
    }

    /**
     * Quotes given value with apostrophes or quotation marks
     *
     * @param mixed $value         The value to quote
     * @param bool  $useApostrophe (optional) If is set to true, apostrophes are used. Otherwise - quotation marks.
     * @return string
     */
    public static function quoteValue($value, bool $useApostrophe = true): string
    {
        if (is_string($value)) {
            $quotes = '"';

            if ($useApostrophe) {
                $quotes = '\'';
            }

            $value = sprintf('%s%s%s', $quotes, $value, $quotes);
        }

        return $value;
    }

    /**
     * Removes the directory.
     * If not empty, removes also contents.
     *
     * @param string $directoryPath Directory path
     * @param bool   $contentOnly   (optional) If is set to true, only content of the directory is removed, not
     *                              directory itself. Otherwise - directory is removed too (default behaviour).
     * @return null|bool
     */
    public static function removeDirectory(string $directoryPath, bool $contentOnly = false): ?bool
    {
        /*
         * Directory does not exist?
         * Nothing to do
         */
        if (!file_exists($directoryPath)) {
            return null;
        }

        /*
         * It's not a directory?
         * Let's treat it like file
         */
        if (!is_dir($directoryPath)) {
            return unlink($directoryPath);
        }

        foreach (scandir($directoryPath, SCANDIR_SORT_ASCENDING) as $item) {
            if ('.' === $item || '..' === $item) {
                continue;
            }

            if (!self::removeDirectory($directoryPath.DIRECTORY_SEPARATOR.$item)) {
                return false;
            }
        }

        // Directory should be removed too?
        if (!$contentOnly) {
            return rmdir($directoryPath);
        }

        return true;
    }

    /**
     * Removes the ending directory's separator
     *
     * @param string $text      Text that may contain a directory's separator at the end
     * @param string $separator (optional) The directory's separator, e.g. "/". If is empty (not provided), system's
     *                          separator is used.
     * @return string
     */
    public static function removeEndingDirectorySeparator(string $text, string $separator = ''): string
    {
        if (empty($separator)) {
            $separator = DIRECTORY_SEPARATOR;
        }

        $effect = trim($text);

        if (Regex::endsWithDirectorySeparator($effect, $separator)) {
            $effect = mb_substr($effect, 0, mb_strlen($effect) - mb_strlen($separator));
        }

        return $effect;
    }

    /**
     * Removes marginal character (first or last) from given string
     *
     * @param string $string The string which should be shortened
     * @param bool   $last   (optional) If is set to true, last element is removed (default behaviour). Otherwise -
     *                       first.
     * @return null|string
     */
    public static function removeMarginalCharacter(string $string, bool $last = true): ?string
    {
        if (empty($string)) {
            return null;
        }

        if ($last) {
            return substr($string, 0, -1);
        }

        return substr($string, 1);
    }

    /**
     * Removes the starting / beginning directory's separator
     *
     * @param string $text      Text that may contain a directory's separator at the start / beginning
     * @param string $separator (optional) The directory's separator, e.g. "/". If is empty (not provided), separator
     *                          provided by operating system will be used.
     * @return string
     */
    public static function removeStartingDirectorySeparator(string $text, string $separator = ''): string
    {
        if (empty($separator)) {
            $separator = DIRECTORY_SEPARATOR;
        }

        $effect = trim($text);

        if (Regex::startsWithDirectorySeparator($effect, $separator)) {
            $effect = mb_substr($effect, mb_strlen($separator));
        }

        return $effect;
    }

    /**
     * Returns part of string preserving words
     *
     * @param string $text      The string / text
     * @param int    $maxLength Maximum length of given string
     * @param string $suffix    (optional) The suffix to add at the end of string
     * @return string
     */
    public static function substringToWord(string $text, int $maxLength, string $suffix = '...'): string
    {
        $effect = $text;

        $textLength = mb_strlen($text, Locale::UTF8_ENCODING);
        $suffixLength = mb_strlen($suffix, Locale::UTF8_ENCODING);

        $maxLength -= $suffixLength;

        if ($textLength > $maxLength) {
            $effect = mb_substr($text, 0, $maxLength, Locale::UTF8_ENCODING);
            $lastSpacePosition = mb_strrpos($effect, ' ', 0, Locale::UTF8_ENCODING);

            if (false !== $lastSpacePosition) {
                $effect = mb_substr($effect, 0, $lastSpacePosition, Locale::UTF8_ENCODING);
            }

            $effect .= $suffix;
        }

        return $effect;
    }

    /**
     * Converts given string characters to latin characters
     *
     * @param string $string          String to convert
     * @param bool   $lowerCaseHuman  (optional) If is set to true, converted string is returned as lowercase and
     *                                human-readable. Otherwise - as original.
     * @param string $replacementChar (optional) Replacement character for all non-latin characters and uppercase
     *                                letters, if 2nd argument is set to true
     * @return string
     */
    public static function toLatin(string $string, bool $lowerCaseHuman = true, string $replacementChar = '-'): string
    {
        $string = trim($string);

        /*
         * Empty value?
         * Nothing to do
         */
        if (empty($string)) {
            return '';
        }

        $converter = Transliterator::create('Latin-ASCII;');

        /*
         * Oops, cannot instantiate converter
         * Nothing to do
         */
        if (null === $converter) {
            return '';
        }

        $converted = $converter->transliterate($string);

        // Make the string lowercase and human-readable
        if ($lowerCaseHuman) {
            $matches = [];
            $matchCount = preg_match_all(self::START_WITH_UPPERCASE_LETTER_PATTERN, $converted, $matches);

            if ($matchCount > 0) {
                $parts = $matches[0];
                $converted = mb_strtolower(implode($replacementChar, $parts));
            }
        }

        /*
         * Let's replace special characters to spaces
         * ...and finally spaces to $replacementChar
         */
        $replaced = preg_replace(self::SPECIAL_CHARACTERS_PATTERN, ' ', $converted);

        return preg_replace('| +|', $replacementChar, trim($replaced));
    }

    /**
     * Returns smartly trimmed string.
     * If the string is empty, contains only spaces, e.g. " ", nothing is done and the original string is returned.
     *
     * @param string $string The string to trim
     * @return string
     */
    public static function trimSmart(string $string): string
    {
        $trimmed = trim($string);

        if (empty($trimmed)) {
            return $string;
        }

        return $trimmed;
    }

    /**
     * Make a string's first character uppercase
     *
     * @param string    $text          The text to get uppercase
     * @param bool|null $restLowercase (optional) Information that to do with rest of given string
     * @return string
     *
     * Values of the $restLowercase argument:
     * - null (default): nothing is done with the string
     * - true: the rest of string is lowercased
     * - false: the rest of string is uppercased
     */
    public static function uppercaseFirst(string $text, ?bool $restLowercase = null): string
    {
        if (empty($text)) {
            return '';
        }

        $effect = $text;

        if ($restLowercase) {
            $effect = mb_strtolower($effect);
        } elseif (false === $restLowercase) {
            $effect = mb_strtoupper($effect);
        }

        return ucfirst($effect);
    }

    /**
     * Converts value to non-negative integer (element of the set {0, 1, 2, 3, ...})
     *
     * @param mixed $value               Value to convert
     * @param int   $negativeReplacement (optional) Replacement for negative value
     * @return int
     */
    public static function value2NonNegativeInteger($value, int $negativeReplacement = 0): int
    {
        $effect = (int) $value;

        if ($effect < 0) {
            return $negativeReplacement;
        }

        return $effect;
    }
}
