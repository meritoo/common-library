<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Utilities;

/**
 * Useful methods related to uri
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class Uri
{
    private const PROTOCOL_NAME_AND_VERSION_PATTERN = '|(.+)/(.+)|';
    private const HTTP_PROTOCOL = 'http';

    /**
     * Adds protocol to given url, if the url does not contain given protocol.
     * Returns the new url.
     *
     * @param string $url      Url string
     * @param string $protocol (optional) Protocol string
     * @return string
     */
    public static function addProtocolToUrl(string $url, string $protocol = self::HTTP_PROTOCOL): string
    {
        $pattern = sprintf('/^%s.*/', $protocol);

        if (preg_match($pattern, $url)) {
            return $url;
        }

        return sprintf('%s://%s', $protocol, $url);
    }

    public static function buildUrl(string $rootUrl, string ...$urlParts): string
    {
        $rootUrl = Regex::clearEndingSlash($rootUrl);

        if (empty($urlParts) || Arrays::containsEmptyStringsOnly($urlParts)) {
            return $rootUrl;
        }

        array_walk($urlParts, static function (&$part) {
            $part = Regex::clearBeginningSlash($part);
            $part = Regex::clearEndingSlash($part);
        });

        return sprintf(
            '%s/%s',
            $rootUrl,
            implode('/', $urlParts)
        );
    }

    /**
     * Returns full uri string
     *
     * @param bool $withoutHost (optional) If is set to true, means that host / server name is omitted
     * @return string
     */
    public static function getFullUri(bool $withoutHost = false): string
    {
        $requestedUrl = Miscellaneous::getSafelyGlobalVariable(INPUT_SERVER, 'REQUEST_URI');

        /*
         * Unknown requested url?
         * Nothing to do
         */
        if (empty($requestedUrl)) {
            return '';
        }

        /*
         * Without host / server name?
         * All is done
         */
        if ($withoutHost) {
            return $requestedUrl;
        }

        return self::getServerNameOrIp(true).$requestedUrl;
    }

    /**
     * Returns protocol name
     *
     * @return string
     */
    public static function getProtocolName(): string
    {
        $matches = [];
        $protocolData = Miscellaneous::getSafelyGlobalVariable(INPUT_SERVER, 'SERVER_PROTOCOL'); // e.g. HTTP/1.1
        $matchCount = preg_match(self::PROTOCOL_NAME_AND_VERSION_PATTERN, $protocolData, $matches);

        /*
         * $matches[1] - protocol name, e.g. HTTP
         * $matches[2] - protocol version, e.g. 1.1
         */

        // Oops, cannot match protocol
        if (0 === $matchCount) {
            return '';
        }

        return strtolower($matches[1]);
    }

    /**
     * Returns http referer uri
     *
     * @return string
     */
    public static function getRefererUri(): string
    {
        return Miscellaneous::getSafelyGlobalVariable(INPUT_SERVER, 'HTTP_REFERER');
    }

    /**
     * Returns url to resource secured by given htpasswd login and password
     *
     * @param string $url      A path / url to some resource, e.g. page, image, css file
     * @param string $user     (optional) User name used to log in
     * @param string $password (optional) User password used to log in
     * @return string
     */
    public static function getSecuredUrl(string $url, string $user = '', string $password = ''): string
    {
        /*
         * Url is not provided?
         * Nothing to do
         */
        if (empty($url)) {
            return '';
        }

        $protocol = self::getProtocolName();
        $host = self::getServerNameOrIp();

        if (!Regex::startsWith($url, '/')) {
            $url = sprintf('/%s', $url);
        }

        $url = $host.$url;

        if (!empty($user) && !empty($password)) {
            $url = sprintf('%s:%s@%s', $user, $password, $url);
        }

        return sprintf('%s://%s', $protocol, $url);
    }

    /**
     * Returns server name or IP address
     *
     * @param bool $withProtocol (optional) If is set to true, protocol name is included. Otherwise isn't.
     * @return string
     */
    public static function getServerNameOrIp(bool $withProtocol = false): string
    {
        $host = Miscellaneous::getSafelyGlobalVariable(INPUT_SERVER, 'HTTP_HOST');

        /*
         * Unknown host / server?
         * Nothing to do
         */
        if (empty($host)) {
            return '';
        }

        /*
         * With protocol?
         * Let's include the protocol
         */
        if ($withProtocol) {
            return sprintf('%s://%s', self::getProtocolName(), $host);
        }

        return $host;
    }

    /**
     * Returns user's IP address
     *
     * @return string
     */
    public static function getUserAddressIp(): string
    {
        return Miscellaneous::getSafelyGlobalVariable(INPUT_SERVER, 'REMOTE_ADDR');
    }

    /**
     * Returns name of user's operating system
     *
     * @return string
     */
    public static function getUserOperatingSystemName(): string
    {
        $info = self::getUserWebBrowserInfo();

        $knownSystems = [
            'Linux' => 'Linux',
            'Win' => 'Windows',
            'Mac' => 'Mac OS',
        ];

        foreach ($knownSystems as $pattern => $systemName) {
            $matchCount = preg_match(sprintf('|%s|', $pattern), $info);

            if ($matchCount > 0) {
                return $systemName;
            }
        }

        return '';
    }

    /**
     * Returns user's web browser information
     *
     * @return string
     *
     * Examples:
     * - Mozilla Firefox:
     * 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.10; rv:34.0) Gecko/20100101 Firefox/34.0'
     *
     * - Google Chrome:
     * 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95
     * Safari/537.36'
     *
     * - Opera:
     * 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.65
     * Safari/537.36 OPR/26.0.1656.24'
     *
     * - Apple Safari:
     * 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/600.2.5 (KHTML, like Gecko) Version/8.0.2
     * Safari/600.2.5'
     */
    public static function getUserWebBrowserInfo(): string
    {
        return Miscellaneous::getSafelyGlobalVariable(INPUT_SERVER, 'HTTP_USER_AGENT');
    }

    /**
     * Returns name and version of user's web browser
     *
     * @param bool $withVersion (optional) If is set to true, version of the browser is returned too. Otherwise -
     *                          name only.
     * @return string
     */
    public static function getUserWebBrowserName(bool $withVersion = false): string
    {
        $info = self::getUserWebBrowserInfo();

        $knownBrowsers = [
            'Firefox/([\d\.]+)$' => 'Mozilla Firefox',
            'OPR/([\d\.]+)$' => 'Opera',
            'Chrome/([\d\.]+)$' => 'Google Chrome',
            'Safari/([\d\.]+)$' => 'Apple Safari',
        ];

        foreach ($knownBrowsers as $pattern => $browserName) {
            $matches = [];
            $matchCount = preg_match(sprintf('|%s|', $pattern), $info, $matches);

            if ($matchCount > 0) {
                if ($withVersion) {
                    $version = $matches[1];

                    return sprintf('%s %s', $browserName, $version);
                }

                return $browserName;
            }
        }

        return '';
    }

    /**
     * Returns information if given url is external, from another server / domain
     *
     * @param string $url The url to check
     * @return bool
     */
    public static function isExternalUrl(string $url): bool
    {
        /*
         * Unknown url or it's just slash?
         * Nothing to do
         */
        if (empty($url) || '/' === $url) {
            return false;
        }

        $currentUrl = self::getServerNameOrIp(true);
        $url = self::replenishProtocol($url);

        // Let's prepare pattern of current url
        $search = [
            ':',
            '/',
            '.',
        ];

        $replace = [
            '\:',
            '\/',
            '\.',
        ];

        $currentUrlPattern = str_replace($search, $replace, $currentUrl);

        return !Regex::contains($url, $currentUrlPattern);
    }

    /**
     * Returns information if running server is localhost
     *
     * @return bool
     */
    public static function isServerLocalhost(): bool
    {
        $serverNameOrIp = strtolower(self::getServerNameOrIp());

        return in_array($serverNameOrIp, [
            'localhost',
            '127.0.0.1',
            '127.0.1.1',
        ]);
    }

    /**
     * Replenishes protocol in the given url
     *
     * @param string $url      The url to check and replenish
     * @param string $protocol (optional) The protocol which is replenished. If is empty, protocol of current request
     *                         is used.
     * @return string
     */
    public static function replenishProtocol(string $url, string $protocol = ''): string
    {
        // Let's trim the url
        $url = trim($url);

        /*
         * Url is not provided?
         * Nothing to do
         */
        if (empty($url)) {
            return '';
        }

        /*
         * It's a valid url?
         * Let's return it
         */
        if (Regex::isValidUrl($url, true)) {
            return $url;
        }

        // Protocol is not provided?
        if (empty($protocol)) {
            $protocol = self::getProtocolName();
        }

        return sprintf('%s://%s', $protocol, $url);
    }
}
