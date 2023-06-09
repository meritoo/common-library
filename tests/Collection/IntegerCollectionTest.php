<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Test\Common\Collection;

use Generator;
use Meritoo\Common\Collection\BaseCollection;
use Meritoo\Common\Collection\IntegerCollection;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Type\Base\BaseType;
use Meritoo\Common\Type\OopVisibilityType;
use Meritoo\Common\Utilities\Reflection;
use Meritoo\Test\Common\Traits\Test\Base\BaseTestCaseTrait\SimpleTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(IntegerCollection::class)]
#[UsesClass(BaseCollection::class)]
#[UsesClass(BaseType::class)]
#[UsesClass(Reflection::class)]
#[UsesClass(SimpleTestCase::class)]
class IntegerCollectionTest extends BaseTestCase
{
    public static function provideDifferentTypesOfElements(): Generator
    {
        yield [
            'An empty array',
            [],
            [],
        ];

        yield [
            'Valid elements only',
            [
                1,
                2,
                3,
            ],
            [
                1,
                2,
                3,
            ],
        ];

        yield [
            'Mixed elements',
            [
                1,
                'test',
                '',
                [],
                234,
                'test',
            ],
            [
                0 => 1,
                4 => 234,
            ],
        ];
    }

    public function testConstructor(): void
    {
        static::assertConstructorVisibilityAndArguments(
            IntegerCollection::class,
            OopVisibilityType::IS_PUBLIC,
            1
        );
    }

    #[DataProvider('provideDifferentTypesOfElements')]
    public function testCreateWithDifferentTypesOfElements(
        string $description,
        array $elements,
        array $expectedElements
    ): void {
        $collection = new IntegerCollection($elements);
        static::assertSame($expectedElements, $collection->toArray(), $description);
    }
}
