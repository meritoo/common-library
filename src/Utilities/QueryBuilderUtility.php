<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Utilities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\Query\Parameter;
use Doctrine\ORM\QueryBuilder;

/**
 * Useful methods for query builder (the Doctrine's QueryBuilder class)
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class QueryBuilderUtility
{
    /**
     * Returns root alias of given query builder.
     * If null is returned, alias was not found.
     *
     * @param QueryBuilder $queryBuilder The query builder to retrieve root alias
     * @return null|string
     */
    public static function getRootAlias(QueryBuilder $queryBuilder)
    {
        $aliases = $queryBuilder->getRootAliases();

        /*
         * No aliases?
         * Nothing to do
         */
        if (empty($aliases)) {
            return null;
        }

        return Arrays::getFirstElement($aliases);
    }

    /**
     * Returns alias of given property joined in given query builder
     *
     * If there are no joins or the join does not exist, null is returned.
     * It's also information if given property is already joined in given query builder.
     *
     * @param QueryBuilder $queryBuilder The query builder to verify
     * @param string       $property     Name of property that maybe is joined
     * @return null|string
     */
    public static function getJoinedPropertyAlias(QueryBuilder $queryBuilder, $property)
    {
        $joins = $queryBuilder->getDQLPart('join');

        /*
         * No joins?
         * Nothing to do
         */
        if (empty($joins)) {
            return null;
        }

        $patternTemplate = '/^.+\.%s$/'; // e.g. "SomeThing.PropertyName"
        $pattern = sprintf($patternTemplate, $property);

        foreach ($joins as $joinExpressions) {
            /* @var $expression Join */
            foreach ($joinExpressions as $expression) {
                $joinedProperty = $expression->getJoin();

                if (preg_match($pattern, $joinedProperty)) {
                    return $expression->getAlias();
                }
            }
        }

        return null;
    }

    /**
     * Sets the WHERE criteria in given query builder
     *
     * @param QueryBuilder $queryBuilder The query builder
     * @param array        $criteria     (optional) The criteria used in WHERE clause. It may simple array with pairs
     *                                   key-value or an array of arrays where second element of sub-array is the
     *                                   comparison operator. Example below.
     * @param string|null  $alias        (optional) Alias used in the query
     * @return QueryBuilder
     *
     * Example of the $criteria argument:
     * [
     *      'created_at' => [
     *          '2012-11-17 20:00',
     *          '<'
     *      ],
     *      'title' => [
     *          '%test%',
     *          'like'
     *      ],
     *      'position' => 5,
     * ]
     */
    public static function setCriteria(QueryBuilder $queryBuilder, array $criteria = [], $alias = null)
    {
        /*
         * No criteria used in WHERE clause?
         * Nothing to do
         */
        if (empty($criteria)) {
            return $queryBuilder;
        }

        /*
         * No alias provided?
         * Let's use root alias
         */
        if (null === $alias || '' === $alias) {
            $alias = self::getRootAlias($queryBuilder);
        }

        foreach ($criteria as $column => $value) {
            $compareOperator = '=';

            if (is_array($value) && !empty($value)) {
                if (2 === count($value)) {
                    $compareOperator = $value[1];
                }

                $value = $value[0];
            }

            $predicate = sprintf('%s.%s %s :%s', $alias, $column, $compareOperator, $column);

            if (null === $value) {
                $predicate = $queryBuilder->expr()->isNull(sprintf('%s.%s', $alias, $column));
                unset($criteria[$column]);
            } else {
                $queryBuilder->setParameter($column, $value);
            }

            $queryBuilder = $queryBuilder->andWhere($predicate);
        }

        return $queryBuilder;
    }

    /**
     * Deletes given entities
     *
     * @param EntityManager         $entityManager The entity manager
     * @param array|ArrayCollection $entities      The entities to delete
     * @param bool                  $flushDeleted  (optional) If is set to true, flushes the deleted objects (default
     *                                             behaviour). Otherwise - not.
     * @return bool
     */
    public static function deleteEntities(EntityManager $entityManager, $entities, $flushDeleted = true)
    {
        /*
         * No entities provided?
         * Nothing to do
         */
        if (empty($entities)) {
            return false;
        }

        foreach ($entities as $entity) {
            $entityManager->remove($entity);
        }

        /*
         * The deleted objects should be flushed?
         */
        if ($flushDeleted) {
            $entityManager->flush();
        }

        return true;
    }

    /**
     * Adds given parameters to given query builder.
     * Attention. Existing parameters will be overridden.
     *
     * @param QueryBuilder          $queryBuilder The query builder
     * @param array|ArrayCollection $parameters   Parameters to add. Collection of Doctrine\ORM\Query\Parameter
     *                                            instances or an array with key-value pairs.
     * @return QueryBuilder
     */
    public static function addParameters(QueryBuilder $queryBuilder, $parameters)
    {
        /*
         * No parameters?
         * Nothing to do
         */
        if (empty($parameters)) {
            return $queryBuilder;
        }

        foreach ($parameters as $key => $parameter) {
            $name = $key;
            $value = $parameter;

            if ($parameter instanceof Parameter) {
                $name = $parameter->getName();
                $value = $parameter->getValue();
            }

            $queryBuilder->setParameter($name, $value);
        }

        return $queryBuilder;
    }
}
