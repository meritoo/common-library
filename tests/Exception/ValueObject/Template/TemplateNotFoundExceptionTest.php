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
use Meritoo\Common\Traits\Test\Base\BaseTestCaseTrait;
use Meritoo\Common\Type\Base\BaseType;
use Meritoo\Common\Type\OopVisibilityType;
use Meritoo\Common\Utilities\Reflection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(TemplateNotFoundException::class)]
#[UsesClass(BaseTestCaseTrait::class)]
#[UsesClass(BaseType::class)]
#[UsesClass(Reflection::class)]
class TemplateNotFoundExceptionTest extends BaseTestCase
{
    public static function provideIndexAndException(): Generator
    {
        $template = 'Template with \'%s\' index was not found. Did you provide all required templates?';

        yield [
            'An empty string',
            '',
            new TemplateNotFoundException(sprintf($template, '')),
        ];

        yield [
            'Non-empty string',
            'test',
            new TemplateNotFoundException(sprintf($template, 'test')),
        ];

        yield [
            'Integer',
            '2',
            new TemplateNotFoundException(sprintf($template, 2)),
        ];
    }

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
}
