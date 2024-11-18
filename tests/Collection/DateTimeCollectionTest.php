<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Test\Common\Collection;

use DateTime;
use Generator;
use Meritoo\Common\Collection\DateTimeCollection;
use Meritoo\Common\Enums\OopVisibility;
use Meritoo\Common\Test\Base\BaseTestCase;

/**
 * Test case of the collection of DateTime instances
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 *
 * @internal
 * @covers    \Meritoo\Common\Collection\DateTimeCollection
 */
class DateTimeCollectionTest extends BaseTestCase
{
    public function provideDifferentTypesOfElements(): ?Generator
    {
        yield [
            'An empty array',
            [],
            [],
        ];

        yield [
            'Valid elements only',
            [
                new DateTime('2001-01-01'),
                new DateTime('2001-01-02'),
            ],
            [
                new DateTime('2001-01-01'),
                new DateTime('2001-01-02'),
            ],
        ];

        yield [
            'Mixed elements',
            [
                1,
                'test',
                new DateTime('2001-01-01'),
                '',
                [],
                234,
                new DateTime('2001-01-02'),
            ],
            [
                2 => new DateTime('2001-01-01'),
                6 => new DateTime('2001-01-02'),
            ],
        ];
    }

    public function testConstructor(): void
    {
        static::assertConstructorVisibilityAndArguments(
            DateTimeCollection::class,
            OopVisibility::Public,
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
        $collection = new DateTimeCollection($elements);
        static::assertEquals($expectedElements, $collection->toArray(), $description);
    }
}
