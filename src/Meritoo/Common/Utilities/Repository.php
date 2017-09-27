<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Utilities;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * Useful methods for repository
 *
 * @author    Krzysztof Niziol <krzysztof.niziol@meritoo.pl>
 * @copyright Meritoo.pl
 */
class Repository
{
    /**
     * Replenishes positions of given items
     *
     * @param array $items  The items
     * @param bool  $asLast (optional) If is set to true, items are placed at the end. Otherwise - at the top.
     * @param bool  $force  (optional) If is set to true, positions are set even there is no extreme position.
     *                      Otherwise - if extreme position is not found (is null) replenishment is stopped / skipped.
     */
    public static function replenishPositions($items, $asLast = true, $force = false)
    {
        $position = self::getExtremePosition($items, $asLast);

        if (null === $position && $force) {
            $position = 0;
        }

        if (null !== $position && !empty($items)) {
            foreach ($items as $item) {
                if (method_exists($item, 'getPosition')) {
                    if (null === $item->getPosition()) {
                        if ($asLast) {
                            ++$position;
                        } else {
                            --$position;
                        }

                        if (method_exists($item, 'setPosition')) {
                            $item->setPosition($position);
                        }
                    }
                }
            }
        }
    }

    /**
     * Returns extreme position (max or min) of given items
     *
     * @param array $items The items
     * @param bool  $max   (optional) If is set to true, maximum value is returned. Otherwise - minimum.
     * @return int
     */
    public static function getExtremePosition($items, $max = true)
    {
        $extreme = null;

        if (!empty($items)) {
            foreach ($items as $item) {
                if (Reflection::hasMethod($item, 'getPosition')) {
                    $position = $item->getPosition();

                    if ($max) {
                        if ($position > $extreme) {
                            $extreme = $position;
                        }
                    } else {
                        if ($position < $extreme) {
                            $extreme = $position;
                        }
                    }
                }
            }
        }

        return $extreme;
    }

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
        $property = 'name',
        $direction = 'ASC'
    ) {
        $alias = 'qb';

        return $repository
            ->createQueryBuilder($alias)
            ->orderBy(sprintf('%s.%s', $alias, $property), $direction);
    }
}
