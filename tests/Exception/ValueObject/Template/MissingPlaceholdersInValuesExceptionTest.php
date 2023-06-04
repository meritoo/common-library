<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Test\Common\Exception\ValueObject\Template;

use Generator;
use Meritoo\Common\Exception\ValueObject\Template\MissingPlaceholdersInValuesException;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Type\OopVisibilityType;

/**
 * Test case of an exception used while there are missing values required to fill all placeholders in template
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 *
 * @internal
 * @covers    \Meritoo\Common\Exception\ValueObject\Template\MissingPlaceholdersInValuesException
 */
class MissingPlaceholdersInValuesExceptionTest extends BaseTestCase
{
    public static function provideContentAndMissingPlaceholders(): Generator
    {
        $template = 'Cannot fill template \'%s\', because of missing values for placeholder(s): %s. Did you provide all'
            .' required values?';

        yield [
            'Missing 2nd placeholder',
            '%test1% - %test2%',
            [
                'test2',
            ],
            sprintf(
                $template,
                '%test1% - %test2%',
                'test2'
            ),
        ];

        yield [
            'Missing 2nd and 3rd placeholder',
            '%test1% / %test2% / %test3%',
            [
                'test2',
                'test3',
            ],
            sprintf(
                $template,
                '%test1% / %test2% / %test3%',
                'test2, test3'
            ),
        ];
    }

    public function testConstructorVisibilityAndArguments(): void
    {
        static::assertConstructorVisibilityAndArguments(
            MissingPlaceholdersInValuesException::class,
            OopVisibilityType::IS_PUBLIC,
            3
        );
    }

    /**
     * @param string $description         Description of test
     * @param string $content             Content of template
     * @param array  $missingPlaceholders Missing placeholders in provided values, iow. placeholders without values
     * @param string $expectedMessage     Expected exception's message
     *
     * @dataProvider provideContentAndMissingPlaceholders
     */
    public function testCreate(
        string $description,
        string $content,
        array $missingPlaceholders,
        string $expectedMessage
    ): void {
        $exception = MissingPlaceholdersInValuesException::create($content, $missingPlaceholders);
        static::assertSame($expectedMessage, $exception->getMessage(), $description);
    }
}
