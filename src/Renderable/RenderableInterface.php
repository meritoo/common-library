<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Common\Renderable;

use Meritoo\Common\Collection\Templates;

/**
 * Something that may be rendered
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
interface RenderableInterface
{
    /**
     * Renders this object using given templates
     *
     * @param Templates $templates Collection/storage of templates that will be required while rendering this and
     *                             related objects, e.g. children of this object
     * @return string
     */
    public function render(Templates $templates): string;
}
