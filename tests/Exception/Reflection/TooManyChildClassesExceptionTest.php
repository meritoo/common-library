<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Test\Common\Exception\Reflection;

use Generator;
use Meritoo\Common\Exception\Reflection\TooManyChildClassesException;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Type\OopVisibilityType;
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
        $template = "The '%s' class requires one child class at most who will extend her, but more than one child"
            ." class was found:\n- %s\n\nWhy did you create more than one classes that extend '%s' class?";

        yield [
            BaseTestCase::class,
            [
                stdClass::class,
                OopVisibilityType::class,
            ],
            sprintf($template, BaseTestCase::class, implode("\n- ", [
                stdClass::class,
                OopVisibilityType::class,
            ]), BaseTestCase::class),
        ];

        yield [
            TooManyChildClassesException::class,
            [
                stdClass::class,
            ],
            sprintf($template, TooManyChildClassesException::class, implode("\n- ", [stdClass::class]), TooManyChildClassesException::class),
        ];
    }

    public function testConstructor(): void
    {
        static::assertConstructorVisibilityAndArguments(
            TooManyChildClassesException::class,
            OopVisibilityType::IS_PUBLIC,
            3
        );
    }

    /**
     * @param array|object|string $parentClass     Class that has more than one child class, but it shouldn't. An array
     *                                             of objects, strings, object or string.
     * @param array               $childClasses    Child classes
     * @param string              $expectedMessage Expected exception's message
     *
     * @dataProvider provideParentAndChildClasses
     */
    public function testCreate($parentClass, array $childClasses, string $expectedMessage): void
    {
        $exception = TooManyChildClassesException::create($parentClass, $childClasses);
        static::assertSame($expectedMessage, $exception->getMessage());
    }
}
