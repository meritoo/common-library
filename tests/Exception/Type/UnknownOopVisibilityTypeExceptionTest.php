<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Test\Common\Exception\Type;

use Generator;
use Meritoo\Common\Exception\Type\UnknownOopVisibilityTypeException;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Type\OopVisibilityType;

/**
 * Test case of an exception used while the visibility of a property, a method or (as of PHP 7.1.0) a constant is
 * unknown
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 *
 * @internal
 * @covers    \Meritoo\Common\Exception\Type\UnknownOopVisibilityTypeException
 */
class UnknownOopVisibilityTypeExceptionTest extends BaseTestCase
{
    /**
     * Provides path of the empty file and expected exception's message
     *
     * @return Generator
     */
    public function provideUnknownType(): Generator
    {
        $allTypes = (new OopVisibilityType())->getAll();

        $template = 'The \'%s\' type of OOP-related visibility is unknown. Probably doesn\'t exist or there is a typo.'
            .' You should use one of these types: %s.';

        yield [
            '',
            sprintf($template, '', implode(', ', $allTypes)),
        ];

        yield [
            123,
            sprintf($template, 123, implode(', ', $allTypes)),
        ];
    }

    /**
     * @param string $unknownType     Unknown OOP-related visibility
     * @param string $expectedMessage Expected exception's message
     *
     * @dataProvider provideUnknownType
     */
    public function testConstructorMessage($unknownType, $expectedMessage): void
    {
        $exception = UnknownOopVisibilityTypeException::createException($unknownType);
        static::assertSame($expectedMessage, $exception->getMessage());
    }

    public function testConstructorVisibilityAndArguments(): void
    {
        static::assertConstructorVisibilityAndArguments(
            UnknownOopVisibilityTypeException::class,
            OopVisibilityType::IS_PUBLIC,
            3
        );
    }
}
