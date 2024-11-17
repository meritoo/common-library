<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Test\Common\Exception\Reflection;

use Generator;
use Meritoo\Common\Enums\OopVisibility;
use Meritoo\Common\Exception\Reflection\TooManyChildClassesException;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Test\Common\ValueObject\AddressTest;
use stdClass;

/**
 * Test case of an exception used while given class has more than one child class
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 *
 * @internal
 * @covers    \Meritoo\Common\Exception\Reflection\TooManyChildClassesException
 */
class TooManyChildClassesExceptionTest extends BaseTestCase
{
    /**
     * Provides name of class that has more than one child class, but it shouldn't, child classes, and expected
     * exception's message
     *
     * @return Generator
     */
    public function provideParentAndChildClasses(): ?Generator
    {
        yield [
            BaseTestCase::class,
            [
                stdClass::class,
                AddressTest::class,
            ],
            "The Meritoo\Common\Test\Base\BaseTestCase class requires one child class at most who will extend her,"
            ." but more than one child class was found:\n"
            ."- stdClass\n"
            ."- Meritoo\Test\Common\ValueObject\AddressTest\n"
            ."\n"
            ."Why did you create more than one classes that extend Meritoo\Common\Test\Base\BaseTestCase class?",
        ];

        yield [
            TooManyChildClassesException::class,
            [
                stdClass::class,
            ],
            "The Meritoo\Common\Exception\Reflection\TooManyChildClassesException class requires one child class at most who will extend her,"
            ." but more than one child class was found:\n"
            ."- stdClass\n"
            ."\n"
            ."Why did you create more than one classes that extend Meritoo\Common\Exception\Reflection\TooManyChildClassesException class?",
        ];
    }

    public function testConstructor(): void
    {
        static::assertConstructorVisibilityAndArguments(
            TooManyChildClassesException::class,
            OopVisibility::Public,
            3
        );
    }

    /** @dataProvider provideParentAndChildClasses */
    public function testCreate(object|string $parentClass, array $childClasses, string $expectedMessage): void
    {
        $exception = TooManyChildClassesException::create($parentClass, $childClasses);
        static::assertSame($expectedMessage, $exception->getMessage());
    }
}
