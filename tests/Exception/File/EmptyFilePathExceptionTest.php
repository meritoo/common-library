<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Test\Common\Exception\File;

use Meritoo\Common\Enums\OopVisibility;
use Meritoo\Common\Exception\File\EmptyFilePathException;
use Meritoo\Common\Test\Base\BaseTestCase;

/**
 * Test case of an exception used while path of given file is empty
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 *
 * @internal
 * @covers    \Meritoo\Common\Exception\File\EmptyFilePathException
 */
class EmptyFilePathExceptionTest extends BaseTestCase
{
    public function testConstructorMessage()
    {
        $exception = EmptyFilePathException::create();
        static::assertSame('Path of the file is empty. Did you provide path of proper file?', $exception->getMessage());
    }

    public function testConstructorVisibilityAndArguments()
    {
        static::assertConstructorVisibilityAndArguments(EmptyFilePathException::class, OopVisibility::Public, 3);
    }
}
