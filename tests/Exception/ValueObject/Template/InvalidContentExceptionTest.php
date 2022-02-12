<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Test\Common\Exception\ValueObject\Template;

use Generator;
use Meritoo\Common\Exception\ValueObject\Template\InvalidContentException;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Type\OopVisibilityType;

/**
 * Test case of an exception used while content of template is invalid
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 *
 * @internal
 * @covers    \Meritoo\Common\Exception\ValueObject\Template\InvalidContentException
 */
class InvalidContentExceptionTest extends BaseTestCase
{
    public function provideContent(): ?Generator
    {
        $template = 'Content of template \'%s\' is invalid. Did you use string with 1 placeholder at least?';

        yield [
            'An empty string',
            '',
            sprintf($template, ''),
        ];

        yield [
            'Simple string',
            'Lorem ipsum',
            sprintf($template, 'Lorem ipsum'),
        ];

        yield [
            'One sentence',
            'Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh.',
            sprintf($template, 'Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh.'),
        ];
    }

    public function testConstructorVisibilityAndArguments(): void
    {
        static::assertConstructorVisibilityAndArguments(
            InvalidContentException::class,
            OopVisibilityType::IS_PUBLIC,
            3
        );
    }

    /**
     * @param string $description     Description of test
     * @param string $content         Invalid content of template
     * @param string $expectedMessage Expected exception's message
     *
     * @dataProvider provideContent
     */
    public function testCreate(string $description, string $content, string $expectedMessage): void
    {
        $exception = InvalidContentException::create($content);
        static::assertSame($expectedMessage, $exception->getMessage(), $description);
    }
}
