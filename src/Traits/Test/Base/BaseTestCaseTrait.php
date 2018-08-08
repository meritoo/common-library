<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Traits\Test\Base;

use DateTime;
use Generator;
use Meritoo\Common\Exception\Type\UnknownOopVisibilityTypeException;
use Meritoo\Common\Type\OopVisibilityType;
use Meritoo\Common\Utilities\Miscellaneous;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use stdClass;

/**
 * BaseTestCaseTrait
 * Created on 2017-11-02
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
    public function provideEmptyValue()
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
    public function provideEmptyScalarValue()
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
    public function provideBooleanValue()
    {
        yield[false];
        yield[true];
    }

    /**
     * Provides instance of DateTime class
     *
     * @return Generator
     */
    public function provideDateTimeInstance()
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
    public function provideDateTimeRelativeFormat()
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
    public function provideNotExistingFilePath()
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
    public function provideNonScalarValue()
    {
        yield[
            [],
        ];

        yield[
            null,
        ];

        yield[
            new stdClass(),
        ];
    }

    /**
     * Returns path of file used by tests.
     * It should be placed in /data/tests directory of this project.
     *
     * @param string $fileName      Name of file
     * @param string $directoryPath (optional) Path of directory containing the file
     * @return string
     */
    public function getFilePathForTesting($fileName, $directoryPath = '')
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
     * Verifies visibility and arguments of method
     *
     * @param string                  $classNamespace         Namespace of class that contains method to verify
     * @param string|ReflectionMethod $method                 Name of method or just the method to verify
     * @param string                  $visibilityType         Expected visibility of verified method. One of
     *                                                        OopVisibilityType class constants.
     * @param int                     $argumentsCount         (optional) Expected count/amount of arguments of the
     *                                                        verified method
     * @param int                     $requiredArgumentsCount (optional) Expected count/amount of required arguments
     *                                                        of the verified method
     * @throws UnknownOopVisibilityTypeException
     * @throws ReflectionException
     *
     * Attention. 2nd argument, the $method, may be:
     * - string - name of the method
     * - instance of ReflectionMethod - just the method (provided by ReflectionClass::getMethod() method)
     */
    protected static function assertMethodVisibilityAndArguments(
        $classNamespace,
        $method,
        $visibilityType,
        $argumentsCount = 0,
        $requiredArgumentsCount = 0
    ) {
        /*
         * Type of visibility is correct?
         */
        if (!(new OopVisibilityType())->isCorrectType($visibilityType)) {
            throw new UnknownOopVisibilityTypeException($visibilityType);
        }

        $reflection = new ReflectionClass($classNamespace);

        /*
         * Name of method provided only?
         * Let's find instance of the method (based on reflection)
         */
        if (!$method instanceof ReflectionMethod) {
            $method = $reflection->getMethod($method);
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

        static::assertEquals($argumentsCount, $method->getNumberOfParameters());
        static::assertEquals($requiredArgumentsCount, $method->getNumberOfRequiredParameters());
    }

    /**
     * Verifies visibility and arguments of class constructor
     *
     * @param string $classNamespace         Namespace of class that contains constructor to verify
     * @param string $visibilityType         Expected visibility of verified method. One of OopVisibilityType class
     *                                       constants.
     * @param int    $argumentsCount         (optional) Expected count/amount of arguments of the verified method
     * @param int    $requiredArgumentsCount (optional) Expected count/amount of required arguments of the verified
     *                                       method
     * @throws ReflectionException
     * @throws UnknownOopVisibilityTypeException
     */
    protected static function assertConstructorVisibilityAndArguments(
        $classNamespace,
        $visibilityType,
        $argumentsCount = 0,
        $requiredArgumentsCount = 0
    ) {
        /*
         * Let's grab the constructor
         */
        $reflection = new ReflectionClass($classNamespace);
        $method = $reflection->getConstructor();

        static::assertMethodVisibilityAndArguments($classNamespace, $method, $visibilityType, $argumentsCount, $requiredArgumentsCount);
    }

    /**
     * Asserts that class with given namespace has no constructor
     *
     * @param string $classNamespace Namespace of class that contains constructor to verify
     * @throws ReflectionException
     */
    protected static function assertHasNoConstructor($classNamespace)
    {
        /*
         * Let's grab the constructor
         */
        $reflection = new ReflectionClass($classNamespace);
        $constructor = $reflection->getConstructor();

        static::assertNull($constructor);
    }

    /**
     * Sets path of directory with data used by test cases
     *
     * @param string $testsDataDirPath Path of directory with data used by test cases
     */
    protected static function setTestsDataDirPath($testsDataDirPath)
    {
        static::$testsDataDirPath = $testsDataDirPath;
    }
}
