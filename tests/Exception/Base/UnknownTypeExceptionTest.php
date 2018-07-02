<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Test\Exception\Base;

use Meritoo\Common\Exception\Base\UnknownTypeException;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Type\Base\BaseType;
use Meritoo\Common\Type\OopVisibilityType;

/**
 * Test case of the exception used while type of something is unknown
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class UnknownTypeExceptionTest extends BaseTestCase
{
    public function testConstructorVisibilityAndArguments()
    {
        static::assertConstructorVisibilityAndArguments(UnknownTestTypeException::class, OopVisibilityType::IS_PUBLIC, 3);
    }

    public function testWithoutException()
    {
        self::assertEquals('Test 2', (new TestService())->getTranslatedType('test_2'));
    }

    public function testTheException()
    {
        $this->setExpectedException(UnknownTestTypeException::class);
        self::assertEmpty((new TestService())->getTranslatedType('test_3'));
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
    const TEST_1 = 'test_1';

    const TEST_2 = 'test_2';
}

/**
 * An exception used while type of something is unknown (for testing purposes)
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class UnknownTestTypeException extends UnknownTypeException
{
    /**
     * Creates exception
     *
     * @param string $unknownType The unknown type of something (for testing purposes)
     * @return UnknownTestTypeException
     */
    public static function createException($unknownType)
    {
        /* @var UnknownTestTypeException $exception */
        $exception = parent::create($unknownType, new TestType(), 'type of something used for testing');

        return $exception;
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
     * @throws UnknownTestTypeException
     * @return string
     */
    public function getTranslatedType($type)
    {
        if ((new TestType())->isCorrectType($type)) {
            return ucfirst(str_replace('_', ' ', $type));
        }

        throw new UnknownTestTypeException($type);
    }
}
