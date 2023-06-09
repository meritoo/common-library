<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Test\Common\Utilities;

use Generator;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Traits\Test\Base\BaseTestCaseTrait;
use Meritoo\Common\Utilities\Arrays;
use Meritoo\Common\Utilities\Miscellaneous;
use Meritoo\Common\Utilities\Regex;
use Meritoo\Common\Utilities\Uri;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(Uri::class)]
#[UsesClass(Arrays::class)]
#[UsesClass(Regex::class)]
#[UsesClass(Miscellaneous::class)]
#[UsesClass(BaseTestCaseTrait::class)]
class UriTest extends BaseTestCase
{
    /**
     * Provides data used to build secured url
     *
     * @return Generator
     */
    public static function provideDataForSecuredUrl(): Generator
    {
        yield [
            '',
            '',
            '',
            '',
        ];

        yield [
            '/',
            '',
            '',
            'http://lorem.com/',
        ];

        yield [
            'contact',
            '',
            '',
            'http://lorem.com/contact',
        ];

        yield [
            'contact',
            'john',
            '',
            'http://lorem.com/contact',
        ];

        yield [
            'contact',
            '',
            'pass123',
            'http://lorem.com/contact',
        ];

        yield [
            'contact',
            'john',
            'pass123',
            'http://john:pass123@lorem.com/contact',
        ];
    }

    public static function provideRootUrlAndUrlParts(): Generator
    {
        yield [
            '',
            '',
        ];

        yield [
            '',
            '',
            '',
        ];

        yield [
            'http://my.example',
            'http://my.example',
            '',
        ];

        yield [
            'http://my.example',
            'http://my.example',
            '',
            '',
        ];

        yield [
            'http://my.example//test/12/test/',
            'http://my.example',
            '',
            'test',
            '12/test',
            '',
        ];

        yield [
            'http://my.example//test/12/test',
            'http://my.example',
            '',
            'test/',
            '12/test/',
        ];

        yield [
            'http://my.example/test/12/test/',
            'http://my.example',
            '/test/',
            '/12/test',
            '',
        ];
    }

    /**
     * Provides url to replenish protocol
     *
     * @return Generator
     */
    public static function provideUrlToReplenishProtocol(): Generator
    {
        yield [
            'http://test',
            'test',
            '',
        ];

        yield [
            'ftp://lorem.ipsum',
            'lorem.ipsum',
            'ftp',
        ];
    }

    /**
     * Provides url used to verify if it's external, from another server / domain
     *
     * @return Generator
     */
    public static function provideUrlToVerifyIfIsExternal(): Generator
    {
        yield [
            '',
            false,
        ];

        yield [
            '/',
            false,
        ];

        yield [
            'http://something.different/first-page',
            true,
        ];

        yield [
            'something.different/first-page',
            true,
        ];

        yield [
            'http://lorem.com',
            false,
        ];

        yield [
            'http://lorem.com/contact',
            false,
        ];

        yield [
            'lorem.com',
            false,
        ];

        yield [
            'lorem.com/contact',
            false,
        ];
    }

    public function testAddProtocolToUrl()
    {
        $http = 'http';
        $https = 'https';

        $url = 'my.domain/some/url';
        $httpUrl = sprintf('%s://%s', $http, $url);
        $httpsUrl = sprintf('%s://%s', $https, $url);

        self::assertEquals($httpUrl, Uri::addProtocolToUrl($httpUrl));
        self::assertEquals($httpUrl, Uri::addProtocolToUrl($url));

        self::assertEquals($httpsUrl, Uri::addProtocolToUrl($url, $https));
        self::assertEquals($httpsUrl, Uri::addProtocolToUrl($httpsUrl, $http));
    }

    /**
     * @param string $expected
     * @param string $rootUrl
     * @param string ...$urlParts
     *
     * @dataProvider provideRootUrlAndUrlParts
     */
    public function testBuildUrl(string $expected, string $rootUrl, string ...$urlParts): void
    {
        static::assertSame($expected, Uri::buildUrl($rootUrl, ...$urlParts));
    }

    public function testConstructor()
    {
        static::assertHasNoConstructor(Uri::class);
    }

    public function testGetFullUriWithHost()
    {
        $_SERVER['HTTP_HOST'] = '';
        $_SERVER['SERVER_PROTOCOL'] = '';
        $_SERVER['REQUEST_URI'] = '';

        self::assertEquals('', Uri::getFullUri());

        $host = 'lorem.com';
        $protocol = 'HTTP/1.1';
        $requestedUrl = '/test/123';

        $_SERVER['HTTP_HOST'] = $host;
        $_SERVER['SERVER_PROTOCOL'] = $protocol;
        $_SERVER['REQUEST_URI'] = $requestedUrl;

        self::assertEquals(sprintf('http://%s%s', $host, $requestedUrl), Uri::getFullUri());
    }

    public function testGetFullUriWithoutHost()
    {
        $_SERVER['HTTP_HOST'] = '';
        $_SERVER['SERVER_PROTOCOL'] = '';
        $_SERVER['REQUEST_URI'] = '';

        self::assertEquals('', Uri::getFullUri(true));

        $requestedUrl = '/test/123';
        $_SERVER['REQUEST_URI'] = $requestedUrl;

        self::assertEquals($requestedUrl, Uri::getFullUri(true));
    }

    public function testGetProtocolName()
    {
        $_SERVER['SERVER_PROTOCOL'] = '';
        self::assertEquals('', Uri::getProtocolName());

        $protocol = 'HTTP/1.1';
        $_SERVER['SERVER_PROTOCOL'] = $protocol;

        self::assertEquals('http', Uri::getProtocolName());
    }

    public function testGetRefererUri()
    {
        $_SERVER['HTTP_REFERER'] = '';
        self::assertEquals('', Uri::getRefererUri());

        $refererUrl = 'http://lorem.com/test/123';
        $_SERVER['HTTP_REFERER'] = $refererUrl;

        self::assertEquals($refererUrl, Uri::getRefererUri());
    }

    /**
     * @param string $url         A path / url to some resource, e.g. page, image, css file
     * @param string $user        User name used to log in
     * @param string $password    User password used to log in
     * @param string $expectedUrl Expected, secured url
     *
     * @dataProvider provideDataForSecuredUrl
     */
    public function testGetSecuredUrl($url, $user, $password, $expectedUrl)
    {
        $_SERVER['SERVER_PROTOCOL'] = 'HTTP/1.1';
        $_SERVER['HTTP_HOST'] = 'lorem.com';

        self::assertEquals($expectedUrl, Uri::getSecuredUrl($url, $user, $password));
    }

    public function testGetServerNameOrIpWithProtocol()
    {
        $_SERVER['HTTP_HOST'] = '';
        $_SERVER['SERVER_PROTOCOL'] = '';

        self::assertEquals('', Uri::getServerNameOrIp(true));

        $host = 'lorem.com';
        $protocol = 'HTTP/1.1';

        $_SERVER['HTTP_HOST'] = $host;
        $_SERVER['SERVER_PROTOCOL'] = $protocol;

        self::assertEquals(sprintf('http://%s', $host), Uri::getServerNameOrIp(true));
    }

    public function testGetServerNameOrIpWithoutProtocol()
    {
        $_SERVER['HTTP_HOST'] = '';
        self::assertEquals('', Uri::getServerNameOrIp());

        $host = 'lorem.com';
        $_SERVER['HTTP_HOST'] = $host;

        self::assertEquals($host, Uri::getServerNameOrIp());
    }

    public function testGetUserAddressIp()
    {
        $_SERVER['REMOTE_ADDR'] = '';
        self::assertEquals('', Uri::getUserAddressIp());

        $userAddressIp = '1.2.3.4';
        $_SERVER['REMOTE_ADDR'] = $userAddressIp;

        self::assertEquals($userAddressIp, Uri::getUserAddressIp());
    }

    public function testGetUserOperatingSystemName()
    {
        $_SERVER['HTTP_USER_AGENT'] = '';
        self::assertEquals('', Uri::getUserOperatingSystemName());

        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/600.2.5 (KHTML, like'
            .' Gecko) Version/8.0.2 Safari/600.2.5';

        self::assertEquals('Mac OS', Uri::getUserOperatingSystemName());
    }

    public function testGetUserWebBrowserInfo()
    {
        $_SERVER['HTTP_USER_AGENT'] = '';
        self::assertEquals('', Uri::getUserWebBrowserInfo());

        $browserInfo = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/600.2.5 (KHTML, like Gecko)'
            .' Version/8.0.2 Safari/600.2.5';

        $_SERVER['HTTP_USER_AGENT'] = $browserInfo;
        self::assertEquals($browserInfo, Uri::getUserWebBrowserInfo());
    }

    public function testGetUserWebBrowserNameWithVersion()
    {
        $_SERVER['HTTP_USER_AGENT'] = '';
        self::assertEquals('', Uri::getUserWebBrowserName(true));

        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/600.2.5 (KHTML, like'
            .' Gecko) Version/8.0.2 Safari/600.2.5';

        self::assertEquals('Apple Safari 600.2.5', Uri::getUserWebBrowserName(true));
    }

    public function testGetUserWebBrowserNameWithoutVersion()
    {
        $_SERVER['HTTP_USER_AGENT'] = '';
        self::assertEquals('', Uri::getUserWebBrowserName());

        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/600.2.5 (KHTML, like'
            .' Gecko) Version/8.0.2 Safari/600.2.5';

        self::assertEquals('Apple Safari', Uri::getUserWebBrowserName());
    }

    /**
     * @param string $url      The url to check
     * @param bool   $expected Information if verified url is external
     *
     * @dataProvider provideUrlToVerifyIfIsExternal
     */
    public function testIsExternalUrl($url, $expected)
    {
        $host = 'lorem.com';
        $protocol = 'HTTP/1.1';

        $_SERVER['HTTP_HOST'] = $host;
        $_SERVER['SERVER_PROTOCOL'] = $protocol;

        self::assertEquals($expected, Uri::isExternalUrl($url));
    }

    public function testIsServerLocalhost()
    {
        $_SERVER['HTTP_HOST'] = '';
        self::assertFalse(Uri::isServerLocalhost());

        $_SERVER['HTTP_HOST'] = '127.0.0.1';
        self::assertTrue(Uri::isServerLocalhost());
    }

    /**
     * @param string $expected Expected result
     * @param string $url      The url to check and replenish
     * @param string $protocol (optional) The protocol which is replenished. If is empty, protocol of current request
     *                         is used.
     *
     * @dataProvider provideUrlToReplenishProtocol
     */
    public function testReplenishProtocol($expected, $url, $protocol = '')
    {
        /*
         * Required to get protocol when it's not provided and to void test failure:
         *
         * Failed asserting that two strings are identical.
         * Expected :'://test'
         * Actual   :'http://test'
         */
        $_SERVER['SERVER_PROTOCOL'] = 'HTTP/1.1';

        self::assertSame($expected, Uri::replenishProtocol($url, $protocol));
    }

    /**
     * @param mixed $url Empty value, e.g. ""
     * @dataProvider provideEmptyScalarValue
     */
    public function testReplenishProtocolEmptyUrl($url)
    {
        self::assertEquals('', Uri::replenishProtocol($url));
    }
}
