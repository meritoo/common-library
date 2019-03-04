<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Test\Common\Utilities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\Query\Parameter;
use Doctrine\ORM\QueryBuilder;
use Generator;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Utilities\QueryBuilderUtility;

/**
 * Test case of the useful methods for query builder (the Doctrine's QueryBuilder class)
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class QueryBuilderUtilityTest extends BaseTestCase
{
    public function testConstructor()
    {
        static::assertHasNoConstructor(QueryBuilderUtility::class);
    }

    /**
     * @param QueryBuilder $queryBuilder The query builder to retrieve root alias
     * @param null|string  $rootAlias    Expected root alias of given query builder
     *
     * @dataProvider provideQueryBuilderAndRootAlias
     */
    public function testGetRootAlias(QueryBuilder $queryBuilder, $rootAlias)
    {
        static::assertSame($rootAlias, QueryBuilderUtility::getRootAlias($queryBuilder));
    }

    /**
     * @param QueryBuilder $queryBuilder  The query builder to verify
     * @param string       $propertyName  Name of property that maybe is joined
     * @param null|string  $propertyAlias Expected alias of given property joined in given query builder
     *
     * @dataProvider provideQueryBuilderAndPropertyAlias
     */
    public function testGetJoinedPropertyAlias(QueryBuilder $queryBuilder, $propertyName, $propertyAlias)
    {
        static::assertSame($propertyAlias, QueryBuilderUtility::getJoinedPropertyAlias($queryBuilder, $propertyName));
    }

    public function testSetCriteriaWithoutCriteria()
    {
        $entityManager = $this->getMock(EntityManagerInterface::class);
        $queryBuilder = new QueryBuilder($entityManager);
        $newQueryBuilder = QueryBuilderUtility::setCriteria($queryBuilder);

        static::assertSame($queryBuilder, $newQueryBuilder);
        static::assertCount(0, $newQueryBuilder->getParameters());
        static::assertNull($newQueryBuilder->getDQLPart('where'));
    }

    public function testSetCriteriaWithoutAlias()
    {
        $criteria = [
            'lorem' => 11,
            'ipsum' => 22,
        ];

        $entityManager = $this->getMock(EntityManagerInterface::class);
        $queryBuilder = new QueryBuilder($entityManager);
        $newQueryBuilder = QueryBuilderUtility::setCriteria($queryBuilder, $criteria);

        static::assertSame($queryBuilder, $newQueryBuilder);
        static::assertCount(count($criteria), $newQueryBuilder->getParameters());
        static::assertNotNull($newQueryBuilder->getDQLPart('where'));
    }

    /**
     * @param QueryBuilder $queryBuilder The query builder
     * @param array        $criteria     The criteria used in WHERE clause
     *
     * @dataProvider provideQueryBuilderAndCriteria
     */
    public function testSetCriteria(QueryBuilder $queryBuilder, array $criteria)
    {
        $newQueryBuilder = QueryBuilderUtility::setCriteria($queryBuilder, $criteria);
        $criteriaCount = count($criteria);
        $nullsCount = 0;

        /*
         * I have to verify count/amount of NULLs and decrease $criteriaCount, because for null parameter is not added
         */
        array_walk($criteria, function ($value) use (&$nullsCount) {
            if (null === $value) {
                ++$nullsCount;
            }
        });

        static::assertSame($queryBuilder, $newQueryBuilder);
        static::assertCount($criteriaCount - $nullsCount, $newQueryBuilder->getParameters());
        static::assertNotNull($newQueryBuilder->getDQLPart('where'));
    }

    public function testDeleteEntitiesWithoutFlush()
    {
        $methods = [
            'remove',
            'flush',
        ];

        $entityManager = $this->getMock(EntityManager::class, $methods, [], '', false);
        $entities1 = [];

        $entities2 = [
            new \stdClass(),
        ];

        static::assertFalse(QueryBuilderUtility::deleteEntities($entityManager, $entities1, false));
        static::assertTrue(QueryBuilderUtility::deleteEntities($entityManager, $entities2, false));
    }

    public function testDeleteEntities()
    {
        $methods = [
            'remove',
            'flush',
        ];

        $entityManager = $this->getMock(EntityManager::class, $methods, [], '', false);
        $entities1 = [];

        $entities2 = [
            new \stdClass(),
        ];

        static::assertFalse(QueryBuilderUtility::deleteEntities($entityManager, $entities1));
        static::assertTrue(QueryBuilderUtility::deleteEntities($entityManager, $entities2));
    }

    /**
     * @param QueryBuilder          $queryBuilder The query builder
     * @param array|ArrayCollection $parameters   Parameters to add. Collection of Doctrine\ORM\Query\Parameter
     *                                            instances or an array with key-value pairs.
     *
     * @dataProvider provideQueryBuilderAndParameters
     */
    public function testAddParameters(QueryBuilder $queryBuilder, $parameters)
    {
        $newQueryBuilder = QueryBuilderUtility::addParameters($queryBuilder, $parameters);

        static::assertSame($queryBuilder, $newQueryBuilder);
        static::assertCount(count($parameters), $newQueryBuilder->getParameters());
    }

    /**
     * Provides query builder to retrieve root alias and expected root alias
     *
     * @return Generator
     */
    public function provideQueryBuilderAndRootAlias()
    {
        $entityManager = $this->getMock(EntityManagerInterface::class);

        yield[
            new QueryBuilder($entityManager),
            null,
        ];

        yield[
            (new QueryBuilder($entityManager))->from('lorem_ipsum', 'lm'),
            'lm',
        ];

        yield[
            (new QueryBuilder($entityManager))
                ->from('lorem', 'l')
                ->leftJoin('l.ipsum', 'i'),
            'l',
        ];
    }

    /**
     * Provides query builder, name of property and expected alias of given property
     *
     * @return Generator
     */
    public function provideQueryBuilderAndPropertyAlias()
    {
        $entityManager = $this->getMock(EntityManagerInterface::class);

        yield[
            new QueryBuilder($entityManager),
            '',
            null,
        ];

        yield[
            new QueryBuilder($entityManager),
            'lorem',
            null,
        ];

        yield[
            (new QueryBuilder($entityManager))->from('lorem_ipsum', 'lm'),
            'lm',
            null,
        ];

        yield[
            (new QueryBuilder($entityManager))
                ->from('lorem', 'l')
                ->leftJoin('l.ipsum', 'i'),
            'ipsum',
            'i',
        ];

        yield[
            (new QueryBuilder($entityManager))
                ->from('lorem', 'l')
                ->leftJoin('l.ipsum', 'i')
                ->innerJoin('i.dolor', 'd'),
            'ipsum1',
            null,
        ];

        yield[
            (new QueryBuilder($entityManager))
                ->from('lorem', 'l')
                ->leftJoin('l.ipsum', 'i')
                ->innerJoin('i.dolor', 'd'),
            'ipsum',
            'i',
        ];

        yield[
            (new QueryBuilder($entityManager))
                ->from('lorem', 'l')
                ->leftJoin('l.ipsum', 'i')
                ->innerJoin('i.dolor', 'd'),
            'dolor',
            'd',
        ];
    }

    /**
     * Provides query builder and criteria used in WHERE clause
     *
     * @return Generator
     */
    public function provideQueryBuilderAndCriteria()
    {
        $entityManager = $this->getMock(EntityManager::class, ['getExpressionBuilder'], [], '', false);

        $entityManager
            ->expects(static::any())
            ->method('getExpressionBuilder')
            ->willReturn(new Expr())
        ;

        yield[
            (new QueryBuilder($entityManager))->from('lorem_ipsum', 'lm'),
            [
                'lorem' => 11,
                'ipsum' => 22,
                'dolor' => null,
            ],
        ];

        yield[
            (new QueryBuilder($entityManager))->from('lorem_ipsum', 'lm'),
            [
                'lorem' => [
                    11,
                    '>=',
                ],
                'ipsum' => [
                    22,
                    '<',
                ],
                'dolor' => null,
            ],
        ];
    }

    /**
     * Provides query builder and parameters to add to given query builder
     *
     * @return Generator
     */
    public function provideQueryBuilderAndParameters()
    {
        $entityManager = $this->getMock(EntityManagerInterface::class);

        yield[
            new QueryBuilder($entityManager),
            [],
        ];

        yield[
            new QueryBuilder($entityManager),
            new ArrayCollection(),
        ];

        yield[
            new QueryBuilder($entityManager),
            [
                'lorem' => 11,
                'ipsum' => 22,
            ],
        ];

        yield[
            new QueryBuilder($entityManager),
            new ArrayCollection([
                'lorem' => 11,
                'ipsum' => 22,
            ]),
        ];

        yield[
            new QueryBuilder($entityManager),
            [
                new Parameter('lorem', 11),
                new Parameter('ipsum', 22),
            ],
        ];

        yield[
            new QueryBuilder($entityManager),
            new ArrayCollection([
                new Parameter('lorem', 11),
                new Parameter('ipsum', 22),
            ]),
        ];
    }
}
