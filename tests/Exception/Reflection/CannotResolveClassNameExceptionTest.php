<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Test\Common\Exception\Reflection;

use Generator;
use Meritoo\Common\Exception\Reflection\CannotResolveClassNameException;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Type\OopVisibilityType;
use stdClass;

/**
 * Test case of an exception used while name of class or trait cannot be resolved
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 *
 * @internal
 * @covers    \Meritoo\Common\Exception\Reflection\CannotResolveClassNameException
 */
class CannotResolveClassNameExceptionTest extends BaseTestCase
{
    public function testConstructorVisibilityAndArguments(): void
    {
        static::assertConstructorVisibilityAndArguments(
            CannotResolveClassNameException::class,
            OopVisibilityType::IS_PUBLIC,
            3
        );
    }

    public function testCreateUsingDefaults(): void
    {
        $exception = CannotResolveClassNameException::create(stdClass::class);
        $expectedMessage = 'Name of class from given \'string\' stdClass cannot be resolved. Is there everything ok?';

        static::assertSame($expectedMessage, $exception->getMessage());
    }

    /**
     * @param string $source          Source of name of the class or trait
     * @param bool   $forClass        (optional) If is set to true, message of this exception for class is prepared.
     *                                Otherwise - for trait.
     * @param string $expectedMessage Expected exception's message
     *
     * @dataProvider provideClassName
     */
    public function testCreate(string $source, bool $forClass, string $expectedMessage): void
    {
        $exception = CannotResolveClassNameException::create($source, $forClass);
        static::assertSame($expectedMessage, $exception->getMessage());
    }

    /**
     * Provides source of the class's / trait's name, information if message of this exception should be prepared for
     * class and the expected exception's message
     *
     * @return Generator
     */
    public function provideClassName(): Generator
    {
        yield[
            'Not\Existing\Class',
            true,
            'Name of class from given \'string\' Not\Existing\Class cannot be resolved. Is there everything ok?',
        ];

        yield[
            'Not\Existing\Trait',
            false,
            'Name of trait from given \'string\' Not\Existing\Trait cannot be resolved. Is there everything ok?',
        ];

        yield[
            stdClass::class,
            true,
            'Name of class from given \'string\' stdClass cannot be resolved. Is there everything ok?',
        ];
    }
}
