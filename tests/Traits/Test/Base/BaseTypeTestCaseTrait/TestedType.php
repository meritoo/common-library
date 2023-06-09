<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Test\Common\Traits\Test\Base\BaseTypeTestCaseTrait;

use Meritoo\Common\Type\Base\BaseType;
use PHPUnit\Framework\Attributes\CoversNothing;

#[CoversNothing]
class TestedType extends BaseType
{
    public const A = 'a';
    public const B = 'b';
}
