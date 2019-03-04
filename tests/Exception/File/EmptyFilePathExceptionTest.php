<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Test\Common\Exception\File;

use Meritoo\Common\Exception\File\EmptyFilePathException;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Type\OopVisibilityType;

/**
 * Test case of an exception used while path of given file is empty
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class EmptyFilePathExceptionTest extends BaseTestCase
{
    public function testConstructorVisibilityAndArguments()
    {
        static::assertConstructorVisibilityAndArguments(EmptyFilePathException::class, OopVisibilityType::IS_PUBLIC, 3);
    }

    public function testConstructorMessage()
    {
        $exception = EmptyFilePathException::create();
        static::assertSame('Path of the file is empty. Did you provide path of proper file?', $exception->getMessage());
    }
}
