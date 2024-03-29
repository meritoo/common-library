<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Test\Common\Exception\Base;

use Meritoo\Common\Exception\Base\UnknownTypeException;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Type\Base\BaseType;
use Meritoo\Common\Type\OopVisibilityType;

/**
 * Test case of the exception used while type of something is unknown
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 *
 * @internal
 * @covers    \Meritoo\Common\Exception\Base\UnknownTypeException
 */
class UnknownTypeExceptionTest extends BaseTestCase
{
    public function testConstructorVisibilityAndArguments()
    {
        static::assertConstructorVisibilityAndArguments(UnknownTypeException::class, OopVisibilityType::IS_PUBLIC, 3);
    }

    public function testTheException()
    {
        $this->expectException(UnknownTestTypeException::class);
        self::assertEmpty((new TestService())->getTranslatedType('test_3'));
    }

    public function testWithoutException()
    {
        self::assertEquals('Test 2', (new TestService())->getTranslatedType('test_2'));
    }
}

/**
 * Type of something (for testing purposes)
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class TestType extends BaseType
{
    public const TEST_1 = 'test_1';

    public const TEST_2 = 'test_2';
}

/**
 * An exception used while type of something is unknown (for testing purposes)
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 *
 * @internal
 * @covers    \Meritoo\Common\Exception\Base\UnknownTypeException
 */
class UnknownTestTypeException extends UnknownTypeException
{
    /**
     * Creates exception
     *
     * @param string $unknownType The unknown type of something (for testing purposes)
     * @return UnknownTestTypeException
     */
    public static function createException(string $unknownType): UnknownTestTypeException
    {
        return parent::create($unknownType, new TestType(), 'type of something used for testing');
    }
}

/**
 * Service used together with type of something (for testing purposes)
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class TestService
{
    /**
     * Returns translated type (for testing purposes)
     *
     * @param string $type Type of something (for testing purposes)
     * @return string
     * @throws UnknownTestTypeException
     */
    public function getTranslatedType(string $type): string
    {
        if (TestType::isCorrectType($type)) {
            return ucfirst(str_replace('_', ' ', $type));
        }

        throw UnknownTestTypeException::createException($type);
    }
}
