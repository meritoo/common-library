<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Test\Common\Type\Base;

use Generator;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Type\Base\BaseType;

/**
 * Test case of the base / abstract type of something
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 *
 * @internal
 * @covers    \Meritoo\Common\Type\Base\BaseType
 */
class BaseTypeTest extends BaseTestCase
{
    public function testConstructor()
    {
        static::assertHasNoConstructor(BaseType::class);
    }

    /**
     * @param BaseType $type          Type of something
     * @param array    $expectedTypes Expected concrete types of given instance of type
     *
     * @dataProvider provideType
     */
    public function testGetAll(BaseType $type, array $expectedTypes)
    {
        $all = $type->getAll();
        self::assertEquals($expectedTypes, $all);
    }

    /**
     * @param string $toVerifyType Concrete type to verify
     * @param bool   $isCorrect    Expected information if given type is correct
     *
     * @dataProvider provideTypeToVerifyUsingTestEmptyType
     */
    public function testIsCorrectTypeUsingTestEmptyType(?string $toVerifyType, bool $isCorrect): void
    {
        self::assertEquals($isCorrect, TestEmptyType::isCorrectType($toVerifyType));
    }

    /**
     * @param string $toVerifyType Concrete type to verify
     * @param bool   $isCorrect    Expected information if given type is correct
     *
     * @dataProvider provideTypeToVerifyUsingTestType
     */
    public function testIsCorrectTypeUsingTestType(?string $toVerifyType, bool $isCorrect): void
    {
        self::assertEquals($isCorrect, TestType::isCorrectType($toVerifyType));
    }

    /**
     * Provides type of something for testing the getAll() method
     *
     * @return Generator
     */
    public function provideType(): ?Generator
    {
        yield[
            new TestEmptyType(),
            [],
        ];

        yield[
            new TestType(),
            [
                'TEST_1' => TestType::TEST_1,
                'TEST_2' => TestType::TEST_2,
            ],
        ];
    }

    /**
     * Provides type of something for testing the isCorrectType() method
     *
     * @return Generator
     */
    public function provideTypeToVerifyUsingTestEmptyType(): ?Generator
    {
        yield[
            null,
            false,
        ];

        yield[
            'null',
            false,
        ];

        yield[
            'false',
            false,
        ];

        yield[
            'true',
            false,
        ];

        yield[
            '',
            false,
        ];

        yield[
            '0',
            false,
        ];

        yield[
            '1',
            false,
        ];

        yield[
            'lorem',
            false,
        ];
    }

    /**
     * Provides type of something for testing the isCorrectType() method
     *
     * @return Generator
     */
    public function provideTypeToVerifyUsingTestType(): ?Generator
    {
        yield[
            null,
            false,
        ];

        yield[
            'null',
            false,
        ];

        yield[
            'false',
            false,
        ];

        yield[
            'true',
            false,
        ];

        yield[
            '',
            false,
        ];

        yield[
            '0',
            false,
        ];

        yield[
            '1',
            false,
        ];

        yield[
            'lorem',
            false,
        ];

        yield[
            'test',
            false,
        ];

        yield[
            'test_1',
            true,
        ];

        yield[
            'test_2',
            true,
        ];
    }
}

/**
 * Empty type of something used for testing
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class TestEmptyType extends BaseType
{
}

/**
 * Type of something used for testing
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class TestType extends BaseType
{
    public const TEST_1 = 'test_1';

    public const TEST_2 = 'test_2';
}
