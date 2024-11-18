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
use Meritoo\Common\Exception\Reflection\NotExistingPropertyException;
use Meritoo\Common\Test\Base\BaseTestCase;
use stdClass;

/**
 * Class NotExistingPropertyExceptionTest
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 *
 * @internal
 * @covers    \Meritoo\Common\Exception\Reflection\NotExistingPropertyException
 */
class NotExistingPropertyExceptionTest extends BaseTestCase
{
    public function provideObjectPropertyAndMessage(): ?Generator
    {
        $template = 'Property \'%s\' does not exist in instance of class \'%s\'. Did you use proper name of property?';

        yield [
            'An empty string as name of property',
            new stdClass(),
            '',
            sprintf($template, '', get_class(new stdClass())),
        ];

        yield [
            'Null as name of property',
            new stdClass(),
            null,
            sprintf($template, '', get_class(new stdClass())),
        ];

        yield [
            'String with spaces as name of property',
            new stdClass(),
            'This is test',
            sprintf($template, 'This is test', get_class(new stdClass())),
        ];

        yield [
            'String without spaces as name of property',
            new stdClass(),
            'ThisIsTest',
            sprintf($template, 'ThisIsTest', get_class(new stdClass())),
        ];
    }

    public function testConstructor(): void
    {
        static::assertConstructorVisibilityAndArguments(
            NotExistingPropertyException::class,
            OopVisibility::Public,
            3
        );
    }

    /**
     * @param string      $description     Description of test
     * @param mixed       $object          Object that should contains given property
     * @param null|string $property        Name of the property
     * @param string      $expectedMessage Expected exception's message
     *
     * @dataProvider provideObjectPropertyAndMessage
     */
    public function testCreate(string $description, $object, ?string $property, string $expectedMessage): void
    {
        $exception = NotExistingPropertyException::create($object, $property);
        static::assertSame($expectedMessage, $exception->getMessage(), $description);
    }
}
