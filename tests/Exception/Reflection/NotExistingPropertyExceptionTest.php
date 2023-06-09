<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Test\Common\Exception\Reflection;

use Generator;
use Meritoo\Common\Exception\Reflection\NotExistingPropertyException;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Traits\Test\Base\BaseTestCaseTrait;
use Meritoo\Common\Type\Base\BaseType;
use Meritoo\Common\Type\OopVisibilityType;
use Meritoo\Common\Utilities\Reflection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use stdClass;

#[CoversClass(NotExistingPropertyException::class)]
#[UsesClass(BaseTestCaseTrait::class)]
#[UsesClass(BaseType::class)]
#[UsesClass(Reflection::class)]
class NotExistingPropertyExceptionTest extends BaseTestCase
{
    public static function provideObjectPropertyAndMessage(): Generator
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
            OopVisibilityType::IS_PUBLIC,
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
