<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Utilities;

use Generator;

/**
 * Useful methods for the Generator class (only static functions)
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class GeneratorUtility
{
    /**
     * Returns elements of generator
     *
     * @param Generator $generator The generator who elements should be returned
     * @return array
     */
    public static function getGeneratorElements(Generator $generator)
    {
        $elements = [];

        for (; $generator->valid(); $generator->next()) {
            $elements[] = $generator->current();
        }

        return $elements;
    }
}
