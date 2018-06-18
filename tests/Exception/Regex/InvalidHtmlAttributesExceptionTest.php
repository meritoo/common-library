<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Test\Exception\Regex;

use Generator;
use Meritoo\Common\Exception\Regex\InvalidHtmlAttributesException;
use Meritoo\Common\Exception\Type\UnknownOopVisibilityTypeException;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Type\OopVisibilityType;

/**
 * Test case of an exception used while html attributes are invalid
 *
 * @author    Krzysztof Niziol <krzysztof.niziol@meritoo.pl>
 * @copyright Meritoo.pl
 */
class InvalidHtmlAttributesExceptionTest extends BaseTestCase
{
    /**
     * @throws UnknownOopVisibilityTypeException
     */
    public function testConstructorVisibilityAndArguments()
    {
        static::assertConstructorVisibilityAndArguments(InvalidHtmlAttributesException::class, OopVisibilityType::IS_PUBLIC, 1, 1);
    }

    /**
     * @param string $htmlAttributes  Invalid html attributes
     * @param string $expectedMessage Expected exception's message
     *
     * @dataProvider provideHtmlAttributes
     */
    public function testConstructorMessage($htmlAttributes, $expectedMessage)
    {
        $exception = new InvalidHtmlAttributesException($htmlAttributes);
        static::assertEquals($expectedMessage, $exception->getMessage());
    }

    /**
     * Provides html attributes
     *
     * @return Generator
     */
    public function provideHtmlAttributes()
    {
        $template = 'HTML attributes \'%s\' are invalid. Is there everything ok?';

        yield[
            'abc = def',
            sprintf($template, 'abc = def'),
        ];

        yield[
            'abc = def ghi = jkl',
            sprintf($template, 'abc = def ghi = jkl'),
        ];

        yield[
            'abc=def ghi=jkl',
            sprintf($template, 'abc=def ghi=jkl'),
        ];
    }
}
