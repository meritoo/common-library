<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Test\Common\Exception\Regex;

use Generator;
use Meritoo\Common\Exception\Regex\InvalidUrlException;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Traits\Test\Base\BaseTestCaseTrait;
use Meritoo\Common\Type\Base\BaseType;
use Meritoo\Common\Type\OopVisibilityType;
use Meritoo\Common\Utilities\Reflection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(InvalidUrlException::class)]
#[UsesClass(BaseTestCaseTrait::class)]
#[UsesClass(BaseType::class)]
#[UsesClass(Reflection::class)]
class InvalidUrlExceptionTest extends BaseTestCase
{
    /**
     * Provides invalid url and expected exception's message
     *
     * @return Generator
     */
    public static function provideUrl(): Generator
    {
        $template = 'Url \'%s\' is invalid. Is there everything ok?';

        yield [
            'aa/bb/cc',
            sprintf($template, 'aa/bb/cc'),
        ];

        yield [
            'http:/images\show\car.jpg',
            sprintf($template, 'http:/images\show\car.jpg'),
        ];
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
        static::assertSame($expectedMessage, $exception->getMessage());
    }

    public function testConstructorVisibilityAndArguments()
    {
        static::assertConstructorVisibilityAndArguments(InvalidUrlException::class, OopVisibilityType::IS_PUBLIC, 3);
    }
}
