<?php

declare(strict_types=1);

namespace Meritoo\Test\Common\Collection;

use Meritoo\Common\Collection\StringCollection;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Type\OopVisibilityType;

/**
 * Test case of the collection of strings
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 *
 * @internal
 * @covers    \Meritoo\Common\Collection\StringCollection
 */
class StringCollectionTest extends BaseTestCase
{
    public function testConstructor(): void
    {
        static::assertConstructorVisibilityAndArguments(
            StringCollection::class,
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
        $collection = new StringCollection($elements);
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
                1 => 'test',
                2 => '',
                5 => 'test',
            ],
        ];
    }
}
