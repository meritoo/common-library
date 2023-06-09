<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Test\Common\Exception\File;

use Meritoo\Common\Exception\File\EmptyFilePathException;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Traits\Test\Base\BaseTestCaseTrait;
use Meritoo\Common\Type\Base\BaseType;
use Meritoo\Common\Type\OopVisibilityType;
use Meritoo\Common\Utilities\Reflection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(EmptyFilePathException::class)]
#[UsesClass(BaseType::class)]
#[UsesClass(Reflection::class)]
#[UsesClass(BaseTestCaseTrait::class)]
class EmptyFilePathExceptionTest extends BaseTestCase
{
    public function testConstructorMessage(): void
    {
        $exception = EmptyFilePathException::create();
        static::assertSame('Path of the file is empty. Did you provide path of proper file?', $exception->getMessage());
    }

    public function testConstructorVisibilityAndArguments(): void
    {
        static::assertConstructorVisibilityAndArguments(EmptyFilePathException::class, OopVisibilityType::IS_PUBLIC, 3);
    }
}
