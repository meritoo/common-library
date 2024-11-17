<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Test\Common\Collection;

use Generator;
use Meritoo\Common\Collection\Templates;
use Meritoo\Common\Enums\OopVisibility;
use Meritoo\Common\Exception\ValueObject\Template\TemplateNotFoundException;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\ValueObject\Template;

/**
 * Test case for the collection/storage of templates
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 *
 * @internal
 * @covers    \Meritoo\Common\Collection\Templates
 */
class TemplatesTest extends BaseTestCase
{
    public function provideArrayWithTemplates(): ?Generator
    {
        yield [
            'An empty array',
            [],
            new Templates(),
        ];

        yield [
            'Number-based indexes',
            [
                'First name: %first_name%',
                'Last name: %last_name%',
            ],
            new Templates([
                new Template('First name: %first_name%'),
                new Template('Last name: %last_name%'),
            ]),
        ];

        yield [
            'String-based indexes',
            [
                'first' => 'First name: %first_name%',
                'last' => 'Last name: %last_name%',
            ],
            new Templates([
                'first' => new Template('First name: %first_name%'),
                'last' => new Template('Last name: %last_name%'),
            ]),
        ];
    }

    public function provideTemplatesToFind(): ?Generator
    {
        yield [
            '2 templates only',
            new Templates([
                'first' => new Template('First name: %first_name%'),
                'last' => new Template('Last name: %last_name%'),
            ]),
            'first',
            new Template('First name: %first_name%'),
        ];

        yield [
            'Different indexes',
            new Templates([
                'first' => new Template('First name: %first_name%'),
                'last' => new Template('Last name: %last_name%'),
                1 => new Template('Hi %name%, how are you?'),
                '2' => new Template('Your score is: %score%'),
            ]),
            '1',
            new Template('Hi %name%, how are you?'),
        ];
    }

    public function provideTemplatesWithNotExistingIndex(): ?Generator
    {
        $template = 'Template with \'%s\' index was not found. Did you provide all required templates?';

        yield [
            new Templates(),
            'test',
            sprintf($template, 'test'),
        ];

        yield [
            new Templates([
                'first' => new Template('First name: %first_name%'),
                'last' => new Template('Last name: %last_name%'),
            ]),
            'test',
            sprintf($template, 'test'),
        ];

        yield [
            new Templates([
                'first' => new Template('First name: %first_name%'),
                'last' => new Template('Last name: %last_name%'),
            ]),
            '',
            sprintf($template, ''),
        ];

        yield [
            new Templates([
                'first' => new Template('First name: %first_name%'),
                'last' => new Template('Last name: %last_name%'),
            ]),
            '4',
            sprintf($template, 4),
        ];
    }

    public function testConstructor(): void
    {
        static::assertConstructorVisibilityAndArguments(
            Templates::class,
            OopVisibility::Public,
            1
        );
    }

    /**
     * @dataProvider provideTemplatesToFind
     */
    public function testFindTemplate(string $description, Templates $templates, string $index, Template $expected): void
    {
        static::assertEquals($expected, $templates->findTemplate($index), $description);
    }

    public function testFindTemplateUsingEmptyCollection(): void
    {
        $template = 'Template with \'%s\' index was not found. Did you provide all required templates?';
        $message = sprintf($template, 'test');

        $this->expectException(TemplateNotFoundException::class);
        $this->expectExceptionMessage($message);

        $templates = new Templates();
        $templates->findTemplate('test');
    }

    /**
     * @dataProvider provideTemplatesWithNotExistingIndex
     */
    public function testFindTemplateUsingNotExistingIndex(
        Templates $templates,
        string $index,
        string $expectedMessage
    ): void {
        $this->expectException(TemplateNotFoundException::class);
        $this->expectExceptionMessage($expectedMessage);

        $templates->findTemplate($index);
    }

    /**
     * @dataProvider provideArrayWithTemplates
     */
    public function testFromArray(string $description, array $templates, Templates $expected): void
    {
        static::assertEquals($expected, Templates::fromArray($templates), $description);
    }
}
