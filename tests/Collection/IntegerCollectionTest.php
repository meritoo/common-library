<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Test\Common\Collection;

use Meritoo\Common\Collection\IntegerCollection;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Type\OopVisibilityType;

/**
 * Test case of the collection of integers
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 *
 * @internal
 * @covers    \Meritoo\Common\Collection\IntegerCollection
 */
class IntegerCollectionTest extends BaseTestCase
{
    public function testConstructor(): void
    {
        static::assertConstructorVisibilityAndArguments(
            IntegerCollection::class,
            OopVisibilityType::IS_PUBLIC,
            1
        );
    }

    /**
     * @param string $description
     * @param array  $elements
     * @param array  $expectedElements
     *
     * @dataProvider provideDifferentTypesOfElements
     */
    public function testCreateWithDifferentTypesOfElements(
        string $description,
        array $elements,
        array $expectedElements
    ): void {
        $collection = new IntegerCollection($elements);
        static::assertSame($expectedElements, $collection->toArray(), $description);
    }

    public function provideDifferentTypesOfElements(): ?\Generator
    {
        yield[
            'An empty array',
            [],
            [],
        ];

        yield[
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

        yield[
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
}
