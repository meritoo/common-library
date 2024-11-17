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
use Meritoo\Common\Exception\Reflection\ClassWithoutConstructorException;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Utilities\Arrays;

/**
 * Test case of an exception used while given class hasn't constructor
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 *
 * @internal
 * @covers    \Meritoo\Common\Exception\Reflection\ClassWithoutConstructorException
 */
class ClassWithoutConstructorExceptionTest extends BaseTestCase
{
    public function provideClassName(): Generator
    {
        $template = 'Oops, class \'%s\' hasn\'t constructor. Did you use proper class?';

        yield [
            'An empty name of class',
            '',
            sprintf($template, ''),
        ];

        yield [
            'The Arrays class',
            Arrays::class,
            sprintf($template, Arrays::class),
        ];
    }

    public function testConstructor(): void
    {
        static::assertConstructorVisibilityAndArguments(
            ClassWithoutConstructorException::class,
            OopVisibility::Public,
            3
        );
    }

    /**
     * @param string $description     Description of test case
     * @param string $className       Fully-qualified name of class that hasn't constructor
     * @param string $expectedMessage Expected exception's message
     *
     * @dataProvider provideClassName
     */
    public function testCreate(string $description, string $className, string $expectedMessage): void
    {
        $exception = ClassWithoutConstructorException::create($className);
        static::assertSame($expectedMessage, $exception->getMessage(), $description);
    }
}
