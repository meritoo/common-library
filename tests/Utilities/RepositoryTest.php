<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Test\Common\Utilities;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\OrderBy;
use Doctrine\ORM\QueryBuilder;
use Generator;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Utilities\Repository;
use Meritoo\Test\Common\Utilities\Repository\Sortable;
use stdClass;

/**
 * Test case of the useful methods for repository
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class RepositoryTest extends BaseTestCase
{
    public function testConstructor()
    {
        static::assertHasNoConstructor(Repository::class);
    }

    public function testReplenishPositionsWithoutItems()
    {
        $items = [];
        Repository::replenishPositions($items);

        static::assertSame([], $items);
    }

    public function testReplenishPositionsUsingNotSortableObjects()
    {
        $before = [
            new stdClass(),
            new stdClass(),
            new stdClass(),
        ];

        $after = [
            new stdClass(),
            new stdClass(),
            new stdClass(),
        ];

        /*
         * Using defaults
         */
        Repository::replenishPositions($before);
        static::assertEquals($before, $after);

        /*
         * Place items at the top
         */
        Repository::replenishPositions($before, false);
        static::assertEquals($before, $after);

        /*
         * Set positions even there is no extreme position (at the end)
         */
        Repository::replenishPositions($before, true, true);
        static::assertEquals($before, $after);

        /*
         * Set positions even there is no extreme position (at the top)
         */
        Repository::replenishPositions($before, false, true);
        static::assertEquals($before, $after);
    }

    /**
     * @param array $items Objects who have "getPosition()" and "setPosition()" methods or arrays
     * @dataProvider provideArraysWithoutExtremePosition
     */
    public function testReplenishPositionsUsingArraysWithoutExtremePosition(array $items)
    {
        Repository::replenishPositions($items);
        static::assertSame($items, $items);

        Repository::replenishPositions($items, false);
        static::assertSame($items, $items);
    }

    /**
     * @param array $items    Objects who have "getPosition()" and "setPosition()" methods or arrays
     * @param bool  $asLast   If is set to true, items are placed at the end (default behaviour). Otherwise - at top.
     * @param array $expected Items with replenished positions
     *
     * @dataProvider provideArraysWithoutExtremePosition
     */
    public function testReplenishPositionsUsingArraysWithoutExtremePositionForce(array $items, $asLast, array $expected)
    {
        Repository::replenishPositions($items, $asLast, true);
        static::assertSame($expected, $items);
    }

    /**
     * @param array $items    Objects who have "getPosition()" and "setPosition()" methods or arrays
     * @param bool  $asLast   If is set to true, items are placed at the end (default behaviour). Otherwise - at top.
     * @param array $expected Items with replenished positions
     *
     * @dataProvider provideArraysWithExtremePosition
     */
    public function testReplenishPositionsUsingArraysWithExtremePositionForce(array $items, $asLast, array $expected)
    {
        Repository::replenishPositions($items, $asLast, true);
        static::assertSame($expected, $items);
    }

    /**
     * @param array $items Objects who have "getPosition()" and "setPosition()" methods or arrays
     * @dataProvider provideObjectsWithoutExtremePosition
     */
    public function testReplenishPositionsUsingObjectsWithoutExtremePosition(array $items)
    {
        Repository::replenishPositions($items);
        static::assertSame($items, $items);

        Repository::replenishPositions($items, false);
        static::assertSame($items, $items);
    }

    /**
     * @param array $items    Objects who have "getPosition()" and "setPosition()" methods or arrays
     * @param bool  $asLast   If is set to true, items are placed at the end (default behaviour). Otherwise - at top.
     * @param array $expected Items with replenished positions
     *
     * @dataProvider provideObjectsWithoutExtremePosition
     */
    public function testReplenishPositionsUsingObjectsWithoutExtremePositionForce(array $items, $asLast, array $expected)
    {
        Repository::replenishPositions($items, $asLast, true);
        static::assertEquals($expected, $items);
    }

    /**
     * @param array $items    Objects who have "getPosition()" and "setPosition()" methods or arrays
     * @param bool  $asLast   If is set to true, items are placed at the end (default behaviour). Otherwise - at top.
     * @param array $expected Items with replenished positions
     *
     * @dataProvider provideObjectsWithExtremePosition
     */
    public function testReplenishPositionsUsingObjectsWithExtremePositionForce(array $items, $asLast, array $expected)
    {
        Repository::replenishPositions($items, $asLast, true);
        static::assertEquals($expected, $items);
    }

    public function testGetExtremePositionWithoutItems()
    {
        static::assertNull(Repository::getExtremePosition([]));
        static::assertNull(Repository::getExtremePosition([], false));
    }

    /**
     * @param array $items    Objects who have "getPosition()" and "setPosition()" methods or arrays
     * @param bool  $max      If is set to true, maximum value is returned. Otherwise - minimum.
     * @param int   $expected Extreme position (max or min) of given items
     *
     * @dataProvider provideArraysWithoutExtremePositionToGetExtremePosition
     */
    public function testGetExtremePositionUsingArraysWithoutExtremePosition(array $items, $max, $expected)
    {
        static::assertSame($expected, Repository::getExtremePosition($items, $max));
    }

    /**
     * @param array $items    Objects who have "getPosition()" and "setPosition()" methods or arrays
     * @param bool  $max      If is set to true, maximum value is returned. Otherwise - minimum.
     * @param int   $expected Extreme position (max or min) of given items
     *
     * @dataProvider provideArraysWithExtremePositionToGetExtremePosition
     */
    public function testGetExtremePositionUsingArraysWithExtremePosition(array $items, $max, $expected)
    {
        static::assertSame($expected, Repository::getExtremePosition($items, $max));
    }

    /**
     * @param array $items    Objects who have "getPosition()" and "setPosition()" methods or arrays
     * @param bool  $max      If is set to true, maximum value is returned. Otherwise - minimum.
     * @param int   $expected Extreme position (max or min) of given items
     *
     * @dataProvider provideObjectsWithoutExtremePositionToGetExtremePosition
     */
    public function testGetExtremePositionUsingObjectsWithoutExtremePosition(array $items, $max, $expected)
    {
        static::assertSame($expected, Repository::getExtremePosition($items, $max));
    }

    /**
     * @param array $items    Objects who have "getPosition()" and "setPosition()" methods or arrays
     * @param bool  $max      If is set to true, maximum value is returned. Otherwise - minimum.
     * @param int   $expected Extreme position (max or min) of given items
     *
     * @dataProvider provideObjectsWithExtremePositionToGetExtremePosition
     */
    public function testGetExtremePositionUsingObjectsWithExtremePosition(array $items, $max, $expected)
    {
        static::assertSame($expected, Repository::getExtremePosition($items, $max));
    }

    public function testGetEntityOrderedQueryBuilderUsingDefaults()
    {
        $entityManager = $this->getMock(EntityManagerInterface::class);

        $entityRepository = $this
            ->getMockBuilder(EntityRepository::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'createQueryBuilder',
            ])
            ->getMock()
        ;

        $expectedQueryBuilder = new QueryBuilder($entityManager);
        $expectedQueryBuilder->from('any_table_name', 'qb');

        $entityRepository
            ->expects(static::once())
            ->method('createQueryBuilder')
            ->willReturn($expectedQueryBuilder)
        ;

        $queryBuilder = Repository::getEntityOrderedQueryBuilder($entityRepository);
        $selectDQLPart = $queryBuilder->getDQLPart('select');
        $whereDQLPart = $queryBuilder->getDQLPart('where');
        $orderDQLPart = $queryBuilder->getDQLPart('orderBy');

        /* @var OrderBy $orderBy */
        $orderBy = $orderDQLPart[0];

        static::assertInstanceOf(QueryBuilder::class, $queryBuilder);
        static::assertArraySubset(['qb'], $queryBuilder->getRootAliases());
        static::assertSame([], $selectDQLPart);
        static::assertNull($whereDQLPart);
        static::assertSame(['qb.name ASC'], $orderBy->getParts());
    }

    /**
     * @param string $property        Name of property used by the ORDER BY clause
     * @param string $direction       Direction used by the ORDER BY clause ("ASC" or "DESC")
     * @param string $expectedOrderBy Expected ORDER BY clause
     *
     * @dataProvider providePropertyAndDirectionToGetEntityOrderedQueryBuilder
     */
    public function testGetEntityOrderedQueryBuilder($property, $direction, $expectedOrderBy)
    {
        $entityManager = $this->getMock(EntityManagerInterface::class);

        $entityRepository = $this
            ->getMockBuilder(EntityRepository::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'createQueryBuilder',
            ])
            ->getMock()
        ;

        $expectedQueryBuilder = new QueryBuilder($entityManager);
        $expectedQueryBuilder->from('any_table_name', 'qb');

        $entityRepository
            ->expects(static::once())
            ->method('createQueryBuilder')
            ->willReturn($expectedQueryBuilder)
        ;

        $queryBuilder = Repository::getEntityOrderedQueryBuilder($entityRepository, $property, $direction);
        $selectDQLPart = $queryBuilder->getDQLPart('select');
        $whereDQLPart = $queryBuilder->getDQLPart('where');
        $orderDQLPart = $queryBuilder->getDQLPart('orderBy');

        static::assertInstanceOf(QueryBuilder::class, $queryBuilder);
        static::assertArraySubset(['qb'], $queryBuilder->getRootAliases());
        static::assertSame([], $selectDQLPart);
        static::assertNull($whereDQLPart);

        if (empty($property)) {
            static::assertSame([], $orderDQLPart);
        } else {
            /* @var OrderBy $orderBy */
            $orderBy = $orderDQLPart[0];

            static::assertSame([$expectedOrderBy], $orderBy->getParts());
        }
    }

    /**
     * Provides arrays without extreme position used to replenish positions of them
     *
     * @return Generator
     */
    public function provideArraysWithoutExtremePosition()
    {
        yield[
            [
                [],
                [],
            ],
            true,
            [
                [
                    Repository::POSITION_KEY => 1,
                ],
                [
                    Repository::POSITION_KEY => 2,
                ],
            ],
        ];

        yield[
            [
                [],
                [],
            ],
            false,
            [
                [
                    Repository::POSITION_KEY => -1,
                ],
                [
                    Repository::POSITION_KEY => -2,
                ],
            ],
        ];

        yield[
            [
                [
                    'lorem' => 'ipsum',
                    'dolor',
                    'sit'   => 1,
                ],
                [
                    'abc' => 'def',
                    'ghi' => null,
                    'jkl' => 10,
                ],
            ],
            true,
            [
                [
                    'lorem'                  => 'ipsum',
                    'dolor',
                    'sit'                    => 1,
                    Repository::POSITION_KEY => 1,
                ],
                [
                    'abc'                    => 'def',
                    'ghi'                    => null,
                    'jkl'                    => 10,
                    Repository::POSITION_KEY => 2,
                ],
            ],
        ];

        yield[
            [
                [
                    'lorem' => 'ipsum',
                    'dolor',
                    'sit'   => 1,
                ],
                [
                    'abc' => 'def',
                    'ghi' => null,
                    'jkl' => 10,
                ],
            ],
            false,
            [
                [
                    'lorem'                  => 'ipsum',
                    'dolor',
                    'sit'                    => 1,
                    Repository::POSITION_KEY => -1,
                ],
                [
                    'abc'                    => 'def',
                    'ghi'                    => null,
                    'jkl'                    => 10,
                    Repository::POSITION_KEY => -2,
                ],
            ],
        ];
    }

    /**
     * Provides arrays with extreme position used to replenish positions of them
     *
     * @return Generator
     */
    public function provideArraysWithExtremePosition()
    {
        yield[
            [
                [
                    Repository::POSITION_KEY => 1,
                ],
                [],
                [],
            ],
            true,
            [
                [
                    Repository::POSITION_KEY => 1,
                ],
                [
                    Repository::POSITION_KEY => 2,
                ],
                [
                    Repository::POSITION_KEY => 3,
                ],
            ],
        ];

        yield[
            [
                [],
                [],
                [
                    Repository::POSITION_KEY => 1,
                ],
            ],
            true,
            [
                [
                    Repository::POSITION_KEY => 2,
                ],
                [
                    Repository::POSITION_KEY => 3,
                ],
                [
                    Repository::POSITION_KEY => 1,
                ],
            ],
        ];

        yield[
            [
                [
                    Repository::POSITION_KEY => 1,
                ],
                [],
                [],
            ],
            false,
            [
                [
                    Repository::POSITION_KEY => 1,
                ],
                [
                    Repository::POSITION_KEY => 0,
                ],
                [
                    Repository::POSITION_KEY => -1,
                ],
            ],
        ];

        yield[
            [
                [],
                [],
                [
                    Repository::POSITION_KEY => 1,
                ],
            ],
            false,
            [
                [
                    Repository::POSITION_KEY => 0,
                ],
                [
                    Repository::POSITION_KEY => -1,
                ],
                [
                    Repository::POSITION_KEY => 1,
                ],
            ],
        ];
    }

    /**
     * Provides objects without extreme position used to replenish positions of them
     *
     * @return Generator
     */
    public function provideObjectsWithoutExtremePosition()
    {
        yield[
            [
                new Sortable(),
                new Sortable(),
                new Sortable(),
            ],
            true,
            [
                new Sortable(1),
                new Sortable(2),
                new Sortable(3),
            ],
        ];

        yield[
            [
                new Sortable(),
                new Sortable(),
                new Sortable(),
            ],
            false,
            [
                new Sortable(-1),
                new Sortable(-2),
                new Sortable(-3),
            ],
        ];
    }

    /**
     * Provides objects with extreme position used to replenish positions of them
     *
     * @return Generator
     */
    public function provideObjectsWithExtremePosition()
    {
        yield[
            [
                new Sortable(1),
                new Sortable(),
                new Sortable(),
            ],
            true,
            [
                new Sortable(1),
                new Sortable(2),
                new Sortable(3),
            ],
        ];

        yield[
            [
                new Sortable(),
                new Sortable(1),
                new Sortable(),
            ],
            true,
            [
                new Sortable(2),
                new Sortable(1),
                new Sortable(3),
            ],
        ];

        yield[
            [
                new Sortable(1),
                new Sortable(),
                new Sortable(),
            ],
            false,
            [
                new Sortable(1),
                new Sortable(0),
                new Sortable(-1),
            ],
        ];
    }

    /**
     * Provides arrays without extreme position used to get extreme position
     *
     * @return Generator
     */
    public function provideArraysWithoutExtremePositionToGetExtremePosition()
    {
        yield[
            [],
            false,
            null,
        ];

        yield[
            [],
            true,
            null,
        ];

        yield[
            [
                [
                    'lorem' => 'ipsum',
                    'dolor',
                    'sit'   => 1,
                ],
                [
                    'abc' => 'def',
                    'ghi' => null,
                    'jkl' => 10,
                ],
            ],
            true,
            null,
        ];

        yield[
            [
                [
                    'lorem' => 'ipsum',
                    'dolor',
                    'sit'   => 1,
                ],
                [
                    'abc' => 'def',
                    'ghi' => null,
                    'jkl' => 10,
                ],
            ],
            false,
            null,
        ];
    }

    /**
     * Provides arrays with extreme position used to get extreme position
     *
     * @return Generator
     */
    public function provideArraysWithExtremePositionToGetExtremePosition()
    {
        yield[
            [
                [
                    Repository::POSITION_KEY => 1,
                ],
                [],
                [],
            ],
            true,
            1,
        ];

        yield[
            [
                [
                    Repository::POSITION_KEY => 1,
                ],
                [],
                [],
            ],
            false,
            1,
        ];

        yield[
            [
                [],
                [],
                [
                    Repository::POSITION_KEY => 1,
                ],
            ],
            true,
            1,
        ];

        yield[
            [
                [],
                [],
                [
                    Repository::POSITION_KEY => 1,
                ],
            ],
            false,
            1,
        ];

        yield[
            [
                [
                    Repository::POSITION_KEY => 1,
                ],
                [],
                [
                    Repository::POSITION_KEY => 2,
                ],
                [],
            ],
            true,
            2,
        ];

        yield[
            [
                [
                    Repository::POSITION_KEY => 1,
                ],
                [],
                [
                    Repository::POSITION_KEY => 2,
                ],
                [],
            ],
            false,
            1,
        ];
    }

    /**
     * Provides objects without extreme position used to get extreme position
     *
     * @return Generator
     */
    public function provideObjectsWithoutExtremePositionToGetExtremePosition()
    {
        yield[
            [],
            false,
            null,
        ];

        yield[
            [],
            true,
            null,
        ];

        yield[
            [
                new Sortable(),
                new Sortable(),
                new Sortable(),
            ],
            true,
            null,
        ];

        yield[
            [
                new Sortable(),
                new Sortable(),
                new Sortable(),
            ],
            false,
            null,
        ];
    }

    /**
     * Provides objects with extreme position used to get extreme position
     *
     * @return Generator
     */
    public function provideObjectsWithExtremePositionToGetExtremePosition()
    {
        yield[
            [
                new Sortable(1),
                new Sortable(2),
                new Sortable(3),
            ],
            true,
            3,
        ];

        yield[
            [
                new Sortable(1),
                new Sortable(2),
                new Sortable(3),
            ],
            false,
            1,
        ];
    }

    /**
     * Provide name of property, direction and expected ORDER BY clause used to get query builder
     *
     * @return Generator
     */
    public function providePropertyAndDirectionToGetEntityOrderedQueryBuilder()
    {
        yield[
            null,
            null,
            '',
        ];

        yield[
            '',
            '',
            '',
        ];

        yield[
            'first_name',
            '',
            'qb.first_name ASC',
        ];

        yield[
            'first_name',
            'asc',
            'qb.first_name asc',
        ];

        yield[
            'first_name',
            'ASC',
            'qb.first_name ASC',
        ];

        yield[
            'first_name',
            'desc',
            'qb.first_name desc',
        ];

        yield[
            'first_name',
            'DESC',
            'qb.first_name DESC',
        ];
    }
}
