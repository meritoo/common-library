<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Common\Exception\Date;

use Meritoo\Common\Enums\Date\DatePart;

final class InvalidDatePartException extends \Exception
{
    public function __construct(DatePart $datePart, string $value)
    {
        $message = \sprintf(
            'Value of the \'%s\' date part is invalid: %s',
            $datePart->value,
            $value,
        );

        parent::__construct($message);
    }
}
