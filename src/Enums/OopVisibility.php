<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Common\Enums;

enum OopVisibility: int
{
    case Private = 3;
    case Protected = 2;
    case Public = 1;
}
