<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Test\Exception\Date;

use Generator;
use Meritoo\Common\Exception\Type\UnknownDatePartTypeException;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Type\DatePartType;
use Meritoo\Common\Type\OopVisibilityType;

/**
 * Test case of an exception used while type of date part, e.g. "year", is unknown
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class UnknownDatePartTypeExceptionTest extends BaseTestCase
{
    public function testConstructorVisibilityAndArguments()
    {
        static::assertConstructorVisibilityAndArguments(UnknownDatePartTypeException::class, OopVisibilityType::IS_PUBLIC, 3);
    }

    /**
     * @param string $unknownDatePart Type of date part, e.g. "year". One of DatePartType class constants.
     * @param string $value           Incorrect value
     * @param string $expectedMessage Expected exception's message
     *
     * @dataProvider provideDatePartAndValue
     */
    public function testMessage($unknownDatePart, $value, $expectedMessage)
    {
        $exception = UnknownDatePartTypeException::createException($unknownDatePart, $value);
        static::assertEquals($expectedMessage, $exception->getMessage());
    }

    /**
     * Provides type of date part, incorrect value and expected exception's message
     *
     * @return Generator
     */
    public function provideDatePartAndValue()
    {
        $template = 'The \'%s\' type of date part (with value %s) is unknown. Probably doesn\'t exist or there is a'
            . ' typo. You should use one of these types: day, hour, minute, month, second, year.';

        yield[
            DatePartType::DAY,
            '44',
            sprintf($template, DatePartType::DAY, '44'),
        ];

        yield[
            DatePartType::MONTH,
            '22',
            sprintf($template, DatePartType::MONTH, '22'),
        ];

        yield[
            DatePartType::MINUTE,
            '77',
            sprintf($template, DatePartType::MINUTE, '77'),
        ];
    }
}
