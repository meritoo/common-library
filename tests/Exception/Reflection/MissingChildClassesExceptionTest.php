<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Test\Exception\Reflection;

use Generator;
use Meritoo\Common\Exception\Reflection\MissingChildClassesException;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Type\OopVisibilityType;

/**
 * Test case of an exception used while given class has no child classes
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class MissingChildClassesExceptionTest extends BaseTestCase
{
    public function testConstructorVisibilityAndArguments()
    {
        static::assertConstructorVisibilityAndArguments(MissingChildClassesException::class, OopVisibilityType::IS_PUBLIC, 3);
    }

    /**
     * @param array|object|string $parentClass     Class that hasn't child classes, but it should. An array of objects,
     *                                             strings, object or string.
     * @param string              $expectedMessage Expected exception's message
     *
     * @dataProvider provideParentClass
     */
    public function testConstructorMessage($parentClass, $expectedMessage)
    {
        $exception = MissingChildClassesException::create($parentClass);
        static::assertEquals($expectedMessage, $exception->getMessage());
    }

    /**
     * Provides name of class that hasn't child classes, but it should, and expected exception's message
     *
     * @return Generator
     */
    public function provideParentClass()
    {
        $template = 'The \'%s\' class requires one child class at least who will extend her (maybe is an abstract'
            . ' class), but the child classes are missing. Did you forget to extend this class?';

        yield[
            MissingChildClassesException::class,
            sprintf($template, MissingChildClassesException::class),
        ];

        yield[
            [
                new \stdClass(),
                new \stdClass(),
            ],
            sprintf($template, \stdClass::class),
        ];
    }
}
