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

/**
 * Test case of an exception used while name of class or trait cannot be resolved
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class CannotResolveClassNameExceptionTest extends BaseTestCase
{
    public function testConstructorVisibilityAndArguments()
    {
        static::assertConstructorVisibilityAndArguments(CannotResolveClassNameException::class, OopVisibilityType::IS_PUBLIC, 3);
    }

    /**
     * @param array|object|string $source          Source of the class's / trait's name. It can be an array of objects,
     *                                             namespaces, object or namespace.
     * @param bool                $forClass        If is set to true, message of this exception for class is prepared.
     *                                             Otherwise - for trait.
     * @param string              $expectedMessage Expected exception's message
     *
     * @dataProvider provideClassName
     */
    public function testConstructorMessage($source, $forClass, $expectedMessage)
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
    public function provideClassName()
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
            [
                new \stdClass(),
                new \stdClass(),
            ],
            true,
            'Name of class from given \'array\' cannot be resolved. Is there everything ok?',
        ];
    }
}
