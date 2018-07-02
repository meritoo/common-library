<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Test\Exception\Regex;

use Generator;
use Meritoo\Common\Exception\Regex\InvalidUrlException;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Type\OopVisibilityType;

/**
 * Test case of an exception used while url is invalid
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class InvalidUrlExceptionTest extends BaseTestCase
{
    public function testConstructorVisibilityAndArguments()
    {
        static::assertConstructorVisibilityAndArguments(InvalidUrlException::class, OopVisibilityType::IS_PUBLIC, 3);
    }

    /**
     * @param string $url             Invalid url
     * @param string $expectedMessage Expected exception's message
     *
     * @dataProvider provideUrl
     */
    public function testConstructorMessage($url, $expectedMessage)
    {
        $exception = InvalidUrlException::create($url);
        static::assertEquals($expectedMessage, $exception->getMessage());
    }

    /**
     * Provides invalid url and expected exception's message
     *
     * @return Generator
     */
    public function provideUrl()
    {
        $template = 'Url \'%s\' is invalid. Is there everything ok?';

        yield[
            'aa/bb/cc',
            sprintf($template, 'aa/bb/cc'),
        ];

        yield[
            'http:/images\show\car.jpg',
            sprintf($template, 'http:/images\show\car.jpg'),
        ];
    }
}
