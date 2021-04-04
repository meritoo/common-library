<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Common\Traits\Test\Base;

use DateTime;
use Generator;
use Meritoo\Common\Exception\Reflection\ClassWithoutConstructorException;
use Meritoo\Common\Exception\Type\UnknownOopVisibilityTypeException;
use Meritoo\Common\Type\OopVisibilityType;
use Meritoo\Common\Utilities\Miscellaneous;
use ReflectionClass;
use ReflectionMethod;
use RuntimeException;
use stdClass;

/**
 * Trait for the base test case
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
trait BaseTestCaseTrait
{
    /**
     * Path of directory with data used by test cases
     *
     * @var string
     */
    private static $testsDataDirPath = 'data/tests';

    /**
     * Provides an empty value
     *
     * @return Generator
     */
    public function provideEmptyValue(): ?Generator
    {
        yield[''];
        yield['   '];
        yield[null];
        yield[0];
        yield[false];
        yield[[]];
    }

    /**
     * Provides an empty scalar value
     *
     * @return Generator
     */
    public function provideEmptyScalarValue(): ?Generator
    {
        yield[''];
        yield['   '];
        yield[null];
        yield[0];
        yield[false];
    }

    /**
     * Provides boolean value
     *
     * @return Generator
     */
    public function provideBooleanValue(): ?Generator
    {
        yield[false];
        yield[true];
    }

    /**
     * Provides instance of DateTime class
     *
     * @return Generator
     */
    public function provideDateTimeInstance(): ?Generator
    {
        yield[new DateTime()];
        yield[new DateTime('yesterday')];
        yield[new DateTime('now')];
        yield[new DateTime('tomorrow')];
    }

    /**
     * Provides relative / compound format of DateTime
     *
     * @return Generator
     */
    public function provideDateTimeRelativeFormat(): ?Generator
    {
        yield['now'];
        yield['yesterday'];
        yield['tomorrow'];
        yield['back of 10'];
        yield['front of 10'];
        yield['last day of February'];
        yield['first day of next month'];
        yield['last day of previous month'];
        yield['last day of next month'];
        yield['Y-m-d'];
        yield['Y-m-d 10:00'];
    }

    /**
     * Provides path of not existing file, e.g. "lorem/ipsum.jpg"
     *
     * @return Generator
     */
    public function provideNotExistingFilePath(): ?Generator
    {
        yield['lets-test.doc'];
        yield['lorem/ipsum.jpg'];
        yield['surprise/me/one/more/time.txt'];
    }

    /**
     * Provides non scalar value, e.g. [] or null
     *
     * @return Generator
     */
    public function provideNonScalarValue(): ?Generator
    {
        yield[[]];
        yield[null];
        yield[new stdClass()];
    }

    /**
     * Returns path of file used by tests.
     * It should be placed in /data/tests directory of this project.
     *
     * @param string $fileName      Name of file
     * @param string $directoryPath (optional) Path of directory containing the file
     * @return string
     */
    protected function getFilePathForTesting(string $fileName, string $directoryPath = ''): string
    {
        $rootPath = Miscellaneous::getProjectRootPath();

        $paths = [
            $rootPath,
            self::$testsDataDirPath,
            $directoryPath,
            $fileName,
        ];

        return Miscellaneous::concatenatePaths($paths);
    }

    /**
     * Verifies visibility of method
     *
     * @param ReflectionMethod $method                 Name of method or just the method to verify
     * @param string           $visibilityType         Expected visibility of verified method. One of OopVisibilityType
     *                                                 class constants.
     * @throws UnknownOopVisibilityTypeException
     * @throws RuntimeException
     */
    protected static function assertMethodVisibility(ReflectionMethod $method, string $visibilityType): void
    {
        // Type of visibility is not correct?
        if (!OopVisibilityType::isCorrectType($visibilityType)) {
            throw UnknownOopVisibilityTypeException::createException($visibilityType);
        }

        switch ($visibilityType) {
            case OopVisibilityType::IS_PUBLIC:
                static::assertTrue($method->isPublic());

                break;
            case OopVisibilityType::IS_PROTECTED:
                static::assertTrue($method->isProtected());

                break;
            case OopVisibilityType::IS_PRIVATE:
                static::assertTrue($method->isPrivate());

                break;
        }
    }

    /**
     * Verifies count of method's arguments
     *
     * @param ReflectionMethod $method         Name of method or just the method to verify
     * @param int              $argumentsCount (optional) Expected count/amount of arguments of the verified method
     * @param int              $requiredCount  (optional) Expected count/amount of required arguments of the verified
     *                                         method
     * @throws RuntimeException
     */
    protected static function assertMethodArgumentsCount(
        ReflectionMethod $method,
        int $argumentsCount = 0,
        int $requiredCount = 0
    ): void {
        static::assertSame($argumentsCount, $method->getNumberOfParameters());
        static::assertSame($requiredCount, $method->getNumberOfRequiredParameters());
    }

    /**
     * Verifies visibility and arguments of class constructor
     *
     * @param string $className              Fully-qualified name of class that contains constructor to verify
     * @param string $visibilityType         Expected visibility of verified method. One of OopVisibilityType class
     *                                       constants.
     * @param int    $argumentsCount         (optional) Expected count/amount of arguments of the verified method
     * @param int    $requiredArgumentsCount (optional) Expected count/amount of required arguments of the verified
     *                                       method
     * @throws ClassWithoutConstructorException
     */
    protected static function assertConstructorVisibilityAndArguments(
        string $className,
        string $visibilityType,
        int $argumentsCount = 0,
        int $requiredArgumentsCount = 0
    ): void {
        $reflection = new ReflectionClass($className);
        $method = $reflection->getConstructor();

        if (null === $method) {
            throw ClassWithoutConstructorException::create($className);
        }

        static::assertMethodVisibility($method, $visibilityType);
        static::assertMethodArgumentsCount($method, $argumentsCount, $requiredArgumentsCount);
    }

    /**
     * Asserts that class with given namespace has no constructor
     *
     * @param string $className Fully-qualified name of class that contains constructor to verify
     */
    protected static function assertHasNoConstructor(string $className): void
    {
        $reflection = new ReflectionClass($className);
        $constructor = $reflection->getConstructor();

        static::assertNull($constructor);
    }

    /**
     * Sets path of directory with data used by test cases
     *
     * @param string $testsDataDirPath Path of directory with data used by test cases
     */
    protected static function setTestsDataDirPath(string $testsDataDirPath): void
    {
        static::$testsDataDirPath = $testsDataDirPath;
    }
}
