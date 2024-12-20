<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Test\Common\Utilities;

use Meritoo\Common\Exception\Regex\IncorrectColorHexLengthException;
use Meritoo\Common\Exception\Regex\InvalidColorHexValueException;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Utilities\Regex;
use stdClass;

/**
 * Test case of the useful regular expressions methods
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 *
 * @internal
 * @covers    \Meritoo\Common\Utilities\Regex
 */
class RegexTest extends BaseTestCase
{
    private $simpleText;
    private $camelCaseText;

    /**
     * Provides value to verify if it is a binary value
     *
     * @return \Generator
     */
    public function provideBinaryValue(): \Generator
    {
        $file1Path = $this->getFilePathForTesting('lorem-ipsum.txt');
        $file2Path = $this->getFilePathForTesting('minion.jpg');

        yield [
            '',
            false,
        ];

        yield [
            'abc',
            false,
        ];

        yield [
            '1234',
            false,
        ];

        yield [
            1234,
            false,
        ];

        yield [
            12.34,
            false,
        ];

        yield [
            fread(fopen($file1Path, 'rb'), 1),
            false,
        ];

        yield [
            fread(fopen($file2Path, 'rb'), 1),
            true,
        ];
    }

    /**
     * Provides name of bundle and information if it's valid name
     *
     * @return \Generator
     */
    public function provideBundleName(): \Generator
    {
        yield [
            'something',
            false,
        ];

        yield [
            'something_different',
            false,
        ];

        yield [
            'something-else',
            false,
        ];

        yield [
            'myExtraBundle',
            false,
        ];

        yield [
            'MyExtra',
            false,
        ];

        yield [
            'MyExtraBundle',
            true,
        ];

        yield [
            'MySuperExtraGorgeousBundle',
            true,
        ];
    }

    /**
     * Provides value of color
     *
     * @return \Generator
     */
    public function provideColor(): \Generator
    {
        yield [
            '#1b0',
            '11bb00',
        ];

        yield [
            '#1B0',
            '11bb00',
        ];

        yield [
            '#1ab1ab',
            '1ab1ab',
        ];

        yield [
            '#1AB1AB',
            '1ab1ab',
        ];

        yield [
            '#000',
            '000000',
        ];
    }

    /**
     * Provides empty non color-related value
     *
     * @return \Generator
     */
    public function provideColorEmptyValue(): \Generator
    {
        yield [
            '',
        ];

        yield [
            0,
        ];

        yield [
            '0',
        ];

        yield [
            false,
        ];
    }

    /**
     * Provides value of color with incorrect length
     *
     * @return \Generator
     */
    public function provideColorIncorrectLength(): \Generator
    {
        yield [
            '12',
        ];

        yield [
            '1234',
        ];

        yield [
            '12345678',
        ];

        yield [
            '#12',
        ];

        yield [
            '#1234',
        ];

        yield [
            '#12345678',
        ];
    }

    /**
     * Provides invalid value of color
     *
     * @return \Generator
     */
    public function provideColorInvalidValue(): \Generator
    {
        yield [
            '#qwerty',
        ];

        yield [
            'qwerty',
        ];
    }

    /**
     * Provides e-mail and information if it's valid
     *
     * @return \Generator
     */
    public function provideEmail(): \Generator
    {
        yield [
            '1',
            false,
        ];

        yield [
            1,
            false,
        ];

        yield [
            'a@a',
            false,
        ];

        yield [
            'a@a.com',
            false,
        ];

        yield [
            'aa@a.com',
            true,
        ];

        yield [
            'a.b@d.com',
            true,
        ];
    }

    /**
     * Provides empty non money-related value
     *
     * @return \Generator
     */
    public function provideEmptyNonMoneyValue(): \Generator
    {
        yield [''];
        yield ['   '];
        yield [null];
        yield [false];
        yield [[]];
    }

    public function provideFileName(): \Generator
    {
        yield [
            'An empty string',
            '',
            false,
        ];

        yield [
            'Path of this file, of file with test case',
            __DIR__,
            false,
        ];

        yield [
            'Name of this file, of file with test case',
            __FILE__,
            true,
        ];

        yield [
            'Complicated name of file',
            'this-1_2 3 & my! 4+file.jpg',
            true,
        ];

        yield [
            'Complicated name of file',
            'directory1/directory2/this-1_2 3 & my! 4+file.jpg',
            true,
        ];
    }

    /**
     * Provides html attribute and information if it's valid
     *
     * @return \Generator
     */
    public function provideHtmlAttribute(): \Generator
    {
        yield [
            'abc = def',
            false,
        ];

        yield [
            'a b c=def',
            false,
        ];

        yield [
            'abc=def',
            false,
        ];

        yield [
            'a1b2c=d3e4f',
            false,
        ];

        yield [
            'abc="def"',
            true,
        ];

        yield [
            'a1b2c="d3e4f"',
            true,
        ];
    }

    /**
     * Provides html attribute and information if attributes are valid
     *
     * @return \Generator
     */
    public function provideHtmlAttributes(): \Generator
    {
        yield [
            'abc = def',
            false,
        ];

        yield [
            'abc = def ghi = jkl',
            false,
        ];

        yield [
            'abc=def ghi=jkl',
            false,
        ];

        yield [
            'abc=def ghi=jkl mno=pqr',
            false,
        ];

        yield [
            'abc="def"',
            true,
        ];

        yield [
            'abc="def" ghi="jkl"',
            true,
        ];

        yield [
            'abc="def" ghi="jkl" mno="pqr"',
            true,
        ];

        yield [
            'a2bc="d4ef" ghi="j k l" mno="pq9r"',
            true,
        ];
    }

    /**
     * Provides money-related value and information if the value is valid
     *
     * @return \Generator
     */
    public function provideMoneyValue(): \Generator
    {
        yield [
            'abc',
            false,
        ];

        yield [
            '-a.b',
            false,
        ];

        yield [
            'a,b',
            false,
        ];

        yield [
            0,
            true,
        ];

        yield [
            1,
            true,
        ];

        yield [
            -1,
            true,
        ];

        yield [
            1.2,
            true,
        ];

        yield [
            1.202,
            true,
        ];

        yield [
            -1.202,
            true,
        ];

        yield [
            '0',
            true,
        ];

        yield [
            '1',
            true,
        ];

        yield [
            '-1',
            true,
        ];

        yield [
            '1.2',
            true,
        ];

        yield [
            '1.202',
            true,
        ];

        yield [
            '-1.202',
            true,
        ];

        yield [
            '1,202',
            true,
        ];

        yield [
            '-1,2',
            true,
        ];

        yield [
            '-1,202',
            true,
        ];
    }

    /**
     * Provides pattern and array with keys that should match that pattern
     *
     * @return \Generator
     */
    public function providePatternForArrayKeys(): \Generator
    {
        yield [
            '/\d/',
            [],
            [],
        ];

        yield [
            '/\d+/',
            [
                'lorem' => 'ipsum',
                'dolor' => 123,
                'sit',
                4 => '456',
            ],
            [
                0 => 'sit',
                4 => '456',
            ],
        ];

        yield [
            '/\d+-[a-z]+/',
            [
                'lorem',
                '456-ipsum' => 123,
                '001-sit' => false,
                'dolor',
            ],
            [
                '456-ipsum' => 123,
                '001-sit' => false,
            ],
        ];
    }

    /**
     * Provides pattern and array with values that should match that pattern
     *
     * @return \Generator
     */
    public function providePatternForArrayValues(): \Generator
    {
        yield [
            '/\d/',
            [],
            [],
        ];

        yield [
            '/\d+/',
            [
                'lorem',
                'ipsum',
                123,
                'dolor',
                '456',
            ],
            [
                2 => 123,
                4 => '456',
            ],
        ];

        yield [
            '/\d+-[a-z]+/',
            [
                'lorem',
                123,
                false,
                'dolor',
                '456-ipsum',
            ],
            [
                4 => '456-ipsum',
            ],
        ];
    }

    /**
     * Provides patterns and subject for the pregMultiMatch() method
     *
     * @return \Generator
     */
    public function providePatternsAndSubjectForPregMultiMatch(): \Generator
    {
        yield [
            '',
            '',
            false,
        ];

        yield [
            [],
            '',
            false,
        ];

        yield [
            '/\d+/',
            'Lorem ipsum dolor sit',
            false,
        ];

        yield [
            [
                '/\d+/',
                '/^[a-z]{4}$/',
            ],
            'Lorem ipsum dolor sit',
            false,
        ];

        yield [
            '/\w+/',
            'Lorem ipsum dolor sit',
            true,
        ];

        yield [
            [
                '/\d+/',
                '/\w+/',
            ],
            'Lorem ipsum dolor sit',
            true,
        ];
    }

    /**
     * Provides patterns and subject for the pregMultiMatch() method when must match all patterns
     *
     * @return \Generator
     */
    public function providePatternsAndSubjectForPregMultiMatchWhenMustMatchAllPatterns(): \Generator
    {
        yield [
            '',
            '',
            false,
        ];

        yield [
            [],
            '',
            false,
        ];

        yield [
            '/\d+/',
            'Lorem ipsum dolor sit',
            false,
        ];

        yield [
            [
                '/\d+/',
                '/^[a-z]{4}$/',
            ],
            'Lorem ipsum dolor sit',
            false,
        ];

        yield [
            '/\w+/',
            'Lorem ipsum dolor sit',
            true,
        ];

        yield [
            [
                '/[a-zA-Z ]+/',
                '/\w+/',
            ],
            'Lorem ipsum dolor sit',
            true,
        ];
    }

    /**
     * Provides phone number and information if it's valid
     *
     * @return \Generator
     */
    public function providePhoneNumber(): \Generator
    {
        yield [
            'abc',
            false,
        ];

        yield [
            '1-2-3',
            false,
        ];

        yield [
            '123',
            true,
        ];

        yield [
            '123 456 789',
            true,
        ];

        yield [
            '123456789',
            true,
        ];
    }

    /**
     * Provides regular expression for array filtering
     *
     * @return \Generator
     */
    public function provideRegularExpressionForArrayFiltering(): \Generator
    {
        yield [
            [],
            'id',
            '/\d+/',
            [],
        ];

        yield [
            [
                [
                    'id' => 1,
                    'first_name' => 'Jane',
                    'last_name' => 'Scott',
                    'is_active' => true,
                ],
                [
                    'id' => 2,
                    'first_name' => 'George',
                    'last_name' => 'Brown',
                    'is_active' => true,
                ],
                [
                    'id' => 3,
                    'first_name' => 'Mike',
                    'last_name' => 'Green',
                    'is_active' => false,
                ],
            ],
            'birth_date',
            '/\d+/',
            [
                [
                    'id' => 1,
                    'first_name' => 'Jane',
                    'last_name' => 'Scott',
                    'is_active' => true,
                ],
                [
                    'id' => 2,
                    'first_name' => 'George',
                    'last_name' => 'Brown',
                    'is_active' => true,
                ],
                [
                    'id' => 3,
                    'first_name' => 'Mike',
                    'last_name' => 'Green',
                    'is_active' => false,
                ],
            ],
        ];

        yield [
            [
                [
                    'id' => 1,
                    'first_name' => 'Jane',
                    'last_name' => 'Scott',
                    'is_active' => true,
                ],
                [
                    'id' => 123,
                    'first_name' => 'George',
                    'last_name' => 'Brown',
                    'is_active' => true,
                ],
                [
                    'id' => 3,
                    'first_name' => 'Mike',
                    'last_name' => 'Green',
                    'is_active' => false,
                ],
            ],
            'id',
            '/\d{3}/',
            [
                1 => [
                    'id' => 123,
                    'first_name' => 'George',
                    'last_name' => 'Brown',
                    'is_active' => true,
                ],
            ],
        ];

        yield [
            [
                [
                    'id' => 1,
                    'first_name' => 'Jane',
                    'last_name' => 'Scott',
                    'is_active' => true,
                ],
                [
                    'id' => 123,
                    'first_name' => 'George',
                    'last_name' => 'Brown',
                    'is_active' => true,
                ],
                [
                    'id' => 456,
                    'first_name' => 'Mike',
                    'last_name' => 'Green',
                    'is_active' => false,
                ],
            ],
            'first_name',
            '/George|Mike/',
            [
                1 => [
                    'id' => 123,
                    'first_name' => 'George',
                    'last_name' => 'Brown',
                    'is_active' => true,
                ],
                2 => [
                    'id' => 456,
                    'first_name' => 'Mike',
                    'last_name' => 'Green',
                    'is_active' => false,
                ],
            ],
        ];

        yield [
            [
                [
                    'id' => 1,
                    'first_name' => 'Jane',
                    'last_name' => 'Scott',
                    'is_active' => true,
                ],
                [
                    'id' => 2,
                    'first_name' => 'George',
                    'last_name' => 'Brown',
                    'is_active' => true,
                ],
                [
                    'id' => 3,
                    'first_name' => 'Mike',
                    'last_name' => 'Green-Blue',
                    'is_active' => false,
                ],
            ],
            'last_name',
            '/\w+-\w+/',
            [
                2 => [
                    'id' => 3,
                    'first_name' => 'Mike',
                    'last_name' => 'Green-Blue',
                    'is_active' => false,
                ],
            ],
        ];
    }

    /**
     * Provides Closure for array filtering
     *
     * @return \Generator
     */
    public function provideClosureForArrayFiltering(): \Generator
    {
        yield 'an empty array' => [
            [],
            'id',
            fn($value) => $value == 2,
            [],
        ];

        yield 'not existing column' => [
            [
                [
                    'id' => 1,
                    'first_name' => 'Jane',
                    'last_name' => 'Scott',
                    'is_active' => true,
                ],
                [
                    'id' => 2,
                    'first_name' => 'George',
                    'last_name' => 'Brown',
                    'is_active' => true,
                ],
                [
                    'id' => 3,
                    'first_name' => 'Mike',
                    'last_name' => 'Green',
                    'is_active' => false,
                ],
            ],
            'birth_date',
            fn($value) => $value == 2,
            [
                [
                    'id' => 1,
                    'first_name' => 'Jane',
                    'last_name' => 'Scott',
                    'is_active' => true,
                ],
                [
                    'id' => 2,
                    'first_name' => 'George',
                    'last_name' => 'Brown',
                    'is_active' => true,
                ],
                [
                    'id' => 3,
                    'first_name' => 'Mike',
                    'last_name' => 'Green',
                    'is_active' => false,
                ],
            ],
        ];

        yield 'non-strict equals 2' => [
            [
                [
                    'id' => 1,
                    'first_name' => 'Jane',
                    'last_name' => 'Scott',
                    'is_active' => true,
                ],
                [
                    'id' => '2',
                    'first_name' => 'George',
                    'last_name' => 'Brown',
                    'is_active' => true,
                ],
                [
                    'id' => 3,
                    'first_name' => 'Mike',
                    'last_name' => 'Green',
                    'is_active' => false,
                ],
                [
                    'id' => 2,
                    'first_name' => 'Kate',
                    'last_name' => 'Ryan',
                    'is_active' => false,
                ],
            ],
            'id',
            fn($value) => $value == 2,
            [
                1 => [
                    'id' => '2',
                    'first_name' => 'George',
                    'last_name' => 'Brown',
                    'is_active' => true,
                ],
                3 => [
                    'id' => 2,
                    'first_name' => 'Kate',
                    'last_name' => 'Ryan',
                    'is_active' => false,
                ],
            ],
        ];

        yield 'strict equals 2' => [
            [
                [
                    'id' => 1,
                    'first_name' => 'Jane',
                    'last_name' => 'Scott',
                    'is_active' => true,
                ],
                [
                    'id' => '2',
                    'first_name' => 'George',
                    'last_name' => 'Brown',
                    'is_active' => true,
                ],
                [
                    'id' => 3,
                    'first_name' => 'Mike',
                    'last_name' => 'Green',
                    'is_active' => false,
                ],
                [
                    'id' => 2,
                    'first_name' => 'Kate',
                    'last_name' => 'Ryan',
                    'is_active' => false,
                ],
            ],
            'id',
            fn($value) => $value === 2,
            [
                3 => [
                    'id' => 2,
                    'first_name' => 'Kate',
                    'last_name' => 'Ryan',
                    'is_active' => false,
                ],
            ],
        ];

        yield 'greater than or equals 2' => [
            [
                [
                    'id' => 1,
                    'first_name' => 'Jane',
                    'last_name' => 'Scott',
                    'is_active' => true,
                ],
                [
                    'id' => 2,
                    'first_name' => 'George',
                    'last_name' => 'Brown',
                    'is_active' => true,
                ],
                [
                    'id' => 3,
                    'first_name' => 'Mike',
                    'last_name' => 'Green',
                    'is_active' => false,
                ],
            ],
            'id',
            fn($value) => $value >= 2,
            [
                1 => [
                    'id' => 2,
                    'first_name' => 'George',
                    'last_name' => 'Brown',
                    'is_active' => true,
                ],
                2 => [
                    'id' => 3,
                    'first_name' => 'Mike',
                    'last_name' => 'Green',
                    'is_active' => false,
                ],
            ],
        ];

        yield 'strict not equals `true`' => [
            [
                [
                    'id' => 1,
                    'first_name' => 'Jane',
                    'last_name' => 'Scott',
                    'is_active' => true,
                ],
                [
                    'id' => 2,
                    'first_name' => 'George',
                    'last_name' => 'Brown',
                    'is_active' => true,
                ],
                [
                    'id' => 3,
                    'first_name' => 'Mike',
                    'last_name' => 'Green',
                    'is_active' => false,
                ],
            ],
            'is_active',
            fn($value) => $value !== true,
            [
                2 => [
                    'id' => 3,
                    'first_name' => 'Mike',
                    'last_name' => 'Green',
                    'is_active' => false,
                ],
            ],
        ];

        yield 'not `true`' => [
            [
                [
                    'id' => 1,
                    'first_name' => 'Jane',
                    'last_name' => 'Scott',
                    'is_active' => true,
                ],
                [
                    'id' => 2,
                    'first_name' => 'George',
                    'last_name' => 'Brown',
                    'is_active' => true,
                ],
                [
                    'id' => 3,
                    'first_name' => 'Mike',
                    'last_name' => 'Green',
                    'is_active' => false,
                ],
            ],
            'is_active',
            fn($value) => !$value,
            [
                2 => [
                    'id' => 3,
                    'first_name' => 'Mike',
                    'last_name' => 'Green',
                    'is_active' => false,
                ],
            ],
        ];

        yield 'equals `Mike`' => [
            [
                [
                    'id' => 1,
                    'first_name' => 'Jane',
                    'last_name' => 'Scott',
                    'is_active' => true,
                ],
                [
                    'id' => 2,
                    'first_name' => 'George',
                    'last_name' => 'Brown',
                    'is_active' => true,
                ],
                [
                    'id' => 3,
                    'first_name' => 'Mike',
                    'last_name' => 'Green',
                    'is_active' => false,
                ],
            ],
            'first_name',
            fn($value) => $value === 'Mike',
            [
                2 => [
                    'id' => 3,
                    'first_name' => 'Mike',
                    'last_name' => 'Green',
                    'is_active' => false,
                ],
            ],
        ];
    }

    public function provideSizeToVerify()
    {
        yield [
            'One number only',
            200,
            ' x ',
            false,
        ];

        yield [
            'One number only as string',
            '200',
            ' x ',
            false,
        ];

        yield [
            'The " " as invalid separator',
            '200 100',
            ' x ',
            false,
        ];

        yield [
            'The "|" as separator (invalid separator)',
            '200 | 100',
            ' x ',
            false,
        ];

        yield [
            'The "|" as invalid separator and no spaces around separator',
            '200|100',
            ' x ',
            false,
        ];

        yield [
            'The "X" as invalid separator',
            '200 X 100',
            ' x ',
            false,
        ];

        yield [
            'Simple, valid size',
            '200 x 100',
            ' x ',
            true,
        ];

        yield [
            'Too much spaces at the right of separator',
            '200 x   100',
            ' x ',
            true,
        ];

        yield [
            'Too much spaces at the left of separator',
            '200   x 100',
            ' x ',
            true,
        ];

        yield [
            'Too much spaces around separator',
            '200   x   100',
            ' x ',
            true,
        ];

        yield [
            'Too much spaces before width',
            '   200 x 100',
            ' x ',
            true,
        ];

        yield [
            'Too much spaces after height',
            '200 x 100   ',
            ' x ',
            true,
        ];

        yield [
            'Too much spaces before width and after height',
            '   200 x 100   ',
            ' x ',
            true,
        ];

        yield [
            'Too much spaces everywhere (1st)',
            '   200   x 100   ',
            ' x ',
            true,
        ];

        yield [
            'Too much spaces everywhere (2nd)',
            '   200 x   100   ',
            ' x ',
            true,
        ];

        yield [
            'Too much spaces everywhere (3rd)',
            '   200   x   100   ',
            ' x ',
            true,
        ];

        yield [
            'The " X " as custom separator',
            '200 X 100',
            ' X ',
            true,
        ];

        yield [
            'The "|" as custom separator',
            '200|100',
            '|',
            true,
        ];

        yield [
            'The " | " as custom separator',
            '200 | 100',
            ' | ',
            true,
        ];

        yield [
            'The "::" as custom separator',
            '200::100',
            '::',
            true,
        ];

        yield [
            'The " :: " as custom separator',
            '200 :: 100',
            ' :: ',
            true,
        ];

        yield [
            'The "." as custom separator',
            '200.100',
            '.',
            true,
        ];

        yield [
            'The " . " as custom separator',
            '200 . 100',
            ' . ',
            true,
        ];

        yield [
            'The "/" as custom separator',
            '200/100',
            '/',
            true,
        ];

        yield [
            'The " / " as custom separator',
            '200 / 100',
            ' / ',
            true,
        ];

        yield [
            'The " : " as custom separator and too much spaces everywhere',
            '    200   :   100    ',
            ' : ',
            true,
        ];
    }

    public function provideStringToClearBeginningSlash(): \Generator
    {
        yield [
            '',
            '',
        ];

        yield [
            '/',
            '',
        ];

        yield [
            '\\',
            '\\',
        ];

        yield [
            '//',
            '/',
        ];

        yield [
            'lorem ipsum',
            'lorem ipsum',
        ];

        yield [
            '1234',
            '1234',
        ];

        yield [
            'lorem/ipsum',
            'lorem/ipsum',
        ];

        yield [
            'lorem / ipsum',
            'lorem / ipsum',
        ];

        yield [
            'lorem\ipsum',
            'lorem\ipsum',
        ];

        yield [
            'lorem \ ipsum',
            'lorem \ ipsum',
        ];

        yield [
            '\lorem ipsum',
            '\lorem ipsum',
        ];

        yield [
            '\ lorem ipsum',
            '\ lorem ipsum',
        ];

        yield [
            'lorem ipsum/',
            'lorem ipsum/',
        ];

        yield [
            'lorem ipsum /',
            'lorem ipsum /',
        ];

        yield [
            '/lorem ipsum',
            'lorem ipsum',
        ];

        yield [
            '/ lorem ipsum',
            ' lorem ipsum',
        ];

        yield [
            '/123 456',
            '123 456',
        ];

        yield [
            '/ 123 456',
            ' 123 456',
        ];

        yield [
            '/lorem 123 ipsum 456',
            'lorem 123 ipsum 456',
        ];

        yield [
            '/ lorem 123 ipsum 456',
            ' lorem 123 ipsum 456',
        ];
    }

    public function provideStringToClearEndingSlash(): \Generator
    {
        yield [
            '',
            '',
        ];

        yield [
            '/',
            '',
        ];

        yield [
            '\\',
            '\\',
        ];

        yield [
            '//',
            '/',
        ];

        yield [
            'lorem ipsum',
            'lorem ipsum',
        ];

        yield [
            '1234',
            '1234',
        ];

        yield [
            'lorem/ipsum',
            'lorem/ipsum',
        ];

        yield [
            'lorem / ipsum',
            'lorem / ipsum',
        ];

        yield [
            'lorem\ipsum',
            'lorem\ipsum',
        ];

        yield [
            'lorem \ ipsum',
            'lorem \ ipsum',
        ];

        yield [
            '\lorem ipsum',
            '\lorem ipsum',
        ];

        yield [
            '\ lorem ipsum',
            '\ lorem ipsum',
        ];

        yield [
            '/lorem ipsum',
            '/lorem ipsum',
        ];

        yield [
            '/ lorem ipsum',
            '/ lorem ipsum',
        ];

        yield [
            'lorem ipsum/',
            'lorem ipsum',
        ];

        yield [
            'lorem ipsum /',
            'lorem ipsum ',
        ];

        yield [
            '123 456/',
            '123 456',
        ];

        yield [
            '123 456 /',
            '123 456 ',
        ];

        yield [
            'lorem 123 ipsum 456/',
            'lorem 123 ipsum 456',
        ];

        yield [
            'lorem 123 ipsum 456 /',
            'lorem 123 ipsum 456 ',
        ];
    }

    /**
     * Provides tax ID and information if it's valid
     *
     * @return \Generator
     */
    public function provideTaxId(): \Generator
    {
        yield [
            '123',
            false,
        ];

        yield [
            '12345',
            false,
        ];

        yield [
            '1122334455',
            false,
        ];

        yield [
            '1234567890',
            false,
        ];

        yield [
            '0987654321',
            false,
        ];

        // Microsoft sp. z o.o.
        yield [
            '5270103391',
            true,
        ];

        // Onet S.A.
        yield [
            '7340009469',
            true,
        ];
    }

    /**
     * Provide value to create slug
     *
     * @return \Generator
     */
    public function provideValueSlug(): \Generator
    {
        yield [
            '',
            '',
        ];

        yield [
            1234,
            '1234',
        ];

        yield [
            '1234',
            '1234',
        ];

        yield [
            '1/2/3/4',
            '1234',
        ];

        yield [
            '1 / 2 / 3 / 4',
            '1-2-3-4',
        ];

        yield [
            'test',
            'test',
        ];

        yield [
            'test test',
            'test-test',
        ];

        yield [
            'lorem ipsum dolor sit',
            'lorem-ipsum-dolor-sit',
        ];

        yield [
            'Lorem ipsum. Dolor sit 12.34 amet.',
            'lorem-ipsum-dolor-sit-1234-amet',
        ];

        yield [
            'Was sind Löwen, Bären, Vögel und Käfer (für die Prüfung)?',
            'was-sind-lowen-baren-vogel-und-kafer-fur-die-prufung',
        ];

        yield [
            'äöü (ÄÖÜ)',
            'aou-aou',
        ];

        yield [
            'Półka dębowa. Kolor: żółędziowy. Wymiary: 80 x 30 cm.',
            'polka-debowa-kolor-zoledziowy-wymiary-80-x-30-cm',
        ];

        yield [
            'ąęółńśżźć (ĄĘÓŁŃŚŻŹĆ)',
            'aeolnszzc-aeolnszzc',
        ];
    }

    /**
     * @param string $htmlAttributes The html attributes to verify
     * @param bool   $expected       Information if attributes are valid
     *
     * @dataProvider provideHtmlAttributes
     */
    public static function testAreValidHtmlAttributes($htmlAttributes, $expected)
    {
        self::assertEquals($expected, Regex::areValidHtmlAttributes($htmlAttributes));
    }

    /**
     * @param mixed $emptyValue Empty value, e.g. ""
     * @dataProvider provideEmptyScalarValue
     */
    public static function testAreValidHtmlAttributesUsingEmptyValue($emptyValue)
    {
        self::assertFalse(Regex::areValidHtmlAttributes($emptyValue));
    }

    /** @dataProvider provideRegularExpressionForArrayFiltering */
    public function testArrayFilterUsingRegularExpression(
        array $array,
        string $arrayColumnKey,
        string $filterExpression,
        array $expected,
    ): void {
        self::assertEquals($expected, Regex::arrayFilter($array, $arrayColumnKey, $filterExpression));
    }

    /** @dataProvider provideClosureForArrayFiltering */
    public function testArrayFilterUsingSimpleExpression(
        array $array,
        string $arrayColumnKey,
        \Closure $filter,
        array $expected,
    ): void {
        self::assertEquals($expected, Regex::arrayFilter($array, $arrayColumnKey, $filter));
    }

    public function testCamelCase2humanReadable()
    {
        self::assertEquals('', Regex::camelCase2humanReadable(''));
        self::assertEquals('lorem', Regex::camelCase2humanReadable('lorem'));

        self::assertEquals($this->simpleText, Regex::camelCase2humanReadable($this->camelCaseText));
        self::assertEquals(ucfirst($this->simpleText), Regex::camelCase2humanReadable($this->camelCaseText, true));
    }

    public function testCamelCase2simpleLowercase()
    {
        self::assertEquals('', Regex::camelCase2simpleLowercase(''));
        self::assertEquals('lorem', Regex::camelCase2simpleLowercase('lorem'));
        self::assertEquals('Lorem', Regex::camelCase2simpleLowercase('Lorem', '', false));
        self::assertEquals('lorem-ipsum-dolor-sit', Regex::camelCase2simpleLowercase($this->camelCaseText, '-'));
        self::assertEquals('lorem-Ipsum-Dolor-Sit', Regex::camelCase2simpleLowercase($this->camelCaseText, '-', false));
    }

    /**
     * @param string $string
     * @param string $expected
     *
     * @dataProvider provideStringToClearBeginningSlash
     */
    public function testClearBeginningSlash(string $string, string $expected): void
    {
        static::assertSame($expected, Regex::clearBeginningSlash($string));
    }

    /**
     * @param string $string
     * @param string $expected
     *
     * @dataProvider provideStringToClearEndingSlash
     */
    public function testClearEndingSlash(string $string, string $expected): void
    {
        static::assertSame($expected, Regex::clearEndingSlash($string));
    }

    public function testConstructor()
    {
        static::assertHasNoConstructor(Regex::class);
    }

    public function testContains()
    {
        self::assertTrue(Regex::contains($this->simpleText, 'ipsum'));
        self::assertFalse(Regex::contains($this->simpleText, 'neque'));

        self::assertFalse(Regex::contains($this->simpleText, '.'));
        self::assertTrue(Regex::contains($this->simpleText, 'l'));
    }

    public function testContainsEntities()
    {
        self::assertFalse(Regex::containsEntities('Lorem ipsum'));
        self::assertTrue(Regex::containsEntities('Lorem ipsum &raquo;'));
    }

    /**
     * @param string $value    Value that should be transformed to slug
     * @param string $expected Expected slug
     *
     * @dataProvider provideValueSlug
     */
    public function testCreateSlug($value, $expected)
    {
        self::assertSame($expected, Regex::createSlug($value));
    }

    public function testEndsWith()
    {
        self::assertFalse(Regex::endsWith($this->simpleText, '\.\.\.'));
        self::assertFalse(Regex::endsWith($this->simpleText, '\.'));
        self::assertTrue(Regex::endsWith($this->simpleText, 't'));
    }

    public function testEndsWithDirectorySeparator()
    {
        // Not provided, default separator
        self::assertTrue(Regex::endsWithDirectorySeparator('my simple text/'));
        self::assertFalse(Regex::endsWithDirectorySeparator('my simple text'));

        // Slash as separator
        $separatorSlash = '/';

        self::assertTrue(Regex::endsWithDirectorySeparator('my simple text/', $separatorSlash));
        self::assertFalse(Regex::endsWithDirectorySeparator('my simple text', $separatorSlash));

        // Backslash as separator
        $separatorBackslash = '\\';

        self::assertTrue(Regex::endsWithDirectorySeparator('my simple text\\', $separatorBackslash));
        self::assertFalse(Regex::endsWithDirectorySeparator('my simple text', $separatorBackslash));
    }

    /**
     * @param string $pattern   Pattern to match
     * @param array  $dataArray The array
     * @param array  $expected  Expected array
     *
     * @dataProvider providePatternForArrayKeys
     */
    public static function testGetArrayValuesByPatternUsingKeys($pattern, array $dataArray, $expected)
    {
        self::assertEquals($expected, Regex::getArrayValuesByPattern($pattern, $dataArray, true));
    }

    /**
     * @param string $pattern   Pattern to match
     * @param array  $dataArray The array
     * @param array  $expected  Expected array
     *
     * @dataProvider providePatternForArrayValues
     */
    public static function testGetArrayValuesByPatternUsingValues($pattern, array $dataArray, $expected)
    {
        self::assertEquals($expected, Regex::getArrayValuesByPattern($pattern, $dataArray));
    }

    public function testGetBundleNamePattern()
    {
        self::assertEquals('/^(([A-Z]{1}[a-z0-9]+)((?2))*)(Bundle)$/', Regex::getBundleNamePattern());
    }

    public function testGetCamelCaseParts()
    {
        $parts = [];
        self::assertEquals($parts, Regex::getCamelCaseParts(''));

        $parts = [
            'lorem',
        ];

        self::assertEquals($parts, Regex::getCamelCaseParts('lorem'));

        $parts = [
            'lorem',
            'Ipsum',
            'Dolor',
            'Sit',
        ];

        self::assertEquals($parts, Regex::getCamelCaseParts($this->camelCaseText));

        $parts = [
            'Lorem',
            'Ipsum',
            'Dolor',
            'Sit',
        ];

        $string = ucfirst($this->camelCaseText); // 'LoremIpsumDolorSit'
        self::assertEquals($parts, Regex::getCamelCaseParts($string));
    }

    public function testGetHtmlAttributePattern()
    {
        self::assertEquals('/([\w-]+)="([\w -]+)"/', Regex::getHtmlAttributePattern());
    }

    public function testGetMoneyPattern()
    {
        self::assertEquals('/^[-+]?\d+([.,]{1}\d*)?$/', Regex::getMoneyPattern());
    }

    public static function testGetUrlPatternWithProtocolRequired()
    {
        $pattern = '|^([a-z]+://)([\da-z.-]+)\.([a-z.]{2,6})(/)?([\w.\-]*)?(\?)?([\w .\-/=&]*)/?$|i';
        self::assertEquals($pattern, Regex::getUrlPattern(true));
    }

    public static function testGetUrlPatternWithoutProtocol()
    {
        $pattern = '|^([a-z]+://)?([\da-z.-]+)\.([a-z.]{2,6})(/)?([\w.\-]*)?(\?)?([\w .\-/=&]*)/?$|i';
        self::assertEquals($pattern, Regex::getUrlPattern());
    }

    /**
     * @param string $color    Color to verify
     * @param string $expected Expected value of color
     *
     * @dataProvider provideColor
     */
    public function testGetValidColorHexValue($color, $expected)
    {
        self::assertEquals($expected, Regex::getValidColorHexValue($color));
    }

    /**
     * @param mixed $emptyValue Empty value, e.g. ""
     * @dataProvider provideColorEmptyValue
     */
    public function testGetValidColorHexValueUsingEmptyValue($emptyValue)
    {
        $this->expectException(IncorrectColorHexLengthException::class);
        Regex::getValidColorHexValue($emptyValue);
    }

    /**
     * @param mixed $emptyValue Empty value, e.g. ""
     * @dataProvider provideColorEmptyValue
     */
    public function testGetValidColorHexValueUsingEmptyValueWithoutException($emptyValue)
    {
        self::assertFalse(Regex::getValidColorHexValue($emptyValue, false));
    }

    /**
     * @param string $incorrectColor Incorrect value of color
     * @dataProvider provideColorIncorrectLength
     */
    public function testGetValidColorHexValueUsingIncorrectValue($incorrectColor)
    {
        $this->expectException(IncorrectColorHexLengthException::class);
        Regex::getValidColorHexValue($incorrectColor);
    }

    /**
     * @param string $incorrectColor Incorrect value of color
     * @dataProvider provideColorIncorrectLength
     */
    public function testGetValidColorHexValueUsingIncorrectValueWithoutException($incorrectColor)
    {
        self::assertFalse(Regex::getValidColorHexValue($incorrectColor, false));
    }

    /**
     * @param string $invalidColor Invalid value of color
     * @dataProvider provideColorInvalidValue
     */
    public function testGetValidColorHexValueUsingInvalidValue($invalidColor)
    {
        $this->expectException(InvalidColorHexValueException::class);
        Regex::getValidColorHexValue($invalidColor);
    }

    /**
     * @param string $invalidColor Invalid value of color
     * @dataProvider provideColorInvalidValue
     */
    public function testGetValidColorHexValueUsingInvalidValueWithoutException($invalidColor)
    {
        self::assertFalse(Regex::getValidColorHexValue($invalidColor, false));
    }

    /**
     * @param string $value    Value to verify
     * @param bool   $expected Information if value is a binary value
     *
     * @dataProvider provideBinaryValue
     */
    public static function testIsBinaryValue($value, $expected)
    {
        self::assertEquals($expected, Regex::isBinaryValue($value));
    }

    /**
     * @param string $description Description of test
     * @param string $fileName
     * @param bool   $expected    Expected result
     *
     * @dataProvider provideFileName
     */
    public function testIsFileName(string $description, string $fileName, bool $expected): void
    {
        static::assertSame($expected, Regex::isFileName($fileName), $description);
    }

    public function testIsLetterOrDigit()
    {
        self::assertTrue(Regex::isLetterOrDigit('a'));
        self::assertTrue(Regex::isLetterOrDigit(10));
        self::assertFalse(Regex::isLetterOrDigit(';'));
    }

    public function testIsQuoted()
    {
        self::assertTrue(Regex::isQuoted('\'lorem ipsum\''));
        self::assertTrue(Regex::isQuoted('"lorem ipsum"'));

        self::assertFalse(Regex::isQuoted('lorem ipsum'));
        self::assertFalse(Regex::isQuoted(new stdClass()));
    }

    public function testIsSetUriParameter()
    {
        $uri = 'www.domain.com/?name=phil&type=4';

        $parameterName = 'type';
        self::assertTrue(Regex::isSetUriParameter($uri, $parameterName));

        $parameterName = 'color';
        self::assertFalse(Regex::isSetUriParameter($uri, $parameterName));
    }

    /**
     * @param string $description Description of test
     * @param string $value       Value to verify
     * @param string $separator   Separator used to split width and height
     * @param bool   $expected    Expected result of verification
     *
     * @dataProvider provideSizeToVerify
     */
    public function testIsSizeValue($description, $value, $separator, $expected)
    {
        self::assertEquals($expected, Regex::isSizeValue($value, $separator), $description);
    }

    /**
     * @param mixed $emptyValue Empty value, e.g. ""
     * @dataProvider provideEmptyScalarValue
     */
    public static function testIsSizeValueUsingEmptyValue($emptyValue)
    {
        self::assertFalse(Regex::isSizeValue($emptyValue));
    }

    public function testIsSubPathOf()
    {
        self::assertFalse(Regex::isSubPathOf('', ''));

        self::assertFalse(Regex::isSubPathOf('', '/my/directory'));
        self::assertFalse(Regex::isSubPathOf('/my/file', ''));
        self::assertFalse(Regex::isSubPathOf('/my/file', '/my/directory'));

        self::assertTrue(Regex::isSubPathOf('/my/directory', '/my/directory'));
        self::assertTrue(Regex::isSubPathOf('/my/directory/', '/my/directory'));
        self::assertTrue(Regex::isSubPathOf('/my/directory', '/my/directory/'));
        self::assertTrue(Regex::isSubPathOf('/my/directory/', '/my/directory/'));

        self::assertTrue(Regex::isSubPathOf('/my/another/directory/another/file', '/my/another/directory'));
    }

    /**
     * @param string $bundleName Full name of bundle to verify, e.g. "MyExtraBundle"
     * @param bool   $expected   Information if it's valid name
     *
     * @dataProvider provideBundleName
     */
    public function testIsValidBundleName($bundleName, $expected)
    {
        self::assertEquals($expected, Regex::isValidBundleName($bundleName));
    }

    /**
     * @param mixed $emptyValue Empty value, e.g. ""
     * @dataProvider provideEmptyScalarValue
     */
    public function testIsValidBundleNameUsingEmptyValue($emptyValue)
    {
        self::assertFalse(Regex::isValidBundleName($emptyValue));
    }

    /**
     * @param string $email    E-mail address to validate / verify
     * @param bool   $expected Information if e-mail is valid
     *
     * @dataProvider provideEmail
     */
    public static function testIsValidEmail($email, $expected)
    {
        self::assertEquals($expected, Regex::isValidEmail($email));
    }

    /**
     * @param mixed $emptyValue Empty value, e.g. ""
     * @dataProvider provideEmptyScalarValue
     */
    public static function testIsValidEmailUsingEmptyValue($emptyValue)
    {
        self::assertFalse(Regex::isValidEmail($emptyValue));
    }

    /**
     * @param string $htmlAttribute The html attribute to verify
     * @param bool   $expected      Information if it's valid attribute
     *
     * @dataProvider provideHtmlAttribute
     */
    public function testIsValidHtmlAttribute($htmlAttribute, $expected)
    {
        self::assertEquals($expected, Regex::isValidHtmlAttribute($htmlAttribute));
    }

    /**
     * @param mixed $emptyValue Empty value, e.g. ""
     * @dataProvider provideEmptyScalarValue
     */
    public function testIsValidHtmlAttributeUsingEmptyValue($emptyValue)
    {
        self::assertFalse(Regex::isValidHtmlAttribute($emptyValue));
    }

    /**
     * @param mixed $value    Value to verify
     * @param bool  $expected Information if given value is a money value
     *
     * @dataProvider provideMoneyValue
     */
    public function testIsValidMoneyValue($value, $expected)
    {
        self::assertEquals($expected, Regex::isValidMoneyValue($value));
    }

    /**
     * @param mixed $emptyValue Empty value, e.g. ""
     * @dataProvider provideEmptyNonMoneyValue
     */
    public function testIsValidMoneyValueUsingEmptyValue($emptyValue)
    {
        self::assertFalse(Regex::isValidMoneyValue($emptyValue));
    }

    public function testIsValidNip()
    {
        self::assertFalse(Regex::isValidNip(''));
        self::assertFalse(Regex::isValidNip(1234));
        self::assertFalse(Regex::isValidNip(1234567890));
        self::assertFalse(Regex::isValidNip(0000000000));
        self::assertFalse(Regex::isValidNip('1234567890'));
        self::assertFalse(Regex::isValidNip('0000000000'));
        self::assertFalse(Regex::isValidNip('abc'));
        self::assertFalse(Regex::isValidNip($this->simpleText));

        self::assertTrue(Regex::isValidNip('7340009469')); // Onet S.A.
        self::assertTrue(Regex::isValidNip('5252530705')); // Facebook Poland sp. z o.o.
    }

    /**
     * @param string $phoneNumber The phone number to validate / verify
     * @param bool   $expected    Information if phone number is valid
     *
     * @dataProvider providePhoneNumber
     */
    public static function testIsValidPhoneNumber($phoneNumber, $expected)
    {
        self::assertEquals($expected, Regex::isValidPhoneNumber($phoneNumber));
    }

    public static function testIsValidPhoneNumberUsingEmptyValue()
    {
        self::assertFalse(Regex::isValidPhoneNumber(''));
        self::assertFalse(Regex::isValidPhoneNumber('   '));
    }

    /**
     * @param string $taxIdString Tax ID (NIP) string
     * @param bool   $expected    Information if tax ID is valid
     *
     * @dataProvider provideTaxId
     */
    public static function testIsValidTaxId($taxIdString, $expected)
    {
        self::assertEquals($expected, Regex::isValidTaxId($taxIdString));
    }

    /**
     * @param mixed $emptyValue Empty value, e.g. ""
     * @dataProvider provideEmptyScalarValue
     */
    public static function testIsValidTaxIdUsingEmptyValue($emptyValue)
    {
        self::assertFalse(Regex::isValidTaxId($emptyValue));
    }

    public function testIsValidUrl()
    {
        $validUrls = [
            'http://php.net',
            'http://php.net/',
            'http://php.net/docs.php',
            'http://php.net/get-involved.php',
            'http://php.net/manual/en/function.preg-match.php',
            'http://domain.com/BigLetters',
            'http://domain.com/Another-Big-Letters',
            'http://domain.com/?a=1&b=c2d',
            'http://domAin.COM/?a=1&B=c2D',
            'http://domain.com/index.php?a=1&b=c2d',
            'http://domain.com/another-page-2.php?a=1&b=c2d',
            'https://domain.com',
            'https://domain.com/',
        ];

        $invalidUrls = [
            '',
            false,
            true,
            0,
            1,
            123,
            '123',
            'http:',
            'http://',
            'http://abc',
            'ftp://def',
        ];

        foreach ($validUrls as $url) {
            self::assertTrue(Regex::isValidUrl($url));
        }

        foreach ($invalidUrls as $url) {
            self::assertFalse(Regex::isValidUrl($url));
        }
    }

    public function testIsWindowsBasedPath()
    {
        self::assertTrue(Regex::isWindowsBasedPath('C:\path\to\directory'));
        self::assertTrue(Regex::isWindowsBasedPath('C:\path\to\file.jpg'));

        self::assertFalse(Regex::isWindowsBasedPath('/path/to/directory'));
        self::assertFalse(Regex::isWindowsBasedPath('/path/to/file.jpg'));
    }

    /**
     * @param array|string $patterns The patterns to match
     * @param string       $subject  The string to check
     * @param bool         $expected Information if given $subject matches given $patterns
     *
     * @dataProvider providePatternsAndSubjectForPregMultiMatch
     */
    public function testPregMultiMatch($patterns, $subject, $expected)
    {
        self::assertEquals($expected, Regex::pregMultiMatch($patterns, $subject));
    }

    /**
     * @param array|string $patterns The patterns to match
     * @param string       $subject  The string to check
     * @param bool         $expected Information if given $subject matches given $patterns
     *
     * @dataProvider providePatternsAndSubjectForPregMultiMatchWhenMustMatchAllPatterns
     */
    public function testPregMultiMatchWhenMustMatchAllPatterns($patterns, $subject, $expected)
    {
        self::assertEquals($expected, Regex::pregMultiMatch($patterns, $subject, true));
    }

    public function testStartsWith()
    {
        $string = 'Lorem ipsum dolor sit amet';

        $beginning = '';
        self::assertFalse(Regex::startsWith($string, $beginning));
        self::assertFalse(Regex::startsWith('', $string));

        $beginning = 'Lor';
        self::assertTrue(Regex::startsWith($string, $beginning));

        $beginning = 'L';
        self::assertTrue(Regex::startsWith($string, $beginning));

        $beginning = 'X';
        self::assertFalse(Regex::startsWith($string, $beginning));

        $string = '1234567890';
        $beginning = '1';
        self::assertTrue(Regex::startsWith($string, $beginning));

        $beginning = ';';
        self::assertFalse(Regex::startsWith($string, $beginning));
    }

    public function testStartsWithDirectorySeparator()
    {
        // Not provided, default separator
        self::assertTrue(Regex::startsWithDirectorySeparator('/my/extra/directory'));
        self::assertFalse(Regex::startsWithDirectorySeparator('my/extra/directory'));

        // Slash as separator
        $separatorSlash = '/';

        self::assertTrue(Regex::startsWithDirectorySeparator('/my/extra/directory', $separatorSlash));
        self::assertFalse(Regex::startsWithDirectorySeparator('my/extra/directory', $separatorSlash));

        // Backslash as separator
        $separatorBackslash = '\\';

        self::assertTrue(Regex::startsWithDirectorySeparator('\my\extra\directory', $separatorBackslash));
        self::assertFalse(Regex::startsWithDirectorySeparator('my\extra\directory', $separatorBackslash));
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->simpleText = 'lorem ipsum dolor sit';
        $simpleUppercase = ucwords($this->simpleText);
        $this->camelCaseText = str_replace(' ', '', lcfirst($simpleUppercase)); // 'loremIpsumDolorSit'
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->simpleText, $this->camelCaseText);
    }
}
