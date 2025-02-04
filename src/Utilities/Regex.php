<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Utilities;

use Meritoo\Common\Exception\Regex\IncorrectColorHexLengthException;
use Meritoo\Common\Exception\Regex\InvalidColorHexValueException;
use Transliterator;

/**
 * Useful methods related to regular expressions
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class Regex
{
    /**
     * Patterns used to validate / verify values
     *
     * @var array
     */
    private static array $patterns = [
        'email' => '/^'
            .'[\w\-.]{2,}'          # username
            .'@'                    # the "@" character
            .'[\w\-]+\.[\w]{2,}+'   # domain
            .'$/',

        'phone' => '/^\+?[0-9 ]+$/',
        'camelCasePart' => '/([a-z]|[A-Z]){1}[a-z]*/',
        'urlProtocol' => '|^([a-z]+://)', // e.g. "http://"

        'urlDomain' => '([\da-z.-]+)'   # website name, e.g. "youtube", "quora",
            .'\.'                       # the "." character
            .'([a-z.]{2,6})'            # domain name, e.g. "com", "net"
            .'(/)?'                     # the "/" character after domain, e.g. "...com/"
            .'([\w.\-]*)?'              # page name, e.g. "...com/about-us"
            .'(\?)?'                    # optional "?" character, e.g. "...com/about-us?page=2"
            .'([\w .\-/=&]*)'           # optional parameters after "?" character
            .'/?'                       # optional "/" character at the end
            .'$|i',

        'letterOrDigit' => '/[a-zA-Z0-9]+/',
        'htmlEntity' => '/&[a-z0-9]+;/',

        'htmlAttribute' => '/'
            .'([\w-]+)'     # name
            .'='            # the "=" character
            .'"([\w -]+)"'  # value
            .'/',

        'fileName' => '/'
            .'[\w.\- +=!@$&()?]+'   # name
            .'\.'                   # the "." character
            .'\w+'                  # extension
            .'$/', // e.g. "this-1_2 3 & my! 4+file.jpg"

        'isQuoted' => '/^[\'"]{1}.+[\'"]{1}$/',
        'windowsBasedPath' => '/^[A-Z]{1}:\\\.*$/',
        'money' => '/^[-+]?\d+([.,]{1}\d*)?$/',
        'color' => '/^[[:xdigit:]]{6}$/', // "[:xdigit:]" is equivalent to "[a-fA-F0-9]"
        'bundleName' => '/^(([A-Z]{1}[a-z0-9]+)((?2))*)(Bundle)$/',
        'binaryValue' => '/[^\x20-\x7E\t\r\n]/',
        'beginningSlash' => '|^/|',
        'endingSlash' => '|/$|',

        /*
         * Matches:
         * - "200x125"
         * - "200 x 125"
         * - "200 x   125"
         * - "   200 x   125"
         * - "   200   x 125   "
         *
         * Contains "%s" that should be replaced with separator used to split width and height.
         */
        'size' => '/^[\ ]*(\d+)[\ ]*%s[\ ]*(\d+)[\ ]*$/',
    ];

    /**
     * Returns information if given html attributes are valid
     *
     * @param string $htmlAttributes The html attributes to verify
     * @return bool
     */
    public static function areValidHtmlAttributes(string $htmlAttributes): bool
    {
        $pattern = self::getHtmlAttributePattern();

        return (bool) preg_match_all($pattern, $htmlAttributes);
    }

    /**
     * Filters array by given expression and column
     *
     * Expression can be simple compare expression, like " == 2", or regular expression.
     * Returns filtered array.
     *
     * @param array $array The 2-dimensional array that should be filtered
     * @param string $arrayColumnKey Column name which value is verified by the filter
     * @param \Closure|string $filter A filter that might be regular expression (string) or anonymous function (closure)
     * @return array
     */
    public static function arrayFilter(
        array $array,
        string $arrayColumnKey,
        \Closure|string $filter,
    ): array {
        /*
         * No elements?
         * Nothing to do
         */
        if (empty($array)) {
            return [];
        }

        $effect = $array;

        foreach ($effect as $key => &$item) {
            if (!isset($item[$arrayColumnKey])) {
                continue;
            }

            $value = $item[$arrayColumnKey];
            $itsRegularExpression = \is_string($filter);

            if ($itsRegularExpression) {
                $matchesCount = preg_match($filter, $value);
                $remove = 0 === $matchesCount;
            } else {
                $remove = !$filter($value);
            }

            if ($remove) {
                unset($effect[$key]);
            }
        }

        return $effect;
    }

    /**
     * Returns string in human readable style generated from given camel case string / text
     *
     * @param string $string              The string / text to convert
     * @param bool   $applyUpperCaseFirst (optional) If is set to true, first word / element from the converted
     *                                    string is uppercased. Otherwise - not.
     * @return string
     */
    public static function camelCase2humanReadable(string $string, bool $applyUpperCaseFirst = false): string
    {
        $parts = self::getCamelCaseParts($string);

        if (!empty($parts)) {
            $elements = [];

            foreach ($parts as $part) {
                $elements[] = strtolower($part);
            }

            $string = implode(' ', $elements);

            if ($applyUpperCaseFirst) {
                $string = ucfirst($string);
            }
        }

        return $string;
    }

    /**
     * Returns simple, lowercase string generated from given camel case string / text
     *
     * @param string $string         The string / text to convert
     * @param string $separator      (optional) Separator used to concatenate parts of the string, e.g. '-' or '_'
     * @param bool   $applyLowercase (optional) If is set to true, returned string will be lowercased. Otherwise - not.
     * @return string
     */
    public static function camelCase2simpleLowercase(
        string $string,
        string $separator = '',
        bool $applyLowercase = true
    ): string {
        $parts = self::getCamelCaseParts($string);

        if (!empty($parts)) {
            $string = implode($separator, $parts);

            if ($applyLowercase) {
                $string = strtolower($string);
            }
        }

        return $string;
    }

    public static function clearBeginningSlash(string $string): string
    {
        $pattern = static::$patterns['beginningSlash'];

        return preg_replace($pattern, '', $string);
    }

    public static function clearEndingSlash(string $string): string
    {
        $pattern = static::$patterns['endingSlash'];

        return preg_replace($pattern, '', $string);
    }

    /**
     * Returns information if one string contains another string
     *
     * @param string $haystack The string to search in
     * @param string $needle   The string to be search for
     * @return bool
     */
    public static function contains(string $haystack, string $needle): bool
    {
        if (1 === strlen($needle) && !self::isLetterOrDigit($needle)) {
            $needle = sprintf('%s%s', '\\', $needle);
        }

        return (bool) preg_match('|.*'.$needle.'.*|', $haystack);
    }

    /**
     * Returns information if the string contains html entities
     *
     * @param string $string String to check
     * @return bool
     */
    public static function containsEntities(string $string): bool
    {
        $pattern = self::getHtmlEntityPattern();

        return (bool) preg_match_all($pattern, $string);
    }

    /**
     * Returns slug for given value
     *
     * @param string $value Value that should be transformed to slug
     * @return bool|string
     */
    public static function createSlug(string $value)
    {
        /*
         * It's an empty string?
         * Nothing to do
         */
        if ('' === $value) {
            return '';
        }

        $id = 'Latin-ASCII; NFD; [:Nonspacing Mark:] Remove; NFC; [:Punctuation:] Remove; Lower();';
        $transliterator = Transliterator::create($id);

        if ($transliterator === null) {
            return false;
        }

        $cleanValue = trim($value);
        $result = $transliterator->transliterate($cleanValue);

        return preg_replace('/[-\s]+/', '-', $result);
    }

    /**
     * Returns information if the string ends with given ending / characters
     *
     * @param string $string String to check
     * @param string $ending The ending of string, one or more characters
     * @return bool
     */
    public static function endsWith(string $string, string $ending): bool
    {
        if (1 === strlen($ending) && !self::isLetterOrDigit($ending)) {
            $ending = sprintf('%s%s', '\\', $ending);
        }

        return (bool) preg_match('|'.$ending.'$|', $string);
    }

    /**
     * Returns information if the string ends with directory's separator
     *
     * @param string $text      String that may contain a directory's separator at the end
     * @param string $separator (optional) The directory's separator, e.g. "/". If is empty (not provided), system's
     *                          separator is used.
     * @return bool
     */
    public static function endsWithDirectorySeparator(string $text, string $separator = ''): bool
    {
        if (empty($separator)) {
            $separator = DIRECTORY_SEPARATOR;
        }

        return self::endsWith($text, $separator);
    }

    /**
     * Returns array values that match given pattern (or values that keys match the pattern)
     *
     * @param string $pattern       Pattern to match
     * @param array  $array         The array (scalar values only)
     * @param bool   $itsKeyPattern (optional) If is set to true, keys will be checked if they match pattern.
     *                              Otherwise - values will be checked (default behaviour).
     * @return array
     */
    public static function getArrayValuesByPattern(string $pattern, array $array, bool $itsKeyPattern = false): array
    {
        /*
         * No elements?
         * Nothing to do
         */
        if (empty($array)) {
            return [];
        }

        if ($itsKeyPattern) {
            $effect = [];

            foreach ($array as $key => $value) {
                if (preg_match($pattern, $key)) {
                    $effect[$key] = $value;
                }
            }

            return $effect;
        }

        return preg_grep($pattern, $array);
    }

    /**
     * Returns pattern used to validate / verify name of bundle
     *
     * @return string
     */
    public static function getBundleNamePattern(): string
    {
        return self::$patterns['bundleName'];
    }

    /**
     * Returns pattern used to validate / verify or get camel case parts of string
     *
     * @return string
     */
    public static function getCamelCasePartPattern(): string
    {
        return self::$patterns['camelCasePart'];
    }

    /**
     * Returns parts of given camel case string / text
     *
     * @param string $string The string / text to retrieve parts
     * @return array
     */
    public static function getCamelCaseParts(string $string): array
    {
        $pattern = self::getCamelCasePartPattern();
        $matches = [];
        preg_match_all($pattern, $string, $matches);

        return $matches[0];
    }

    /**
     * Returns pattern used to validate / verify or get e-mail address
     *
     * @return string
     */
    public static function getEmailPattern(): string
    {
        return self::$patterns['email'];
    }

    /**
     * Returns pattern used to validate / verify name of file
     *
     * @return string
     */
    public static function getFileNamePattern(): string
    {
        return self::$patterns['fileName'];
    }

    /**
     * Returns pattern used to validate / verify html attribute
     *
     * @return string
     */
    public static function getHtmlAttributePattern(): string
    {
        return self::$patterns['htmlAttribute'];
    }

    /**
     * Returns pattern used to validate / verify html entity
     *
     * @return string
     */
    public static function getHtmlEntityPattern(): string
    {
        return self::$patterns['htmlEntity'];
    }

    /**
     * Returns pattern used to validate / verify if value is quoted (by apostrophes or quotation marks)
     *
     * @return string
     */
    public static function getIsQuotedPattern(): string
    {
        return self::$patterns['isQuoted'];
    }

    /**
     * Returns pattern used to validate / verify letter or digit
     *
     * @return string
     */
    public static function getLetterOrDigitPattern(): string
    {
        return self::$patterns['letterOrDigit'];
    }

    /**
     * Returns pattern used to validate / verify if given value is money-related value
     *
     * @return string
     */
    public static function getMoneyPattern(): string
    {
        return self::$patterns['money'];
    }

    /**
     * Returns pattern used to validate / verify or get phone number
     *
     * @return string
     */
    public static function getPhoneNumberPattern(): string
    {
        return self::$patterns['phone'];
    }

    /**
     * Returns pattern used to validate / verify size
     *
     * @param string $separator (optional) Separator used to split width and height. Default: " x ".
     * @return string
     */
    public static function getSizePattern(string $separator = ' x '): string
    {
        $escapeMe = [
            '/',
            '|',
            '.',
            '(',
            ')',
            '[',
            ']',
        ];

        $cleanSeparator = trim($separator);

        if (in_array($cleanSeparator, $escapeMe, true)) {
            // I have to escape special character of  regular expression that may be used as separator
            $separator = str_replace($cleanSeparator, '\\'.$cleanSeparator, $separator);
        }

        return sprintf(self::$patterns['size'], $separator);
    }

    /**
     * Returns pattern used to validate / verify or get url address
     *
     * @param bool $requireProtocol (optional) If is set to true, the protocol is required to be passed in the url.
     *                              Otherwise - not.
     * @return string
     */
    public static function getUrlPattern(bool $requireProtocol = false): string
    {
        $urlProtocol = self::$patterns['urlProtocol'];
        $urlDomain = self::$patterns['urlDomain'];
        $protocolPatternPart = '?';

        if ($requireProtocol) {
            $protocolPatternPart = '';
        }

        return sprintf('%s%s%s', $urlProtocol, $protocolPatternPart, $urlDomain);
    }

    /**
     * Returns valid given hexadecimal value of color.
     * If the value is invalid, throws an exception or returns false.
     *
     * @param string $color          Color to verify
     * @param bool   $throwException (optional) If is set to true, throws an exception if given color is invalid
     *                               (default behaviour). Otherwise - not.
     * @return bool|string
     * @throws InvalidColorHexValueException
     * @throws IncorrectColorHexLengthException
     */
    public static function getValidColorHexValue(string $color, bool $throwException = true)
    {
        $color = str_replace('#', '', $color);
        $length = strlen($color);

        // Color hasn't 3 or 6 characters length?
        if (3 !== $length && 6 !== $length) {
            if ($throwException) {
                throw new IncorrectColorHexLengthException($color);
            }

            return false;
        }

        // Make the color 6 characters length, if has 3
        if (3 === $length) {
            $color = preg_replace('/(.)(.)(.)/', '$1$1$2$2$3$3', $color);
        }

        $pattern = self::$patterns['color'];
        $match = (bool) preg_match($pattern, $color);

        // It's not a valid color
        if (!$match) {
            if ($throwException) {
                throw new InvalidColorHexValueException($color);
            }

            return false;
        }

        return strtolower($color);
    }

    /**
     * Returns pattern used to validate / verify if given path is a Windows-based path, e.g. "C:\path\to\file.jpg"
     *
     * @return string
     */
    public static function getWindowsBasedPathPattern(): string
    {
        return self::$patterns['windowsBasedPath'];
    }

    /**
     * Returns information if given value is a binary value
     *
     * @param string $value Value to verify
     * @return bool
     */
    public static function isBinaryValue(string $value): bool
    {
        $pattern = self::$patterns['binaryValue'];

        return (bool) preg_match($pattern, $value);
    }

    /**
     * Returns information if given name of file is a really name of file.
     * Verifies if given name contains a dot and an extension, e.g. "My File 001.jpg".
     *
     * @param string $fileName Name of file to check. It may be path of file also.
     * @return bool
     */
    public static function isFileName(string $fileName): bool
    {
        $pattern = self::getFileNamePattern();

        return (bool) preg_match($pattern, $fileName);
    }

    /**
     * Returns information if given character is a letter or digit
     *
     * @param string $char Character to check
     * @return bool
     */
    public static function isLetterOrDigit(string $char): bool
    {
        $pattern = self::getLetterOrDigitPattern();

        return (bool) preg_match($pattern, $char);
    }

    /**
     * Returns information if given value is quoted (by apostrophes or quotation marks)
     *
     * @param mixed $value The value to check
     * @return bool
     */
    public static function isQuoted($value): bool
    {
        $pattern = self::getIsQuotedPattern();

        return is_scalar($value) && preg_match($pattern, $value);
    }

    /**
     * Returns information if uri contains parameter
     *
     * @param string $uri           Uri string (e.g. $_SERVER['REQUEST_URI'])
     * @param string $parameterName Uri parameter name
     * @return bool
     */
    public static function isSetUriParameter(string $uri, string $parameterName): bool
    {
        return (bool) preg_match('|[?&]'.$parameterName.'=|', $uri); // e.g. ?name=phil&type=4 -> '$type='
    }

    /**
     * Returns information if given value is a size value
     *
     * @param string $value     Value to verify
     * @param string $separator (optional) Separator used to split width and height. Default: " x ".
     * @return bool
     */
    public static function isSizeValue(string $value, string $separator = ' x '): bool
    {
        $pattern = self::getSizePattern($separator);

        return (bool) preg_match($pattern, $value);
    }

    /**
     * Returns information if given path is sub-path of another path, e.g. path file is owned by path of directory
     *
     * @param string $subPath Path to verify, probably sub-path
     * @param string $path    Main / parent path
     * @return bool
     */
    public static function isSubPathOf(string $subPath, string $path): bool
    {
        /*
         * Empty path?
         * Nothing to do
         */
        if (empty($path) || empty($subPath)) {
            return false;
        }

        // Slash at the ending is optional
        if (self::endsWith($path, '/')) {
            $path .= '?';
        }

        $pattern = sprintf('|^%s.*|', $path);

        return (bool) preg_match($pattern, $subPath);
    }

    /**
     * Returns information if given name of bundle is valid
     *
     * @param string $bundleName Full name of bundle to verify, e.g. "MyExtraBundle"
     * @return bool
     */
    public static function isValidBundleName(string $bundleName): bool
    {
        $pattern = self::getBundleNamePattern();

        return (bool) preg_match($pattern, $bundleName);
    }

    /**
     * Returns information if given e-mail address is valid
     *
     * @param string $email E-mail address to validate / verify
     * @return bool
     *
     * Examples:
     * a) valid e-mails:
     * - ni@g-m.pl
     * - ni@gm.pl
     * - ni@g_m.pl
     * b) invalid e-mails:
     * - ni@g-m.p
     * - n@g-m.pl
     */
    public static function isValidEmail(string $email): bool
    {
        $pattern = self::getEmailPattern();

        return (bool) preg_match($pattern, $email);
    }

    /**
     * Returns information if given html attribute is valid
     *
     * @param string $htmlAttribute The html attribute to verify
     * @return bool
     */
    public static function isValidHtmlAttribute(string $htmlAttribute): bool
    {
        $pattern = self::getHtmlAttributePattern();

        return (bool) preg_match($pattern, $htmlAttribute);
    }

    /**
     * Returns information if given value is valid money-related value
     *
     * @param mixed $value Value to verify
     * @return bool
     */
    public static function isValidMoneyValue($value): bool
    {
        /*
         * Not a scalar value?
         * Nothing to do
         */
        if (!is_scalar($value)) {
            return false;
        }

        $pattern = self::getMoneyPattern();

        return (bool) preg_match($pattern, $value);
    }

    /**
     * Returns information if given NIP number is valid
     *
     * @param string $nip A given NIP number
     * @return bool
     *
     * @see https://pl.wikipedia.org/wiki/NIP#Znaczenie_numeru
     */
    public static function isValidNip(string $nip): bool
    {
        $nip = preg_replace('/\D/', '', $nip);

        $invalidNips = [
            '1234567890',
            '0000000000',
        ];

        if (!preg_match('/^\d{10}$/', $nip) || in_array($nip, $invalidNips, true)) {
            return false;
        }

        $sum = 0;
        $weights = [
            6,
            5,
            7,
            2,
            3,
            4,
            5,
            6,
            7,
        ];

        for ($i = 0; $i < 9; ++$i) {
            $sum += $weights[$i] * $nip[$i];
        }

        $modulo = $sum % 11;
        $numberControl = (10 === $modulo) ? 0 : $modulo;

        return $numberControl === (int) $nip[9];
    }

    /**
     * Returns information if given phone number is valid
     *
     * @param string $phoneNumber The phone number to validate / verify
     * @return bool
     */
    public static function isValidPhoneNumber(string $phoneNumber): bool
    {
        $pattern = self::getPhoneNumberPattern();

        return (bool) preg_match($pattern, trim($phoneNumber));
    }

    /**
     * Returns information if given tax ID is valid (in Poland it's named "NIP")
     *
     * @param string $taxIdString Tax ID (NIP) string
     * @return bool
     */
    public static function isValidTaxId(string $taxIdString): bool
    {
        /*
         * Empty/Unknown value?
         * Nothing to do
         */
        if (empty($taxIdString)) {
            return false;
        }

        $taxId = preg_replace('/[\s-]/', '', $taxIdString);

        /*
         * Tax ID is not 10 characters length OR is not numeric?
         * Nothing to do
         */
        if (!is_numeric($taxId) || 10 !== strlen($taxId)) {
            return false;
        }

        $weights = [
            6,
            5,
            7,
            2,
            3,
            4,
            5,
            6,
            7,
        ];

        $sum = 0;

        for ($x = 0; $x <= 8; ++$x) {
            $sum += $taxId[$x] * $weights[$x];
        }

        /*
         * Last number it's a remainder from dividing per 11?
         * Tax ID is valid
         */

        return $sum % 11 === (int) $taxId[9];
    }

    /**
     * Returns information if given url address is valid
     *
     * @param string $url             The url to validate / verify
     * @param bool   $requireProtocol (optional) If is set to true, the protocol is required to be passed in the url.
     *                                Otherwise - not.
     * @return bool
     */
    public static function isValidUrl(string $url, bool $requireProtocol = false): bool
    {
        $pattern = self::getUrlPattern($requireProtocol);

        return (bool) preg_match($pattern, $url);
    }

    /**
     * Returns information if given path is a Windows-based path, e.g. "C:\path\to\file.jpg"
     *
     * @param string $path The path to verify
     * @return bool
     */
    public static function isWindowsBasedPath(string $path): bool
    {
        $pattern = self::getWindowsBasedPathPattern();

        return (bool) preg_match($pattern, $path);
    }

    /**
     * Performs regular expression match with many given patterns.
     * Returns information if given $subject matches one or all given $patterns.
     *
     * @param array|string $patterns     The patterns to match
     * @param string       $subject      The string to check
     * @param bool         $mustAllMatch (optional) If is set to true, $subject must match all $patterns. Otherwise -
     *                                   not (default behaviour).
     * @return bool
     */
    public static function pregMultiMatch($patterns, string $subject, bool $mustAllMatch = false): bool
    {
        /*
         * No patterns?
         * Nothing to do
         */
        if (empty($patterns)) {
            return false;
        }

        $effect = false;
        $patterns = Arrays::makeArray($patterns);

        if ($mustAllMatch) {
            $effect = true;
        }

        foreach ($patterns as $pattern) {
            $matched = (bool) preg_match_all($pattern, $subject);

            if ($mustAllMatch) {
                $effect = $effect && $matched;
                continue;
            }

            if ($matched) {
                return true;
            }
        }

        return $effect;
    }

    /**
     * Returns information if the string starts with given beginning / characters
     *
     * @param string $string    String to check
     * @param string $beginning The beginning of string, one or more characters
     * @return bool
     */
    public static function startsWith(string $string, string $beginning): bool
    {
        if (!empty($string) && !empty($beginning)) {
            if (1 === strlen($beginning) && !self::isLetterOrDigit($beginning)) {
                $beginning = '\\'.$beginning;
            }

            $pattern = sprintf('|^%s|', $beginning);

            return (bool) preg_match($pattern, $string);
        }

        return false;
    }

    /**
     * Returns information if the string starts with directory's separator
     *
     * @param string $string    String that may contain a directory's separator at the start / beginning
     * @param string $separator (optional) The directory's separator, e.g. "/". If is empty (not provided), system's
     *                          separator is used.
     * @return bool
     */
    public static function startsWithDirectorySeparator(string $string, string $separator = ''): bool
    {
        if (empty($separator)) {
            $separator = DIRECTORY_SEPARATOR;
        }

        return self::startsWith($string, $separator);
    }
}
