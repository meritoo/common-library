<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Test\Utilities;

use DateTime;
use Generator;
use Meritoo\Common\Collection\Collection;
use Meritoo\Common\Exception\Reflection\CannotResolveClassNameException;
use Meritoo\Common\Exception\Reflection\MissingChildClassesException;
use Meritoo\Common\Exception\Reflection\NotExistingPropertyException;
use Meritoo\Common\Exception\Reflection\TooManyChildClassesException;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Test\Utilities\Reflection\A;
use Meritoo\Common\Test\Utilities\Reflection\B;
use Meritoo\Common\Test\Utilities\Reflection\C;
use Meritoo\Common\Test\Utilities\Reflection\D;
use Meritoo\Common\Test\Utilities\Reflection\E;
use Meritoo\Common\Test\Utilities\Reflection\F;
use Meritoo\Common\Test\Utilities\Reflection\G;
use Meritoo\Common\Test\Utilities\Reflection\H;
use Meritoo\Common\Test\Utilities\Reflection\I;
use Meritoo\Common\Utilities\Reflection;
use ReflectionProperty;

/**
 * Test case of the useful reflection methods
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class ReflectionTest extends BaseTestCase
{
    public function testConstructor()
    {
        static::assertHasNoConstructor(Reflection::class);
    }

    /**
     * @param mixed $invalidClass Empty value, e.g. ""
     * @dataProvider provideEmptyValue
     */
    public function testGetClassNameInvalidClass($invalidClass)
    {
        self::assertNull(Reflection::getClassName($invalidClass));
        self::assertNull(Reflection::getClassName(123));
    }

    public function testGetClassNameNotExistingClass()
    {
        /*
         * Not existing class
         */
        self::assertEquals('', Reflection::getClassName('xyz'));
        self::assertEquals('', Reflection::getClassName('xyz', true));
    }

    public function testGetClassNameExistingClass()
    {
        /*
         * Existing class
         */
        self::assertEquals(self::class, Reflection::getClassName(self::class));
        self::assertEquals('ReflectionTest', Reflection::getClassName(self::class, true));
        self::assertEquals(DateTime::class, Reflection::getClassName(new DateTime()));
        self::assertEquals(DateTime::class, Reflection::getClassName(new DateTime(), true));

        self::assertEquals(DateTime::class, Reflection::getClassName([
            new DateTime(),
            new DateTime('yesterday'),
        ]));
    }

    public function testGetClassNameDuplicatedName()
    {
        /*
         * Class with namespace containing name of class (duplicated string)
         */
        if (class_exists('Symfony\Bundle\SecurityBundle\SecurityBundle')) {
            self::assertEquals(
                'Symfony\Bundle\SecurityBundle\SecurityBundle',
                Reflection::getClassName('Symfony\Bundle\SecurityBundle\SecurityBundle')
            );

            self::assertEquals(
                'SecurityBundle',
                Reflection::getClassName('Symfony\Bundle\SecurityBundle\SecurityBundle', true)
            );
        }
    }

    public function testGetClassNamespaceNotExistingClass()
    {
        /*
         * Not existing class
         */
        self::assertEquals('', Reflection::getClassNamespace('xyz'));
    }

    public function testGetClassNamespaceExistingClass()
    {
        /*
         * Existing class
         */
        self::assertEquals('Meritoo\Common\Test\Utilities', Reflection::getClassNamespace(self::class));
        self::assertEquals(DateTime::class, Reflection::getClassNamespace(new DateTime()));

        self::assertEquals(DateTime::class, Reflection::getClassNamespace([
            new DateTime(),
            new DateTime('yesterday'),
        ]));
    }

    public function testGetClassNamespaceDuplicatedName()
    {
        /*
         * Class with namespace containing name of class (duplicated string)
         */
        if (class_exists('Symfony\Bundle\SecurityBundle\SecurityBundle')) {
            self::assertEquals(
                'Symfony\Bundle\SecurityBundle',
                Reflection::getClassNamespace('Symfony\Bundle\SecurityBundle\SecurityBundle')
            );
        }
    }

    /**
     * @param mixed $invalidClass Empty value, e.g. ""
     * @dataProvider provideEmptyValue
     */
    public function testGetChildClassesInvalidClass($invalidClass)
    {
        $this->setExpectedException(CannotResolveClassNameException::class);

        self::assertNull(Reflection::getChildClasses($invalidClass));
        self::assertNull(Reflection::getChildClasses(123));
    }

    public function testGetChildClassesNotExistingClass()
    {
        $this->setExpectedException(CannotResolveClassNameException::class);
        self::assertEquals('', Reflection::getChildClasses('xyz'));
    }

    public function testGetChildClassesExistingClass()
    {
        /*
         * Attention. I have to create instances of these classes to load them and be available while using
         * get_declared_classes() function in the Reflection::getChildClasses() method. Without these instances the
         * Reflection::getChildClasses() method returns an empty array even if given class has child classes.
         */
        new A();
        new B();
        new C();

        $effect = [
            C::class,
        ];

        self::assertEquals($effect, Reflection::getChildClasses(B::class));

        $effect = [
            B::class,
            C::class,
        ];

        self::assertEquals($effect, Reflection::getChildClasses(A::class));
    }

    public function testGetOneChildClassWithMissingChildClasses()
    {
        $this->setExpectedException(MissingChildClassesException::class);
        self::assertEquals('LoremIpsum', Reflection::getOneChildClass(C::class));
    }

    public function testGetOneChildClassWithTooManyChildClasses()
    {
        $this->setExpectedException(TooManyChildClassesException::class);

        self::assertEquals(B::class, Reflection::getOneChildClass(A::class));
        self::assertEquals(C::class, Reflection::getOneChildClass(A::class));
    }

    public function testGetOneChildClass()
    {
        self::assertEquals(C::class, Reflection::getOneChildClass(B::class));
    }

    public function testGetMethods()
    {
        self::assertCount(1, Reflection::getMethods(B::class, true));
        self::assertCount(3, Reflection::getMethods(B::class));
        self::assertCount(2, Reflection::getMethods(A::class));
        self::assertCount(2, Reflection::getMethods(C::class, true));
        self::assertCount(5, Reflection::getMethods(C::class));
    }

    /**
     * @param array|object|string $class An array of objects, namespaces, object or namespace
     * @param array|string        $trait An array of strings or string
     *
     * @dataProvider provideInvalidClassAndTrait
     */
    public function testUsesTraitInvalidClass($class, $trait)
    {
        $this->setExpectedException(CannotResolveClassNameException::class);
        self::assertNull(Reflection::usesTrait($class, $trait));
    }

    /**
     * @param mixed $trait Empty value, e.g. ""
     * @dataProvider provideEmptyValue
     */
    public function testUsesTraitInvalidTrait($trait)
    {
        $this->setExpectedException(CannotResolveClassNameException::class);
        self::assertNull(Reflection::usesTrait(DateTime::class, $trait));
    }

    public function testUsesTraitExistingClass()
    {
        self::assertTrue(Reflection::usesTrait(A::class, E::class));
        self::assertFalse(Reflection::usesTrait(B::class, E::class));
        self::assertFalse(Reflection::usesTrait(C::class, E::class));
        self::assertFalse(Reflection::usesTrait(D::class, E::class));
    }

    public function testUsesTraitExistingClassAndVerifyParents()
    {
        self::assertTrue(Reflection::usesTrait(A::class, E::class, true));
        self::assertTrue(Reflection::usesTrait(B::class, E::class, true));
        self::assertTrue(Reflection::usesTrait(C::class, E::class, true));
        self::assertFalse(Reflection::usesTrait(D::class, E::class, true));
    }

    public function testGetProperties()
    {
        self::assertCount(1, Reflection::getProperties(B::class));
    }

    public function testGetPropertiesUsingFilter()
    {
        self::assertCount(
            1,
            Reflection::getProperties(B::class, ReflectionProperty::IS_PROTECTED)
        );

        self::assertCount(
            0,
            Reflection::getProperties(B::class, ReflectionProperty::IS_PRIVATE)
        );

        self::assertCount(
            1,
            Reflection::getProperties(B::class, ReflectionProperty::IS_PRIVATE, true)
        );
    }

    public function testGetPropertiesWithParents()
    {
        self::assertCount(2, Reflection::getProperties(B::class, null, true));
    }

    public function testGetPropertyValueOfNotExistingProperty()
    {
        self::assertNull(Reflection::getPropertyValue(new D(), 'something'));
        self::assertNull(Reflection::getPropertyValue(new D(), 'something', true));
    }

    public function testGetPropertyValueFromChain()
    {
        $f = new F(1000, 'New York', 'USA', 'john.scott');
        self::assertEquals('John', Reflection::getPropertyValue($f, 'gInstance.firstName'));
    }

    public function testGetPropertyValueWithPublicGetter()
    {
        $country = 'USA';
        $f = new F(1000, 'New York', $country, 'john.scott');

        self::assertEquals($country, Reflection::getPropertyValue($f, 'country'));
    }

    public function testGetPropertyValueWithProtectedGetter()
    {
        $city = 'New York';
        $f = new F(1000, $city, 'USA', 'john.scott');

        self::assertEquals($city, Reflection::getPropertyValue($f, 'city'));
    }

    public function testGetPropertyValueWithPrivateGetter()
    {
        $accountBalance = 1000;
        $f = new F($accountBalance, 'New York', 'USA', 'john.scott');

        self::assertEquals($accountBalance, Reflection::getPropertyValue($f, 'accountBalance'));
    }

    public function testGetPropertyValueWithoutGetter()
    {
        $username = 'john.scott';
        $f = new F(1000, 'New York', 'USA', $username);

        self::assertEquals($username, Reflection::getPropertyValue($f, 'username'));
    }

    public function testGetPropertyValuesFromEmptySource()
    {
        self::assertEquals([], Reflection::getPropertyValues([], 'something'));
        self::assertEquals([], Reflection::getPropertyValues(new Collection(), 'something'));
    }

    public function testGetPropertyValuesOfNotExistingPropertyFromSingleObject()
    {
        self::assertEquals([], Reflection::getPropertyValues(new D(), 'something'));
        self::assertEquals([], Reflection::getPropertyValues(new D(), 'something', true));
    }

    public function testGetPropertyValuesOfNotExistingPropertyFromMultipleObjects()
    {
        $objects = [
            new A(),
            new A(),
            new A(),
            new B(),
            new B(),
            new C(),
            new D(),
        ];

        self::assertEquals([], Reflection::getPropertyValues($objects, 'something'));
        self::assertEquals([], Reflection::getPropertyValues($objects, 'something', true));

        $collection = new Collection($objects);

        self::assertEquals([], Reflection::getPropertyValues($collection, 'something'));
        self::assertEquals([], Reflection::getPropertyValues($collection, 'something', true));
    }

    public function testGetPropertyValuesOfExistingPropertyFromSingleObject()
    {
        self::assertEquals(['John'], Reflection::getPropertyValues(new G(), 'firstName'));
        self::assertEquals(['John'], Reflection::getPropertyValues(new G(), 'firstName', true));
    }

    public function testGetPropertyValuesOfExistingPropertyFromMultipleObjects()
    {
        $expected = [
            'New York',
            'London',
            'Tokyo',
        ];

        $objects = [
            new F(1000, 'New York', 'USA', 'john.scott'),
            new F(2000, 'London', 'GB', 'john.scott'),
            new F(3000, 'Tokyo', 'Japan', 'john.scott'),
        ];

        self::assertEquals($expected, Reflection::getPropertyValues($objects, 'city'));
        self::assertEquals($expected, Reflection::getPropertyValues($objects, 'city', true));

        $collection = new Collection($objects);

        self::assertEquals($expected, Reflection::getPropertyValues($collection, 'city'));
        self::assertEquals($expected, Reflection::getPropertyValues($collection, 'city', true));
    }

    public function testGetPropertyValuesFromChainAndSingleObject()
    {
        $f = new F(1000, 'New York', 'USA', 'john.scott');

        self::assertEquals(['John'], Reflection::getPropertyValues($f, 'gInstance.firstName'));
        self::assertEquals(['John'], Reflection::getPropertyValues($f, 'gInstance.firstName', true));
    }

    public function testGetPropertyValuesFromChainAndMultipleObjects()
    {
        $expected = [
            'John',
            'Mary',
            'Peter',
        ];

        $objects = [
            new F(1000, 'New York', 'USA', 'john.scott'),
            new F(2000, 'London', 'GB', 'john.scott', 'Mary', 'Jane'),
            new F(3000, 'Tokyo', 'Japan', 'john.scott', 'Peter', 'Brown'),
        ];

        self::assertEquals($expected, Reflection::getPropertyValues($objects, 'gInstance.firstName'));
        self::assertEquals($expected, Reflection::getPropertyValues($objects, 'gInstance.firstName', true));

        $collection = new Collection($objects);

        self::assertEquals($expected, Reflection::getPropertyValues($collection, 'gInstance.firstName'));
        self::assertEquals($expected, Reflection::getPropertyValues($collection, 'gInstance.firstName', true));
    }

    public function testGetMaxNumberConstantUsingClassWithoutConstants()
    {
        static::assertNull(Reflection::getMaxNumberConstant(A::class));
    }

    public function testGetMaxNumberConstant()
    {
        static::assertEquals(5, Reflection::getMaxNumberConstant(H::class));
    }

    public function testHasMethodUsingClassWithoutMethod()
    {
        static::assertFalse(Reflection::hasMethod(A::class, 'getUser'));
    }

    public function testHasMethod()
    {
        static::assertTrue(Reflection::hasMethod(A::class, 'getCount'));
    }

    public function testHasPropertyUsingClassWithoutProperty()
    {
        static::assertFalse(Reflection::hasProperty(A::class, 'users'));
    }

    public function testHasProperty()
    {
        static::assertTrue(Reflection::hasProperty(A::class, 'count'));
    }

    public function testHasConstantUsingClassWithoutConstant()
    {
        static::assertFalse(Reflection::hasConstant(H::class, 'users'));
    }

    public function testHasConstant()
    {
        static::assertTrue(Reflection::hasConstant(H::class, 'LOREM'));
    }

    public function testGetConstantValueUsingClassWithoutConstant()
    {
        static::assertNull(Reflection::getConstantValue(H::class, 'users'));
    }

    public function testGetConstantValue()
    {
        static::assertEquals(H::LOREM, Reflection::getConstantValue(H::class, 'LOREM'));
    }

    public function testIsInterfaceImplementedUsingClassWithoutInterface()
    {
        static::assertFalse(Reflection::isInterfaceImplemented(A::class, I::class));
    }

    public function testIsInterfaceImplemented()
    {
        static::assertTrue(Reflection::isInterfaceImplemented(B::class, I::class));
    }

    public function testIsChildOfClassUsingClassWithoutChildClass()
    {
        static::assertFalse(Reflection::isChildOfClass(A::class, B::class));
    }

    public function testIsChildOfClass()
    {
        static::assertTrue(Reflection::isChildOfClass(B::class, A::class));
    }

    public function testGetPropertyUsingClassWithoutProperty()
    {
        static::assertNull(Reflection::getProperty(A::class, 'lorem'));
    }

    public function testGetPropertyUsingClassWithPrivateProperty()
    {
        $property = Reflection::getProperty(A::class, 'count', ReflectionProperty::IS_PRIVATE);

        static::assertInstanceOf(ReflectionProperty::class, $property);
        static::assertTrue($property->isPrivate());
        static::assertEquals('count', $property->getName());
    }

    public function testGetPropertyUsingClassWithProtectedProperty()
    {
        $property = Reflection::getProperty(B::class, 'name', ReflectionProperty::IS_PROTECTED);

        static::assertInstanceOf(ReflectionProperty::class, $property);
        static::assertTrue($property->isProtected());
        static::assertEquals('name', $property->getName());
    }

    /**
     * @param mixed  $object   Object that should contains given property
     * @param string $property Name of the property
     *
     * @dataProvider provideObjectAndNotExistingProperty
     */
    public function testSetPropertyValueUsingNotExistingProperty($object, $property)
    {
        $this->setExpectedException(NotExistingPropertyException::class);
        Reflection::setPropertyValue($object, $property, 'test test test');
    }

    /**
     * @param mixed  $object   Object that should contains given property
     * @param string $property Name of the property
     * @param mixed  $value    Value of the property
     *
     * @dataProvider provideObjectPropertyAndValue
     */
    public function testSetPropertyValue($object, $property, $value)
    {
        $oldValue = Reflection::getPropertyValue($object, $property);
        Reflection::setPropertyValue($object, $property, $value);
        $newValue = Reflection::getPropertyValue($object, $property);

        static::assertNotSame($oldValue, $value);
        static::assertSame($newValue, $value);
    }

    public function testSetPropertiesValuesWithoutProperties()
    {
        $object = new G();
        Reflection::setPropertiesValues($object, []);

        static::assertSame($object->getFirstName(), 'John');
        static::assertSame($object->getLastName(), 'Scott');
    }

    /**
     * @param mixed $object           Object that should contains given property
     * @param array $propertiesValues Key-value pairs, where key - name of the property, value - value of the property
     *
     * @dataProvider provideObjectAndNotExistingProperties
     */
    public function testSetPropertiesValuesUsingNotExistingProperties($object, array $propertiesValues)
    {
        $this->setExpectedException(NotExistingPropertyException::class);
        Reflection::setPropertiesValues($object, $propertiesValues);
    }

    /**
     * @param mixed $object           Object that should contains given property
     * @param array $propertiesValues Key-value pairs, where key - name of the property, value - value of the property
     *
     * @dataProvider provideObjectAndPropertiesValues
     */
    public function testSetPropertiesValues($object, array $propertiesValues)
    {
        Reflection::setPropertiesValues($object, $propertiesValues);

        foreach ($propertiesValues as $property => $value) {
            $realValue = Reflection::getPropertyValue($object, $property);
            static::assertSame($value, $realValue);
        }
    }

    /**
     * Provides invalid class and trait
     *
     * @return Generator
     */
    public function provideInvalidClassAndTrait()
    {
        yield[
            '',
            '',
        ];

        yield[
            null,
            null,
        ];

        yield[
            0,
            0,
        ];

        yield[
            [],
            [],
        ];
    }

    /**
     * Provides object and name of not existing property
     *
     * @return Generator
     */
    public function provideObjectAndNotExistingProperty()
    {
        yield[
            new \stdClass(),
            'test',
        ];

        yield[
            new A(),
            'test',
        ];

        yield[
            new B(),
            'firstName',
        ];
    }

    /**
     * Provides object, name of property and value of the property
     *
     * @return Generator
     */
    public function provideObjectPropertyAndValue()
    {
        yield[
            new A(),
            'count',
            123,
        ];

        yield[
            new B(),
            'name',
            'test test',
        ];

        yield[
            new G(),
            'firstName',
            'Jane',
        ];

        yield[
            new G(),
            'lastName',
            'Smith',
        ];
    }

    /**
     * Provides object and not existing properties
     *
     * @return Generator
     */
    public function provideObjectAndNotExistingProperties()
    {
        yield[
            new \stdClass(),
            [
                'test' => 1,
            ],
        ];

        yield[
            new A(),
            [
                'test' => 2,
            ],
        ];

        yield[
            new B(),
            [
                'firstName' => '',
            ],
        ];
    }

    /**
     * Provides object and its new values of properties
     *
     * @return Generator
     */
    public function provideObjectAndPropertiesValues()
    {
        yield[
            new A(),
            [
                'count' => 123,
            ],
        ];

        yield[
            new B(),
            [
                'name' => 'test test',
            ],
        ];

        yield[
            new G(),
            [
                'firstName' => 'Jane',
            ],
        ];

        yield[
            new G(),
            [
                'lastName' => 'Smith',
            ],
        ];

        yield[
            new G(),
            [
                'firstName' => 'Jane',
                'lastName'  => 'Brown',
            ],
        ];

        yield[
            new F(
                123,
                'New York',
                'USA',
                'UnKnown'
            ),
            [
                'gInstance' => new G(),
            ],
        ];

        yield[
            new F(
                123,
                'New York',
                'USA',
                'UnKnown',
                'Mary',
                'Brown'
            ),
            [
                'country'        => 'Canada',
                'accountBalance' => 456,
            ],
        ];
    }
}
