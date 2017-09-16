<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Test\Base;

use DateTime;
use Generator;
use Meritoo\Common\Exception\Type\UnknownOopVisibilityTypeException;
use Meritoo\Common\Type\OopVisibilityType;
use PHPUnit_Framework_TestCase;
use ReflectionClass;
use ReflectionMethod;

/**
 * Base test case with common methods and data providers
 *
 * @author    Krzysztof Niziol <krzysztof.niziol@meritoo.pl>
 * @copyright Meritoo.pl
 */
abstract class BaseTestCase extends PHPUnit_Framework_TestCase
{
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
     * Returns path of file used by tests.
     * It should be placed in /data/tests directory of this project.
     *
     * @param string $fileName      Name of file
     * @param string $directoryPath (optional) Path of directory containing the file
     * @return string
     */
    public function getFilePathToTests($fileName, $directoryPath = '')
    {
        if (!empty($directoryPath)) {
            $directoryPath = '/' . $directoryPath;
        }

        return sprintf('%s/../../../../../data/tests/%s%s', __DIR__, $fileName, $directoryPath);
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
     *
     * Attention. 2nd argument, the $method, may be:
     * - string - name of the method
     * - instance of ReflectionMethod - just the method (provided by ReflectionClass::getMethod() method)
     */
    protected function verifyMethodVisibilityAndArguments(
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
     * @param string $classNamespace         Namespace of class that contains method to verify
     * @param string $visibilityType         Expected visibility of verified method. One of OopVisibilityType class
     *                                       constants.
     * @param int    $argumentsCount         (optional) Expected count/amount of arguments of the verified method
     * @param int    $requiredArgumentsCount (optional) Expected count/amount of required arguments of the verified
     *                                       method
     * @throws UnknownOopVisibilityTypeException
     */
    protected function verifyConstructorVisibilityAndArguments(
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

        return $this->verifyMethodVisibilityAndArguments($classNamespace, $method, $visibilityType, $argumentsCount, $requiredArgumentsCount);
    }
}
