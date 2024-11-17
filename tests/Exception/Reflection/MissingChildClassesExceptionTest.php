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
use Meritoo\Common\Exception\Reflection\MissingChildClassesException;
use Meritoo\Common\Test\Base\BaseTestCase;
use stdClass;

/**
 * Test case of an exception used while given class has no child classes
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 *
 * @internal
 * @covers    \Meritoo\Common\Exception\Reflection\MissingChildClassesException
 */
class MissingChildClassesExceptionTest extends BaseTestCase
{
    public function provideParentClass(): ?Generator
    {
        $template = 'The \'%s\' class requires one child class at least who will extend her (maybe is an abstract'
            .' class), but the child classes are missing. Did you forget to extend this class?';

        yield [
            MissingChildClassesException::class,
            sprintf($template, MissingChildClassesException::class),
        ];

        yield [
            new stdClass(),
            sprintf($template, stdClass::class),
        ];
    }

    public function testConstructorVisibilityAndArguments(): void
    {
        static::assertConstructorVisibilityAndArguments(
            MissingChildClassesException::class,
            OopVisibility::Public,
            3
        );
    }

    /**
     * @dataProvider provideParentClass
     */
    public function testCreate($parentClass, string $expectedMessage): void
    {
        $exception = MissingChildClassesException::create($parentClass);
        static::assertSame($expectedMessage, $exception->getMessage());
    }
}
