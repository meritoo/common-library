<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Test\Common\ValueObject;

use Generator;
use Meritoo\Common\Exception\ValueObject\Template\InvalidContentException;
use Meritoo\Common\Exception\ValueObject\Template\NotEnoughValuesException;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Type\OopVisibilityType;
use Meritoo\Common\ValueObject\Template;

/**
 * Test case for the template with placeholders that may be filled by real data
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 *
 * @internal
 * @covers    \Meritoo\Common\ValueObject\Template
 */
class TemplateTest extends BaseTestCase
{
    public function testConstructor(): void
    {
        static::assertConstructorVisibilityAndArguments(
            Template::class,
            OopVisibilityType::IS_PUBLIC,
            1,
            1
        );
    }

    /**
     * @param string $content          Raw string with placeholders (content of the template)
     * @param string $exceptionMessage Expected message of exception
     *
     * @dataProvider provideInvalidContent
     */
    public function testIsValidUsingInvalidContent(string $content, string $exceptionMessage): void
    {
        $this->expectException(InvalidContentException::class);
        $this->expectExceptionMessage($exceptionMessage);

        new Template($content);
    }

    /**
     * @param Template $template         Template to fill
     * @param array    $values           Pairs of key-value where: key - name of placeholder, value - value of the
     *                                   placeholder
     * @param string   $exceptionMessage Expected message of exception
     *
     * @dataProvider provideTemplateToFillUsingNotEnoughValues
     */
    public function testFillUsingNotEnoughValues(Template $template, array $values, string $exceptionMessage): void
    {
        $this->expectException(NotEnoughValuesException::class);
        $this->expectExceptionMessage($exceptionMessage);

        $template->fill($values);
    }

    /**
     * @param string   $description Description of test
     * @param Template $template    Template to fill
     * @param array    $values      Pairs of key-value where: key - name of placeholder, value - value of the
     *                              placeholder
     * @param string   $expected    Expected result
     *
     * @dataProvider provideTemplateToFill
     */
    public function testFill(string $description, Template $template, array $values, string $expected): void
    {
        static::assertSame($expected, $template->fill($values), $description);
    }

    public function provideInvalidContent(): ?Generator
    {
        $template = 'Content of template \'%s\' is invalid. Did you use string with 1 placeholder at least?';

        yield[
            'An empty string' => '',
            sprintf($template, ''),
        ];

        yield[
            'Without placeholders' => 'test',
            sprintf($template, 'test'),
        ];

        yield[
            'With starting tag only (invalid placeholder)' => 'This is %test',
            sprintf($template, 'This is %test'),
        ];

        yield[
            'With ending tag only (invalid placeholder)' => 'This is test%',
            sprintf($template, 'This is test%'),
        ];
    }

    public function provideTemplateToFillUsingNotEnoughValues(): ?Generator
    {
        $template = 'Not enough values (%d) to fill all placeholders (%d) in template \'%s\'. Did you provide all'
            . ' required values?';

        yield[
            new Template('%test%'),
            [],
            sprintf(
                $template,
                0,
                1,
                '%test%'
            ),
        ];

        yield[
            new Template('%test1% - %test2%'),
            [
                'test1' => 123,
            ],
            sprintf(
                $template,
                1,
                2,
                '%test1% - %test2%'
            ),
        ];
    }

    public function provideTemplateToFill(): ?Generator
    {
        yield[
            'Template with 1 placeholder, but incorrect values',
            new Template('%test%'),
            [
                'something' => 123,
            ],
            '%test%',
        ];

        yield[
            'Template with 1 placeholder',
            new Template('%test%'),
            [
                'test' => 123,
            ],
            '123',
        ];

        yield[
            'Template with 1 placeholder, but more values',
            new Template('%test%'),
            [
                'test'        => 123,
                'anotherTest' => 456,
            ],
            '123',
        ];

        yield[
            'Template with 2 placeholders',
            new Template('My name is %name% and I am %profession%'),
            [
                'name'       => 'Jane',
                'profession' => 'photographer',
            ],
            'My name is Jane and I am photographer',
        ];

        yield[
            'Template with 2 placeholders, but more values',
            new Template('My name is %name% and I am %profession%'),
            [
                'name'        => 'Jane',
                'test-test'   => 123,
                'profession'  => 'photographer',
                'anotherTest' => 456,
            ],
            'My name is Jane and I am photographer',
        ];

        yield[
            'Template with 2 placeholders that contains space',
            new Template('My name is %first name% %last name% and I live in %current location%'),
            [
                'first name'       => 'Jane',
                'last name'        => 'Brown',
                'current location' => 'NY, USA',
            ],
            'My name is Jane Brown and I live in NY, USA',
        ];

        yield[
            'Template with 2 placeholders that contains space, but more values',
            new Template('My name is %first name% %last name% and I live in %current location%'),
            [
                'first name'       => 'Jane',
                'profession'       => 'photographer',
                'last name'        => 'Brown',
                'test-test'        => 123,
                'anotherTest'      => 456,
                'current location' => 'NY, USA',
            ],
            'My name is Jane Brown and I live in NY, USA',
        ];
    }
}