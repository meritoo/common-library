<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Test\Common\Exception\ValueObject\Template;

use Generator;
use Meritoo\Common\Exception\ValueObject\Template\TemplateNotFoundException;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Type\OopVisibilityType;

/**
 * Test case of an exception used while template with given index was not found
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 *
 * @internal
 * @covers    \Meritoo\Common\Exception\ValueObject\Template\TemplateNotFoundException
 */
class TemplateNotFoundExceptionTest extends BaseTestCase
{
    public function testConstructor(): void
    {
        static::assertConstructorVisibilityAndArguments(
            TemplateNotFoundException::class,
            OopVisibilityType::IS_PUBLIC,
            3
        );
    }

    /**
     * @param string                    $description Description of test
     * @param string                    $index       Index that should contain template, but it was not found
     * @param TemplateNotFoundException $expected    Expected exception
     *
     * @dataProvider provideIndexAndException
     */
    public function testCreate(string $description, string $index, TemplateNotFoundException $expected): void
    {
        $created = TemplateNotFoundException::create($index);
        static::assertEquals($expected, $created, $description);
    }

    public function provideIndexAndException(): ?Generator
    {
        $template = 'Template with \'%s\' index was not found. Did you provide all required templates?';

        yield[
            'An empty string',
            '',
            new TemplateNotFoundException(sprintf($template, '')),
        ];

        yield[
            'Non-empty string',
            'test',
            new TemplateNotFoundException(sprintf($template, 'test')),
        ];

        yield[
            'Integer',
            '2',
            new TemplateNotFoundException(sprintf($template, 2)),
        ];
    }
}
