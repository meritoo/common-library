<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Test\Common\Exception\Regex;

use Generator;
use Meritoo\Common\Exception\Regex\InvalidHtmlAttributesException;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Type\OopVisibilityType;

/**
 * Test case of an exception used while html attributes are invalid
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 *
 * @internal
 * @covers    \Meritoo\Common\Exception\Regex\InvalidHtmlAttributesException
 */
class InvalidHtmlAttributesExceptionTest extends BaseTestCase
{
    /**
     * Provides html attributes
     *
     * @return Generator
     */
    public function provideHtmlAttributes()
    {
        $template = 'HTML attributes \'%s\' are invalid. Is there everything ok?';

        yield [
            'abc = def',
            sprintf($template, 'abc = def'),
        ];

        yield [
            'abc = def ghi = jkl',
            sprintf($template, 'abc = def ghi = jkl'),
        ];

        yield [
            'abc=def ghi=jkl',
            sprintf($template, 'abc=def ghi=jkl'),
        ];
    }

    /**
     * @param string $htmlAttributes  Invalid html attributes
     * @param string $expectedMessage Expected exception's message
     *
     * @dataProvider provideHtmlAttributes
     */
    public function testConstructorMessage($htmlAttributes, $expectedMessage)
    {
        $exception = InvalidHtmlAttributesException::create($htmlAttributes);
        static::assertSame($expectedMessage, $exception->getMessage());
    }

    public function testConstructorVisibilityAndArguments()
    {
        static::assertConstructorVisibilityAndArguments(InvalidHtmlAttributesException::class, OopVisibilityType::IS_PUBLIC, 3);
    }
}
