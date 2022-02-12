<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Utilities;

use Doctrine\Inflector\InflectorFactory;
use Doctrine\Persistence\Proxy;
use Meritoo\Common\Contract\Collection\CollectionInterface;
use Meritoo\Common\Exception\Reflection\CannotResolveClassNameException;
use Meritoo\Common\Exception\Reflection\MissingChildClassesException;
use Meritoo\Common\Exception\Reflection\NotExistingPropertyException;
use Meritoo\Common\Exception\Reflection\TooManyChildClassesException;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionObject;
use ReflectionProperty;

/**
 * Useful reflection methods
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class Reflection
{
    /**
     * Returns child classes of given class.
     * It's an array of namespaces of the child classes or null (if given class has not child classes).
     *
     * @param array|object|string $class Class who child classes should be returned. An array of objects, strings,
     *                                   object or string.
     * @return null|array
     * @throws CannotResolveClassNameException
     */
    public static function getChildClasses($class): ?array
    {
        $allClasses = get_declared_classes();

        /*
         * No classes?
         * Nothing to do
         */
        if (empty($allClasses)) {
            return null;
        }

        $className = self::getClassName($class);

        // Oops, cannot resolve class
        if (null === $className) {
            throw CannotResolveClassNameException::create('');
        }

        $childClasses = [];

        foreach ($allClasses as $oneClass) {
            if (self::isChildOfClass($oneClass, $className)) {
                /*
                 * Attention. I have to use static::getRealClass() method to avoid problem with the proxy / cache
                 * classes. Example:
                 * - My\ExtraBundle\Entity\MyEntity
                 * - Proxies\__CG__\My\ExtraBundle\Entity\MyEntity
                 *
                 * It's actually the same class, so I have to skip it.
                 */
                $realClass = static::getRealClass($oneClass);

                if (in_array($realClass, $childClasses, true)) {
                    continue;
                }

                $childClasses[] = $realClass;
            }
        }

        return $childClasses;
    }

    /**
     * Returns a class name for given source
     *
     * @param array|object|string $source           An array of objects, namespaces, object or namespace
     * @param bool                $withoutNamespace (optional) If is set to true, namespace is omitted. Otherwise -
     *                                              not, full name of class is returned, with namespace.
     * @return null|string
     */
    public static function getClassName($source, bool $withoutNamespace = false): ?string
    {
        /*
         * First argument is not proper source of class?
         * Nothing to do
         */
        if (empty($source) || (!is_array($source) && !is_object($source) && !is_string($source))) {
            return null;
        }

        $name = '';

        /*
         * An array of objects was provided?
         * Let's use first of them
         */
        if (is_array($source)) {
            $source = Arrays::getFirstElement($source);
        }

        // Let's prepare name of class
        if (is_object($source)) {
            $name = get_class($source);
        } elseif (is_string($source) && (class_exists($source) || trait_exists($source))) {
            $name = $source;
        }

        /*
         * Name of class is still unknown?
         * Nothing to do
         */
        if (empty($name)) {
            return null;
        }

        /*
         * Namespace is not required?
         * Let's return name of class only
         */
        if ($withoutNamespace) {
            $classOnly = Miscellaneous::getLastElementOfString($name, '\\');

            if (null !== $classOnly) {
                $name = $classOnly;
            }

            return $name;
        }

        return static::getRealClass($name);
    }

    /**
     * Returns namespace of class for given source
     *
     * @param array|object|string $source An array of objects, namespaces, object or namespace
     * @return string
     */
    public static function getClassNamespace($source): string
    {
        $fullClassName = self::getClassName($source);

        if (null === $fullClassName || '' === $fullClassName) {
            return '';
        }

        $className = self::getClassName($source, true);

        if ($className === $fullClassName) {
            return $className;
        }

        return Miscellaneous::getStringWithoutLastElement($fullClassName, '\\');
    }

    /**
     * Returns value of given constant
     *
     * @param object|string $class    The object or name of object's class
     * @param string        $constant Name of the constant that contains a value
     * @return mixed
     */
    public static function getConstantValue($class, string $constant)
    {
        $reflection = new ReflectionClass($class);

        if (self::hasConstant($class, $constant)) {
            return $reflection->getConstant($constant);
        }

        return null;
    }

    /**
     * Returns constants of given class / object
     *
     * @param object|string $class The object or name of object's class
     * @return array
     */
    public static function getConstants($class): array
    {
        $reflection = new ReflectionClass($class);

        return $reflection->getConstants();
    }

    /**
     * Returns maximum integer value of constant of given class / object.
     * Constants whose values are integers are considered only.
     *
     * @param object|string $class The object or name of object's class
     * @return null|int
     */
    public static function getMaxNumberConstant($class): ?int
    {
        $constants = self::getConstants($class);

        if (empty($constants)) {
            return null;
        }

        $maxNumber = 0;

        foreach ($constants as $constant) {
            if (is_numeric($constant) && $constant > $maxNumber) {
                $maxNumber = $constant;
            }
        }

        return $maxNumber;
    }

    /**
     * Returns names of methods for given class / object
     *
     * @param object|string $class              The object or name of object's class
     * @param bool          $withoutInheritance (optional) If is set to true, only methods for given class are returned.
     *                                          Otherwise - all methods, with inherited methods too.
     * @return array
     */
    public static function getMethods($class, bool $withoutInheritance = false): array
    {
        $effect = [];

        $reflection = new ReflectionClass($class);
        $methods = $reflection->getMethods();

        if (!empty($methods)) {
            $className = self::getClassName($class);

            foreach ($methods as $method) {
                if ($method instanceof ReflectionMethod) {
                    if ($withoutInheritance && $className !== $method->class) {
                        continue;
                    }

                    $effect[] = $method->name;
                }
            }
        }

        return $effect;
    }

    /**
     * Returns namespace of one child class which extends given class.
     * Extended class should has only one child class.
     *
     * @param array|object|string $parentClass Class who child class should be returned. An array of objects,
     *                                         namespaces, object or namespace.
     * @return mixed
     * @throws TooManyChildClassesException|MissingChildClassesException|CannotResolveClassNameException
     */
    public static function getOneChildClass($parentClass)
    {
        $childClasses = self::getChildClasses($parentClass);

        /*
         * No child classes?
         * Oops, the base / parent class hasn't child class
         */
        if (empty($childClasses)) {
            throw MissingChildClassesException::create($parentClass);
        }

        /*
         * More than 1 child class?
         * Oops, the base / parent class has too many child classes
         */
        if (count($childClasses) > 1) {
            throw TooManyChildClassesException::create($parentClass, $childClasses);
        }

        return trim($childClasses[0]);
    }

    /**
     * Returns a parent class or false if there is no parent class
     *
     * @param array|object|string $source An array of objects, namespaces, object or namespace
     * @return false|ReflectionClass
     */
    public static function getParentClass($source)
    {
        $className = self::getClassName($source);
        $reflection = new ReflectionClass($className);

        return $reflection->getParentClass();
    }

    /**
     * Returns name of the parent class.
     * If given class does not extend another, returns null.
     *
     * @param array|object|string $class An array of objects, namespaces, object or namespace
     * @return null|string
     */
    public static function getParentClassName($class): ?string
    {
        $className = self::getClassName($class);
        $reflection = new ReflectionClass($className);
        $parentClass = $reflection->getParentClass();

        if (null === $parentClass || false === $parentClass) {
            return null;
        }

        return $parentClass->getName();
    }

    /**
     * Returns given object properties
     *
     * @param array|object|string $source         An array of objects, namespaces, object or namespace
     * @param int                 $filter         (optional) Filter of properties. Uses \ReflectionProperty class
     *                                            constants. By default all properties are returned.
     * @param bool                $includeParents (optional) If is set to true, properties of parent classes are
     *                                            included (recursively). Otherwise - not.
     * @return ReflectionProperty[]
     */
    public static function getProperties($source, int $filter = null, bool $includeParents = false): array
    {
        $className = self::getClassName($source);
        $reflection = new ReflectionClass($className);

        if (null === $filter) {
            $filter = ReflectionProperty::IS_PRIVATE
                + ReflectionProperty::IS_PROTECTED
                + ReflectionProperty::IS_PUBLIC
                + ReflectionProperty::IS_STATIC;
        }

        $properties = $reflection->getProperties($filter);
        $parentProperties = [];

        if ($includeParents) {
            $parent = self::getParentClass($source);

            if (false !== $parent) {
                $parentClass = $parent->getName();
                $parentProperties = self::getProperties($parentClass, $filter, $includeParents);
            }
        }

        return array_merge($properties, $parentProperties);
    }

    /**
     * Returns property, the \ReflectionProperty instance, of given object
     *
     * @param array|object|string $class    An array of objects, namespaces, object or namespace
     * @param string              $property Name of the property
     * @param int|null            $filter   (optional) Filter of properties. Uses \ReflectionProperty class constants.
     *                                      By default all properties are allowed / processed.
     * @return null|ReflectionProperty
     */
    public static function getProperty($class, string $property, int $filter = null): ?ReflectionProperty
    {
        $className = self::getClassName($class);
        $properties = self::getProperties($className, $filter);

        if (!empty($properties)) {
            foreach ($properties as $reflectionProperty) {
                if ($reflectionProperty->getName() === $property) {
                    return $reflectionProperty;
                }
            }
        }

        return null;
    }

    /**
     * Returns value of given property
     *
     * @param mixed  $source   Object that should contains given property
     * @param string $property Name of the property that contains a value. It may be also multiple properties
     *                         dot-separated, e.g. "invoice.user.email".
     * @param bool   $force    (optional) If is set to true, try to retrieve value even if the object doesn't have
     *                         property. Otherwise - not.
     * @return mixed
     */
    public static function getPropertyValue($source, string $property, bool $force = false)
    {
        if (Regex::contains($property, '.')) {
            return self::getPropertyValueByPropertiesChain($source, $property, $force);
        }

        [
            $value,
            $valueFound,
        ] = self::getPropertyValueByReflectionProperty($source, $property);

        if (!$valueFound) {
            [
                $value,
                $valueFound,
            ] = self::getPropertyValueByParentClasses($source, $property);
        }

        if (!$valueFound && ($force || self::hasProperty($source, $property))) {
            [
                $value,
                $valueFound,
            ] = self::getPropertyValueByGetter($source, $property);
        }

        if (!$valueFound) {
            $byReflectionProperty = self::getPropertyValueByReflectionProperty($source, $property);
            $value = $byReflectionProperty[0];
        }

        return $value;
    }

    /**
     * Returns values of given property for given objects.
     * Looks for proper getter for the property.
     *
     * @param array|CollectionInterface|object $objects  The objects that should contain given property. It may be also
     *                                                   one object.
     * @param string                           $property Name of the property that contains a value
     * @param bool                             $force    (optional) If is set to true, try to retrieve value even if
     *                                                   the object does not have property. Otherwise - not.
     * @return array
     */
    public static function getPropertyValues($objects, string $property, bool $force = false): array
    {
        /*
         * No objects?
         * Nothing to do
         */
        if (empty($objects)) {
            return [];
        }

        if ($objects instanceof CollectionInterface) {
            $objects = $objects->toArray();
        }

        $values = [];
        $objects = Arrays::makeArray($objects);

        foreach ($objects as $object) {
            $value = self::getPropertyValue($object, $property, $force);

            if (null !== $value) {
                $values[] = $value;
            }
        }

        return $values;
    }

    /**
     * Returns information if given class / object has given constant
     *
     * @param object|string $class    The object or name of object's class
     * @param string        $constant Name of the constant to find
     * @return bool
     */
    public static function hasConstant($class, string $constant): bool
    {
        $reflection = new ReflectionClass($class);

        return $reflection->hasConstant($constant);
    }

    /**
     * Returns information if given class / object has given method
     *
     * @param object|string $class  The object or name of object's class
     * @param string        $method Name of the method to find
     * @return bool
     */
    public static function hasMethod($class, string $method): bool
    {
        $reflection = new ReflectionClass($class);

        return $reflection->hasMethod($method);
    }

    /**
     * Returns information if given class / object has given property
     *
     * @param object|string $class    The object or name of object's class
     * @param string        $property Name of the property to find
     * @return bool
     */
    public static function hasProperty($class, string $property): bool
    {
        $reflection = new ReflectionClass($class);

        return $reflection->hasProperty($property);
    }

    /**
     * Returns information if given child class is a subclass of given parent class
     *
     * @param array|object|string $childClass  The child class. An array of objects, namespaces, object or namespace.
     * @param array|object|string $parentClass The parent class. An array of objects, namespaces, object or namespace.
     * @return bool
     */
    public static function isChildOfClass($childClass, $parentClass): bool
    {
        $childClassName = self::getClassName($childClass);
        $parentClassName = self::getClassName($parentClass);

        $parents = class_parents($childClassName);

        if (is_array($parents) && 0 < count($parents)) {
            return in_array($parentClassName, $parents, true);
        }

        return false;
    }

    /**
     * Returns information if given interface is implemented by given class / object
     *
     * @param array|object|string $source    An array of objects, namespaces, object or namespace
     * @param string              $interface The interface that should be implemented
     * @return bool
     */
    public static function isInterfaceImplemented($source, string $interface): bool
    {
        $className = self::getClassName($source);
        $interfaces = class_implements($className);

        return in_array($interface, $interfaces, true);
    }

    /**
     * Sets values of properties in given object
     *
     * @param mixed $object           Object that should contains given property
     * @param array $propertiesValues Key-value pairs, where key - name of the property, value - value of the property
     */
    public static function setPropertiesValues($object, array $propertiesValues): void
    {
        /*
         * No properties?
         * Nothing to do
         */
        if (empty($propertiesValues)) {
            return;
        }

        foreach ($propertiesValues as $property => $value) {
            static::setPropertyValue($object, $property, $value);
        }
    }

    /**
     * Sets value of given property in given object
     *
     * @param mixed  $object   Object that should contains given property
     * @param string $property Name of the property
     * @param mixed  $value    Value of the property
     * @throws NotExistingPropertyException
     */
    public static function setPropertyValue($object, string $property, $value): void
    {
        $reflectionProperty = self::getProperty($object, $property);

        // Oops, property does not exist
        if (null === $reflectionProperty) {
            throw NotExistingPropertyException::create($object, $property);
        }

        $isPublic = $reflectionProperty->isPublic();

        if (!$isPublic) {
            $reflectionProperty->setAccessible(true);
        }

        $reflectionProperty->setValue($object, $value);

        if (!$isPublic) {
            $reflectionProperty->setAccessible(false);
        }
    }

    /**
     * Returns information if given class / object uses / implements given trait
     *
     * @param array|object|string $class         An array of objects, namespaces, object or namespace
     * @param array|string        $trait         An array of strings or string
     * @param bool                $verifyParents If is set to true, parent classes are verified if they use given
     *                                           trait. Otherwise - not.
     * @return null|bool
     * @throws CannotResolveClassNameException|ReflectionException
     */
    public static function usesTrait($class, $trait, bool $verifyParents = false): ?bool
    {
        $className = self::getClassName($class);
        $traitName = self::getClassName($trait);

        // Oops, cannot resolve class
        if (null === $className || '' === $className) {
            throw CannotResolveClassNameException::create('');
        }

        // Oops, cannot resolve trait
        if (null === $traitName || '' === $traitName) {
            throw CannotResolveClassNameException::create('', false);
        }

        $reflection = new ReflectionClass($className);
        $traitsNames = $reflection->getTraitNames();

        $uses = in_array($traitName, $traitsNames, true);

        if (!$uses && $verifyParents) {
            $parentClassName = self::getParentClassName($className);

            if (null !== $parentClassName) {
                return self::usesTrait($parentClassName, $trait, true);
            }
        }

        return $uses;
    }

    /**
     * Returns value of given property using getter of the property
     *
     * An array with 2 elements is returned:
     * - value of given property
     * - information if the value was found (because null may be returned)
     *
     * @param mixed  $source   Object that should contains given property
     * @param string $property Name of the property that contains a value
     * @return array
     */
    private static function getPropertyValueByGetter($source, string $property): array
    {
        $value = null;
        $valueFound = false;

        $reflectionObject = new ReflectionObject($source);
        $inflector = InflectorFactory::create()->build();
        $property = $inflector->classify($property);

        $gettersPrefixes = [
            'get',
            'has',
            'is',
        ];

        foreach ($gettersPrefixes as $prefix) {
            $getter = sprintf('%s%s', $prefix, $property);

            if ($reflectionObject->hasMethod($getter)) {
                $method = new ReflectionMethod($source, $getter);

                /*
                 * Getter is not accessible publicly?
                 * I have to skip it, to avoid an error like this:
                 *
                 * Call to protected method My\ExtraClass::getExtraProperty() from context 'My\ExtraClass'
                 */
                if ($method->isProtected() || $method->isPrivate()) {
                    continue;
                }

                $value = $source->{$getter}();
                $valueFound = true;

                break;
            }
        }

        return [
            $value,
            $valueFound,
        ];
    }

    /**
     * Returns value of given property using parent classes
     *
     * @param mixed  $source   Object that should contains given property
     * @param string $property Name of the property that contains a value
     * @return array
     */
    private static function getPropertyValueByParentClasses($source, string $property): array
    {
        $properties = self::getProperties($source, null, true);

        if (empty($properties)) {
            return [
                null,
                false,
            ];
        }

        foreach ($properties as $reflectionProperty) {
            if ($reflectionProperty->getName() === $property) {
                $byReflectionProperty = self::getPropertyValueByReflectionProperty(
                    $source,
                    $property,
                    $reflectionProperty
                );

                return [
                    $byReflectionProperty[0],
                    true,
                ];
            }
        }

        return [
            null,
            false,
        ];
    }

    /**
     * Returns value of given property represented as chain of properties
     *
     * @param mixed  $source   Object that should contains given property
     * @param string $property Dot-separated properties, e.g. "invoice.user.email"
     * @param bool   $force    (optional) If is set to true, try to retrieve value even if the object doesn't have
     *                         property. Otherwise - not.
     * @return mixed
     */
    private static function getPropertyValueByPropertiesChain($source, string $property, bool $force)
    {
        $exploded = explode('.', $property);

        $property = $exploded[0];
        $source = self::getPropertyValue($source, $property, $force);

        /*
         * Value of processed property from the chain is not null?
         * Let's dig more and get proper value
         *
         * Required to avoid bug:
         * \ReflectionObject::__construct() expects parameter 1 to be object, null given
         * (...)
         * 4. at \ReflectionObject->__construct (null)
         * 5. at Reflection ::getPropertyValue (null, 'name', true)
         * 6. at ListService->getItemValue (object(Deal), 'project.name', '0')
         *
         * while using "project.name" as property - $project has $name property ($project exists in the Deal class)
         * and the $project equals null
         *
         * Meritoo <github@meritoo.pl>
         * 2016-11-07
         */
        if (null !== $source) {
            unset($exploded[0]);
            $property = implode('.', $exploded);

            return self::getPropertyValue($source, $property, $force);
        }

        return null;
    }

    /**
     * Returns value of given property using the property represented by reflection.
     * If value cannot be fetched, makes the property accessible temporarily.
     *
     * @param mixed                   $object             Object that should contains given property
     * @param string                  $property           Name of the property that contains a value
     * @param null|ReflectionProperty $reflectionProperty (optional) Property represented by reflection
     * @return mixed
     */
    private static function getPropertyValueByReflectionProperty(
        $object,
        string $property,
        ?ReflectionProperty $reflectionProperty = null
    ) {
        $value = null;
        $valueFound = false;
        $className = self::getClassName($object);

        try {
            if (null === $reflectionProperty) {
                $reflectionProperty = new ReflectionProperty($className, $property);
            }

            $value = $reflectionProperty->getValue($object);
            $valueFound = true;
        } catch (ReflectionException $exception) {
        }

        if (null !== $reflectionProperty) {
            $reflectionProperty->setAccessible(true);

            $value = $reflectionProperty->getValue($object);
            $valueFound = true;

            $reflectionProperty->setAccessible(false);
        }

        return [
            $value,
            $valueFound,
        ];
    }

    /**
     * Returns the real class name of a class name that could be a proxy
     *
     * @param string $class Class to verify
     * @return string
     */
    private static function getRealClass(string $class): string
    {
        if (false === $pos = strrpos($class, '\\'.Proxy::MARKER.'\\')) {
            return $class;
        }

        return substr($class, $pos + Proxy::MARKER_LENGTH + 2);
    }
}
