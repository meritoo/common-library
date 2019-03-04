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
     * @param BaseType $type         Type of something
     * @param string   $toVerifyType Concrete type to verify (of given instance of type)
     * @param bool     $isCorrect    Expected information if given type is correct
     *
     * @dataProvider provideTypeWithConcreteType
     */
    public function testIsCorrectType(BaseType $type, $toVerifyType, $isCorrect)
    {
        self::assertEquals($isCorrect, $type->isCorrectType($toVerifyType));
    }

    /**
     * Provides type of something for testing the getAll() method
     *
     * @return Generator
     */
    public function provideType()
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
    public function provideTypeWithConcreteType()
    {
        yield[
            new TestEmptyType(),
            null,
            false,
        ];

        yield[
            new TestEmptyType(),
            false,
            false,
        ];

        yield[
            new TestEmptyType(),
            true,
            false,
        ];

        yield[
            new TestEmptyType(),
            '',
            false,
        ];

        yield[
            new TestEmptyType(),
            0,
            false,
        ];

        yield[
            new TestEmptyType(),
            1,
            false,
        ];

        yield[
            new TestEmptyType(),
            'lorem',
            false,
        ];

        yield[
            new TestType(),
            null,
            false,
        ];

        yield[
            new TestType(),
            false,
            false,
        ];

        yield[
            new TestType(),
            true,
            false,
        ];

        yield[
            new TestType(),
            '',
            false,
        ];

        yield[
            new TestType(),
            0,
            false,
        ];

        yield[
            new TestType(),
            1,
            false,
        ];

        yield[
            new TestType(),
            'lorem',
            false,
        ];

        yield[
            new TestType(),
            'test',
            false,
        ];

        yield[
            new TestType(),
            TestType::TEST_1,
            true,
        ];

        yield[
            new TestType(),
            TestType::TEST_2,
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
    const TEST_1 = 'test_1';

    const TEST_2 = 'test_2';
}
