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
use Meritoo\Common\Traits\Test\Base\BaseTestCaseTrait;
use Meritoo\Common\Utilities\Arrays;
use Meritoo\Common\Utilities\QueryBuilderUtility;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;
use stdClass;

#[CoversClass(QueryBuilderUtility::class)]
#[UsesClass(Arrays::class)]
#[UsesClass(BaseTestCaseTrait::class)]
class QueryBuilderUtilityTest extends BaseTestCase
{
    private QueryBuilder $queryBuilder;

    public static function provideQueryBuilderAndCriteria(): Generator
    {
        yield [
            [
                'lorem' => 11,
                'ipsum' => 22,
                'dolor' => null,
            ],
        ];

        yield [
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

    public static function provideQueryBuilderAndParameters(): Generator
    {
        yield [
            [],
        ];

        yield [
            new ArrayCollection(),
        ];

        yield [
            [
                'lorem' => 11,
                'ipsum' => 22,
            ],
        ];

        yield [
            new ArrayCollection([
                'lorem' => 11,
                'ipsum' => 22,
            ]),
        ];

        yield [
            [
                new Parameter('lorem', 11),
                new Parameter('ipsum', 22),
            ],
        ];

        yield [
            new ArrayCollection([
                new Parameter('lorem', 11),
                new Parameter('ipsum', 22),
            ]),
        ];
    }

    public static function provideQueryBuilderAndPropertyAlias(): Generator
    {
        yield [
            '',
        ];

        yield [
            'lorem',
        ];
    }

    public static function provideQueryBuilderAndPropertyAlias2(): Generator
    {
        yield [
            'ipsum1',
            null,
        ];

        yield [
            'ipsum',
            'i',
        ];

        yield [
            'dolor',
            'd',
        ];
    }

    #[DataProvider('provideQueryBuilderAndParameters')]
    public function testAddParameters(array|ArrayCollection $parameters): void
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $queryBuilder = new QueryBuilder($entityManager);
        $newQueryBuilder = QueryBuilderUtility::addParameters($queryBuilder, $parameters);

        static::assertSame($queryBuilder, $newQueryBuilder);
        static::assertCount(count($parameters), $newQueryBuilder->getParameters());
    }

    public function testConstructor()
    {
        static::assertHasNoConstructor(QueryBuilderUtility::class);
    }

    public function testDeleteEntities()
    {
        $methods = [
            'remove',
            'flush',
        ];

        $entityManager = $this
            ->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->onlyMethods($methods)
            ->getMock()
        ;

        $entities1 = [];

        $entities2 = [
            new stdClass(),
        ];

        static::assertFalse(QueryBuilderUtility::deleteEntities($entityManager, $entities1));
        static::assertTrue(QueryBuilderUtility::deleteEntities($entityManager, $entities2));
    }

    public function testDeleteEntitiesWithoutFlush()
    {
        $methods = [
            'remove',
            'flush',
        ];

        $entityManager = $this
            ->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->onlyMethods($methods)
            ->getMock()
        ;

        $entities1 = [];

        $entities2 = [
            new stdClass(),
        ];

        static::assertFalse(QueryBuilderUtility::deleteEntities($entityManager, $entities1, false));
        static::assertTrue(QueryBuilderUtility::deleteEntities($entityManager, $entities2, false));
    }

    #[DataProvider('provideQueryBuilderAndPropertyAlias')]
    public function testGetJoinedPropertyAlias(string $propertyName): void
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $queryBuilder = new QueryBuilder($entityManager);

        static::assertNull(QueryBuilderUtility::getJoinedPropertyAlias($queryBuilder, $propertyName));
    }

    #[DataProvider('provideQueryBuilderAndPropertyAlias2')]
    // TODO (kn) Replace number with appropriate description
    public function testGetJoinedPropertyAlias2(string $propertyName, ?string $propertyAlias): void
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);

        $queryBuilder = (new QueryBuilder($entityManager))
            ->from('lorem', 'l')
            ->leftJoin('l.ipsum', 'i')
            ->innerJoin('i.dolor', 'd')
        ;

        static::assertSame($propertyAlias, QueryBuilderUtility::getJoinedPropertyAlias($queryBuilder, $propertyName));
    }

    // TODO (kn) Replace number with appropriate description
    public function testGetJoinedPropertyAlias3(): void
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $queryBuilder = (new QueryBuilder($entityManager))->from('lorem_ipsum', 'lm');

        static::assertNull(QueryBuilderUtility::getJoinedPropertyAlias($queryBuilder, 'lm'));
    }

    // TODO (kn) Replace number with appropriate description
    public function testGetJoinedPropertyAlias4(): void
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);

        $queryBuilder = (new QueryBuilder($entityManager))
            ->from('lorem', 'l')
            ->leftJoin('l.ipsum', 'i')
        ;

        static::assertSame('i', QueryBuilderUtility::getJoinedPropertyAlias($queryBuilder, 'ipsum'));
    }

    // TODO (kn) Replace number with appropriate description
    public function testGetRootAlias1(): void
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $queryBuilder = new QueryBuilder($entityManager);

        static::assertNull(QueryBuilderUtility::getRootAlias($queryBuilder));
    }

    // TODO (kn) Replace number with appropriate description
    public function testGetRootAlias2(): void
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $queryBuilder = (new QueryBuilder($entityManager))->from('lorem_ipsum', 'lm');

        static::assertSame('lm', QueryBuilderUtility::getRootAlias($queryBuilder));
    }

    // TODO (kn) Replace number with appropriate description
    public function testGetRootAlias3(): void
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);

        $queryBuilder = (new QueryBuilder($entityManager))
            ->from('lorem', 'l')
            ->leftJoin('l.ipsum', 'i')
        ;

        static::assertSame('l', QueryBuilderUtility::getRootAlias($queryBuilder));
    }

    #[DataProvider('provideQueryBuilderAndCriteria')]
    public function testSetCriteria(array $criteria): void
    {
        $entityManager = $this
            ->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getExpressionBuilder'])
            ->getMock()
        ;

        $entityManager
            ->expects(static::any())
            ->method('getExpressionBuilder')
            ->willReturn(new Expr())
        ;

        $queryBuilder = (new QueryBuilder($entityManager))->from('lorem_ipsum', 'lm');
        $newQueryBuilder = QueryBuilderUtility::setCriteria($queryBuilder, $criteria);

        $criteriaCount = count($criteria);
        $nullsCount = 0;

        // I have to verify count/amount of NULLs and decrease $criteriaCount, because for null parameter is not added
        array_walk($criteria, function ($value) use (&$nullsCount) {
            if (null === $value) {
                ++$nullsCount;
            }
        });

        static::assertSame($queryBuilder, $newQueryBuilder);
        static::assertCount($criteriaCount - $nullsCount, $newQueryBuilder->getParameters());
        static::assertNotNull($newQueryBuilder->getDQLPart('where'));
    }

    public function testSetCriteriaWithoutAlias()
    {
        $criteria = [
            'lorem' => 11,
            'ipsum' => 22,
        ];

        $newQueryBuilder = QueryBuilderUtility::setCriteria($this->queryBuilder, $criteria);

        static::assertSame($this->queryBuilder, $newQueryBuilder);
        static::assertCount(count($criteria), $newQueryBuilder->getParameters());
        static::assertNotNull($newQueryBuilder->getDQLPart('where'));
    }

    public function testSetCriteriaWithoutCriteria()
    {
        $newQueryBuilder = QueryBuilderUtility::setCriteria($this->queryBuilder);

        static::assertSame($this->queryBuilder, $newQueryBuilder);
        static::assertCount(0, $newQueryBuilder->getParameters());
        static::assertNull($newQueryBuilder->getDQLPart('where'));
    }

    protected function setUp(): void
    {
        parent::setUp();

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $this->queryBuilder = new QueryBuilder($entityManager);
    }
}
