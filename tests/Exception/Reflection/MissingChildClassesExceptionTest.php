<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Test\Common\Exception\Reflection;

use Generator;
use Meritoo\Common\Exception\Reflection\MissingChildClassesException;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Traits\Test\Base\BaseTestCaseTrait;
use Meritoo\Common\Type\Base\BaseType;
use Meritoo\Common\Type\OopVisibilityType;
use Meritoo\Common\Utilities\Reflection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;
use stdClass;

#[CoversClass(MissingChildClassesException::class)]
#[UsesClass(Reflection::class)]
#[UsesClass(BaseTestCaseTrait::class)]
#[UsesClass(BaseType::class)]
class MissingChildClassesExceptionTest extends BaseTestCase
{
    public static function provideParentClass(): Generator
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
            OopVisibilityType::IS_PUBLIC,
            3
        );
    }

    #[DataProvider('provideParentClass')]
    public function testCreate(string|stdClass $parentClass, string $expectedMessage): void
    {
        $exception = MissingChildClassesException::create($parentClass);
        static::assertSame($expectedMessage, $exception->getMessage());
    }
}
