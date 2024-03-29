<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Utilities;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use ReflectionException;

/**
 * Useful methods for repository
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class Repository
{
    public const ORDER_BY_ASCENDING = 'ASC';
    public const ORDER_BY_DESCENDING = 'DESC';

    /**
     * Name of key responsible for sorting/position of an item in array
     *
     * @var string
     */
    public const POSITION_KEY = 'position';

    /**
     * Returns query builder for given entity's repository.
     * The entity should contain given property, e.g. "name".
     *
     * @param EntityRepository $repository Repository of the entity
     * @param string           $property   (optional) Name of property used by the ORDER BY clause
     * @param string           $direction  (optional) Direction used by the ORDER BY clause ("ASC" or "DESC")
     * @return QueryBuilder
     */
    public static function getEntityOrderedQueryBuilder(
        EntityRepository $repository,
        string $property = 'name',
        string $direction = self::ORDER_BY_ASCENDING
    ): QueryBuilder {
        $alias = 'qb';
        $queryBuilder = $repository->createQueryBuilder($alias);

        if (empty($property)) {
            return $queryBuilder;
        }

        return $queryBuilder->orderBy(sprintf('%s.%s', $alias, $property), $direction);
    }

    /**
     * Returns extreme position (max or min) of given items
     *
     * @param array $items Objects who have "getPosition()" and "setPosition()" methods or arrays
     * @param bool  $max   (optional) If is set to true, maximum value is returned. Otherwise - minimum.
     * @return int
     * @throws ReflectionException
     */
    public static function getExtremePosition(array $items, bool $max = true): ?int
    {
        /*
         * No items?
         * Nothing to do
         */
        if (empty($items)) {
            return null;
        }

        $extreme = null;

        foreach ($items as $item) {
            // Not sortable?
            if (!self::isSortable($item)) {
                continue;
            }

            $position = null;

            // Let's grab the position
            if (is_object($item)) {
                $position = $item->getPosition();
            } elseif (array_key_exists(static::POSITION_KEY, $item)) {
                $position = $item[static::POSITION_KEY];
            }

            // Maximum value is expected?
            if ($max) {
                // Position was found and it's larger than previously found position (the extreme position)?
                if (null === $extreme || (null !== $position && $position > $extreme)) {
                    $extreme = $position;
                }

                continue;
            }

            /*
             * Minimum value is expected here.
             * Position was found and it's smaller than previously found position (the extreme position)?
             */
            if (null === $extreme || (null !== $position && $position < $extreme)) {
                $extreme = $position;
            }
        }

        return $extreme;
    }

    /**
     * Replenishes positions of given items
     *
     * @param array $items  Objects who have "getPosition()" and "setPosition()" methods or arrays
     * @param bool  $asLast (optional) If is set to true, items are placed at the end (default behaviour). Otherwise -
     *                      at top.
     * @param bool  $force  (optional) If is set to true, positions are set even there is no extreme position.
     *                      Otherwise - if extreme position is unknown (is null) replenishment is stopped / skipped
     *                      (default behaviour).
     * @throws ReflectionException
     */
    public static function replenishPositions(array &$items, bool $asLast = true, bool $force = false): void
    {
        $position = self::getExtremePosition($items, $asLast);

        /*
         * Extreme position is unknown, but it's required?
         * Use 0 as default/start value
         */
        if (null === $position && $force) {
            $position = 0;
        }

        /*
         * Extreme position is unknown or there are no items to sort?
         * Nothing to do
         */
        if (null === $position || empty($items)) {
            return;
        }

        foreach ($items as &$item) {
            // Not sortable?
            if (!self::isSortable($item)) {
                continue;
            }

            // Sorted already (position has been set)?
            if (self::isSorted($item)) {
                continue;
            }

            // Calculate position
            if ($asLast) {
                ++$position;
            } else {
                --$position;
            }

            /*
             * It's an object?
             * Use proper method to set position
             */
            if (is_object($item)) {
                $item->setPosition($position);

                continue;
            }

            /*
             * It's an array
             * Use proper key to set position
             */
            $item[static::POSITION_KEY] = $position;
        }
    }

    /**
     * Returns information if given item is sortable
     *
     * Sortable means it's an:
     * - array
     * or
     * - object and has getPosition() and setPosition()
     *
     * @param mixed $item An item to verify (object who has "getPosition()" and "setPosition()" methods or an array)
     * @return bool
     * @throws ReflectionException
     */
    private static function isSortable($item): bool
    {
        return is_array($item)
            ||
            (
                is_object($item)
                &&
                Reflection::hasMethod($item, 'getPosition')
                &&
                Reflection::hasMethod($item, 'setPosition')
            );
    }

    /**
     * Returns information if given item is sorted (position has been set)
     *
     * @param mixed $item An item to verify (object who has "getPosition()" and "setPosition()" methods or an array)
     * @return bool
     */
    private static function isSorted($item): bool
    {
        return
            (is_object($item) && null !== $item->getPosition())
            ||
            (is_array($item) && isset($item[static::POSITION_KEY]));
    }
}
