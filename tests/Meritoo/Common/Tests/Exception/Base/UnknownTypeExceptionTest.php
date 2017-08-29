<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Tests\Exception\Base;

use Meritoo\Common\Exception\Base\UnknownTypeException;
use Meritoo\Common\Type\Base\BaseType;

/**
 * Tests of the exception used while type of something is unknown
 *
 * @author    Krzysztof Niziol <krzysztof.niziol@meritoo.pl>
 * @copyright Meritoo.pl
 */
class UnknownTypeExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testWithoutException()
    {
        self::assertEquals('Test 2', (new TestService())->getTranslatedType('test_2'));
    }

    public function testTheException()
    {
        $this->expectException(UnknownTestTypeException::class);
        self::assertEmpty((new TestService())->getTranslatedType('test_3'));
    }
}

/**
 * Type of something (for testing purposes)
 *
 * @author    Krzysztof Niziol <krzysztof.niziol@meritoo.pl>
 * @copyright Meritoo.pl
 */
class TestType extends BaseType
{
    const TEST_1 = 'test_1';

    const TEST_2 = 'test_2';
}

/**
 * An exception used while type of something is unknown (for testing purposes)
 *
 * @author    Krzysztof Niziol <krzysztof.niziol@meritoo.pl>
 * @copyright Meritoo.pl
 */
class UnknownTestTypeException extends UnknownTypeException
{
    /**
     * Class constructor
     *
     * @param int|string $unknownType The unknown type of something (for testing purposes)
     */
    public function __construct($unknownType)
    {
        parent::__construct($unknownType, new TestType(), 'type of something used for testing');
    }
}

/**
 * Service used together with type of something (for testing purposes)
 *
 * @author    Krzysztof Niziol <krzysztof.niziol@meritoo.pl>
 * @copyright Meritoo.pl
 */
class TestService
{
    /**
     * Returns translated type (for testing purposes)
     *
     * @param string $type Type of something (for testing purposes)
     * @return string
     *
     * @throws UnknownTestTypeException
     */
    public function getTranslatedType($type)
    {
        if ((new TestType())->isCorrectType($type)) {
            return ucfirst(str_replace('_', ' ', $type));
        }

        throw new UnknownTestTypeException($type);
    }
}
