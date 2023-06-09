<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Test\Common\Collection\BaseCollection;

use Meritoo\Common\Collection\BaseCollection;
use PHPUnit\Framework\Attributes\CoversNothing;

#[CoversNothing]
class FirstNamesCollection extends BaseCollection
{
    protected function isValidType($element): bool
    {
        return $element instanceof User;
    }

    protected function prepareElements(array $elements): array
    {
        $result = [];

        /** @var User $element */
        foreach ($elements as $element) {
            $result[] = $element->getFirstName();
        }

        return $result;
    }
}
