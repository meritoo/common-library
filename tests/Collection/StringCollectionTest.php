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
use Meritoo\Common\Collection\StringCollection;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Type\Base\BaseType;
use Meritoo\Common\Type\OopVisibilityType;
use Meritoo\Common\Utilities\Reflection;
use Meritoo\Test\Common\Traits\Test\Base\BaseTestCaseTrait\SimpleTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(StringCollection::class)]
#[CoversClass(BaseType::class)]
#[CoversClass(Reflection::class)]
#[CoversClass(SimpleTestCase::class)]
#[CoversClass(BaseCollection::class)]
class StringCollectionTest extends BaseTestCase
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
                '1',
                'test',
                '',
            ],
            [
                '1',
                'test',
                '',
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
                1 => 'test',
                2 => '',
                5 => 'test',
            ],
        ];
    }

    public function testConstructor(): void
    {
        static::assertConstructorVisibilityAndArguments(
            StringCollection::class,
            OopVisibilityType::IS_PUBLIC,
            1
        );
    }

    /**
     * @dataProvider provideDifferentTypesOfElements
     */
    public function testCreateWithDifferentTypesOfElements(
        string $description,
        array $elements,
        array $expectedElements
    ): void {
        $collection = new StringCollection($elements);
        static::assertSame($expectedElements, $collection->toArray(), $description);
    }
}
