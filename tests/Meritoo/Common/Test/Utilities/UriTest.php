<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Test\Utilities;

use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Utilities\Uri;

/**
 * Tests of the useful uri methods (only static functions).
 *
 * @author    Krzysztof Niziol <krzysztof.niziol@meritoo.pl>
 * @copyright Meritoo.pl
 */
class UriTest extends BaseTestCase
{
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
     * @param mixed $url Empty value, e.g. ""
     * @dataProvider provideEmptyValue
     */
    public function testReplenishProtocolEmptyUrl($url)
    {
        self::assertEquals('', Uri::replenishProtocol($url));
    }

    /**
     * @param string $expected Expected result
     * @param string $url      The url to check and replenish
     * @param string $protocol (optional) The protocol which is replenished. If is empty, protocol of current request
     *                         is used.
     *
     * @dataProvider provideUrlsToReplenishProtocol
     */
    public function testReplenishProtocol($expected, $url, $protocol = '')
    {
        self::assertSame($expected, Uri::replenishProtocol($url, $protocol));
    }

    /**
     * Provides urls to replenish protocol.
     *
     * @return \Generator
     */
    public function provideUrlsToReplenishProtocol()
    {
        yield[
            '://test',
            'test',
            '',
        ];

        yield[
            'ftp://lorem.ipsum',
            'lorem.ipsum',
            'ftp',
        ];
    }
}
