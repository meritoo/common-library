<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Test\Common\Exception\Reflection;

use Generator;
use Meritoo\Common\Exception\Reflection\ClassWithoutConstructorException;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Traits\Test\Base\BaseTestCaseTrait;
use Meritoo\Common\Type\Base\BaseType;
use Meritoo\Common\Type\OopVisibilityType;
use Meritoo\Common\Utilities\Arrays;
use Meritoo\Common\Utilities\Reflection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(ClassWithoutConstructorException::class)]
#[UsesClass(BaseTestCaseTrait::class)]
#[UsesClass(BaseType::class)]
#[UsesClass(Reflection::class)]
class ClassWithoutConstructorExceptionTest extends BaseTestCase
{
    public static function provideClassName(): Generator
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
            OopVisibilityType::IS_PUBLIC,
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
