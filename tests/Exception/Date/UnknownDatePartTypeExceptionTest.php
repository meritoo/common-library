<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Test\Common\Exception\Date;

use Generator;
use Meritoo\Common\Exception\Base\UnknownTypeException;
use Meritoo\Common\Exception\Type\UnknownDatePartTypeException;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Traits\Test\Base\BaseTestCaseTrait;
use Meritoo\Common\Type\Base\BaseType;
use Meritoo\Common\Type\DatePartType;
use Meritoo\Common\Type\OopVisibilityType;
use Meritoo\Common\Utilities\Arrays;
use Meritoo\Common\Utilities\Reflection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(UnknownDatePartTypeException::class)]
#[UsesClass(BaseTestCaseTrait::class)]
#[UsesClass(BaseType::class)]
#[UsesClass(Arrays::class)]
#[UsesClass(Reflection::class)]
#[UsesClass(UnknownTypeException::class)]
class UnknownDatePartTypeExceptionTest extends BaseTestCase
{
    /**
     * Provides type of date part, incorrect value and expected exception's message
     */
    public static function provideDatePartAndValue(): Generator
    {
        $template = 'The \'%s\' type of date part (with value %s) is unknown. Probably doesn\'t exist or there is a'
            .' typo. You should use one of these types: day, hour, minute, month, second, year.';

        yield [
            DatePartType::DAY,
            '44',
            sprintf($template, DatePartType::DAY, '44'),
        ];

        yield [
            DatePartType::MONTH,
            '22',
            sprintf($template, DatePartType::MONTH, '22'),
        ];

        yield [
            DatePartType::MINUTE,
            '77',
            sprintf($template, DatePartType::MINUTE, '77'),
        ];
    }

    public function testConstructorVisibilityAndArguments(): void
    {
        static::assertConstructorVisibilityAndArguments(UnknownDatePartTypeException::class, OopVisibilityType::IS_PUBLIC, 3);
    }

    #[DataProvider('provideDatePartAndValue')]
    public function testMessage(string $unknownDatePart, string $value, string $expectedMessage): void
    {
        $exception = UnknownDatePartTypeException::createException($unknownDatePart, $value);
        static::assertSame($expectedMessage, $exception->getMessage());
    }
}
