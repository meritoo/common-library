<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Test\Common\Exception\ValueObject\Template;

use Generator;
use Meritoo\Common\Exception\ValueObject\Template\NotEnoughValuesException;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Type\OopVisibilityType;

/**
 * Test case of an exception used while there is not enough values to fill all placeholders in template
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 *
 * @internal
 * @covers    \Meritoo\Common\Exception\ValueObject\Template\NotEnoughValuesException
 */
class NotEnoughValuesExceptionTest extends BaseTestCase
{
    public function testConstructorVisibilityAndArguments(): void
    {
        static::assertConstructorVisibilityAndArguments(
            NotEnoughValuesException::class,
            OopVisibilityType::IS_PUBLIC,
            3
        );
    }

    /**
     * @param string $description       Description of test
     * @param string $content           Invalid content of template
     * @param int    $valuesCount       Count of values
     * @param int    $placeholdersCount Count of placeholders
     * @param string $expectedMessage   Expected exception's message
     *
     * @dataProvider provideContentAndValuesPlaceholdersCount
     */
    public function testCreate(
        string $description,
        string $content,
        int $valuesCount,
        int $placeholdersCount,
        string $expectedMessage
    ): void {
        $exception = NotEnoughValuesException::create($content, $valuesCount, $placeholdersCount);
        static::assertSame($expectedMessage, $exception->getMessage(), $description);
    }

    public function provideContentAndValuesPlaceholdersCount(): ?Generator
    {
        $template = 'Not enough values (%d) to fill all placeholders (%d) in template \'%s\'. Did you provide all'
            . ' required values?';

        yield[
            'An empty string',
            '',
            3,
            1,
            sprintf($template, 3, 1, ''),
        ];

        yield[
            'Simple string',
            'Lorem ipsum',
            1,
            4,
            sprintf($template, 1, 4, 'Lorem ipsum'),
        ];

        yield[
            'One sentence',
            'Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh.',
            5,
            0,
            sprintf($template, 5, 0, 'Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh.'),
        ];
    }
}
