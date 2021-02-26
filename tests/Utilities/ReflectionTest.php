<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Test\Common\Utilities;

use DateTime;
use Generator;
use Meritoo\Common\Collection\BaseCollection;
use Meritoo\Common\Collection\Templates;
use Meritoo\Common\Exception\Reflection\CannotResolveClassNameException;
use Meritoo\Common\Exception\Reflection\MissingChildClassesException;
use Meritoo\Common\Exception\Reflection\NotExistingPropertyException;
use Meritoo\Common\Exception\Reflection\TooManyChildClassesException;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Utilities\Reflection;
use Meritoo\Test\Common\Utilities\Reflection\A;
use Meritoo\Test\Common\Utilities\Reflection\B;
use Meritoo\Test\Common\Utilities\Reflection\C;
use Meritoo\Test\Common\Utilities\Reflection\D;
use Meritoo\Test\Common\Utilities\Reflection\E;
use Meritoo\Test\Common\Utilities\Reflection\F;
use Meritoo\Test\Common\Utilities\Reflection\G;
use Meritoo\Test\Common\Utilities\Reflection\H;
use Meritoo\Test\Common\Utilities\Reflection\I;
use Meritoo\Test\Common\Utilities\Reflection\J;
use Meritoo\Test\Common\Utilities\Reflection\ObjectsCollection;
use ReflectionProperty;
use stdClass;

/**
 * Test case of the useful reflection methods
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 *
 * @internal
 * @covers    \Meritoo\Common\Utilities\Reflection
 */
class ReflectionTest extends BaseTestCase
{
    public function testConstructor(): void
    {
        static::assertHasNoConstructor(Reflection::class);
    }

    /**
     * @param mixed $invalidClass Empty value, e.g. ""
     * @dataProvider provideEmptyValue
     */
    public function testGetClassNameInvalidClass($invalidClass): void
    {
        self::assertNull(Reflection::getClassName($invalidClass));
        self::assertNull(Reflection::getClassName(123));
    }

    public function testGetClassNameNotExistingClass(): void
    {
        // Not existing class
        self::assertEquals('', Reflection::getClassName('xyz'));
        self::assertEquals('', Reflection::getClassName('xyz', true));
    }

    public function testGetClassNameExistingClass(): void
    {
        // Existing class
        self::assertEquals(self::class, Reflection::getClassName(self::class));
        self::assertEquals('ReflectionTest', Reflection::getClassName(self::class, true));
        self::assertEquals(DateTime::class, Reflection::getClassName(new DateTime()));
        self::assertEquals(DateTime::class, Reflection::getClassName(new DateTime(), true));

        self::assertEquals(DateTime::class, Reflection::getClassName([
            new DateTime(),
            new DateTime('yesterday'),
        ]));
    }

    /**
     * A case when namespace of class contains name of class (iow. name of class occurs twice)
     */
    public function testGetClassWhileNamespaceContainsClassName(): void
    {
        self::assertEquals(
            BaseCollection::class,
            Reflection::getClassName(BaseCollection::class)
        );

        self::assertEquals(
            'BaseCollection',
            Reflection::getClassName(BaseCollection::class, true)
        );
    }

    public function testGetClassNamespaceNotExistingClass(): void
    {
        // Not existing class
        self::assertEquals('', Reflection::getClassNamespace('xyz'));
    }

    public function testGetClassNamespaceExistingClass(): void
    {
        // Existing class
        self::assertEquals('Meritoo\Test\Common\Utilities', Reflection::getClassNamespace(self::class));
        self::assertEquals(DateTime::class, Reflection::getClassNamespace(new DateTime()));

        self::assertEquals(DateTime::class, Reflection::getClassNamespace([
            new DateTime(),
            new DateTime('yesterday'),
        ]));
    }

    /**
     * A case when namespace of class contains name of class (name of class is duplicated, occurs twice)
     */
    public function testGetClassNamespaceWhileNamespaceContainsClassName(): void
    {
        self::assertEquals(
            'Meritoo\Common\Collection',
            Reflection::getClassNamespace(BaseCollection::class)
        );
    }

    /**
     * @param mixed $invalidClass Empty value, e.g. ""
     * @dataProvider provideEmptyValue
     */
    public function testGetChildClassesInvalidClass($invalidClass): void
    {
        $this->expectException(CannotResolveClassNameException::class);

        self::assertNull(Reflection::getChildClasses($invalidClass));
        self::assertNull(Reflection::getChildClasses(123));
    }

    public function testGetChildClassesNotExistingClass(): void
    {
        $this->expectException(CannotResolveClassNameException::class);
        self::assertEquals('', Reflection::getChildClasses('xyz'));
    }

    public function testGetChildClassesExistingClass(): void
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

    public function testGetOneChildClassWithMissingChildClasses(): void
    {
        $this->expectException(MissingChildClassesException::class);
        self::assertEquals('LoremIpsum', Reflection::getOneChildClass(C::class));
    }

    public function testGetOneChildClassWithTooManyChildClasses(): void
    {
        // Required to get all classes by get_declared_classes() function and avoid failure:
        //
        // Failed asserting that exception of type "Meritoo\Common\Exception\Reflection\TooManyChildClassesException"
        // is thrown
        new C();

        $this->expectException(TooManyChildClassesException::class);
        Reflection::getOneChildClass(A::class);
    }

    public function testGetOneChildClass(): void
    {
        // Required to get all classes by get_declared_classes() function and avoid throw of
        // Meritoo\Common\Exception\Reflection\MissingChildClassesException exception
        new C();

        self::assertEquals(C::class, Reflection::getOneChildClass(B::class));
    }

    public function testGetMethods(): void
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
    public function testUsesTraitInvalidClass($class, $trait): void
    {
        $this->expectException(CannotResolveClassNameException::class);
        self::assertNull(Reflection::usesTrait($class, $trait));
    }

    /**
     * @param mixed $trait Empty value, e.g. ""
     * @dataProvider provideEmptyValue
     */
    public function testUsesTraitInvalidTrait($trait): void
    {
        $this->expectException(CannotResolveClassNameException::class);
        Reflection::usesTrait(DateTime::class, $trait);
    }

    public function testUsesTraitExistingClass(): void
    {
        self::assertTrue(Reflection::usesTrait(A::class, E::class));
        self::assertFalse(Reflection::usesTrait(B::class, E::class));
        self::assertFalse(Reflection::usesTrait(C::class, E::class));
        self::assertFalse(Reflection::usesTrait(D::class, E::class));
    }

    public function testUsesTraitExistingClassAndVerifyParents(): void
    {
        self::assertTrue(Reflection::usesTrait(A::class, E::class, true));
        self::assertTrue(Reflection::usesTrait(B::class, E::class, true));
        self::assertTrue(Reflection::usesTrait(C::class, E::class, true));
        self::assertFalse(Reflection::usesTrait(D::class, E::class, true));
    }

    public function testGetProperties(): void
    {
        self::assertCount(1, Reflection::getProperties(B::class));
    }

    public function testGetPropertiesUsingFilter(): void
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

    public function testGetPropertiesWithParents(): void
    {
        self::assertCount(2, Reflection::getProperties(B::class, null, true));
    }

    public function testGetPropertyValueOfNotExistingProperty(): void
    {
        self::assertNull(Reflection::getPropertyValue(new D(), 'something'));
        self::assertNull(Reflection::getPropertyValue(new D(), 'something', true));
    }

    public function testGetPropertyValueFromChain(): void
    {
        $f = new F(1000, 'New York', 'USA', 'john.scott');
        self::assertEquals('John', Reflection::getPropertyValue($f, 'g.firstName'));
    }

    public function testGetPropertyValueWithPublicGetter(): void
    {
        $country = 'USA';
        $f = new F(1000, 'New York', $country, 'john.scott');

        self::assertEquals($country, Reflection::getPropertyValue($f, 'country'));
    }

    public function testGetPropertyValueWithProtectedGetter(): void
    {
        $city = 'New York';
        $f = new F(1000, $city, 'USA', 'john.scott');

        self::assertEquals($city, Reflection::getPropertyValue($f, 'city'));
    }

    public function testGetPropertyValueWithPrivateGetter(): void
    {
        $accountBalance = 1000;
        $f = new F($accountBalance, 'New York', 'USA', 'john.scott');

        self::assertEquals($accountBalance, Reflection::getPropertyValue($f, 'accountBalance'));
    }

    public function testGetPropertyValueWithoutGetter(): void
    {
        $username = 'john.scott';
        $f = new F(1000, 'New York', 'USA', $username);

        self::assertEquals($username, Reflection::getPropertyValue($f, 'username'));
    }

    public function testGetPropertyValueFromParentClass(): void
    {
        $c = new C();
        self::assertEquals(1, Reflection::getPropertyValue($c, 'count', true));
    }

    public function testGetPropertyValuesFromEmptySource(): void
    {
        self::assertEquals([], Reflection::getPropertyValues([], 'something'));
        self::assertEquals([], Reflection::getPropertyValues(new Templates(), 'something'));
    }

    public function testGetPropertyValuesOfNotExistingPropertyFromSingleObject(): void
    {
        self::assertEquals([], Reflection::getPropertyValues(new D(), 'something'));
        self::assertEquals([], Reflection::getPropertyValues(new D(), 'something', true));
    }

    public function testGetPropertyValuesOfNotExistingPropertyFromMultipleObjects(): void
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

        $collection = new ObjectsCollection($objects);

        self::assertEquals([], Reflection::getPropertyValues($collection, 'something'));
        self::assertEquals([], Reflection::getPropertyValues($collection, 'something', true));
    }

    public function testGetPropertyValuesOfExistingPropertyFromSingleObject(): void
    {
        self::assertEquals(['John'], Reflection::getPropertyValues(new G(), 'firstName'));
        self::assertEquals(['John'], Reflection::getPropertyValues(new G(), 'firstName', true));
    }

    public function testGetPropertyValuesOfExistingPropertyFromMultipleObjects(): void
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

        $collection = new ObjectsCollection($objects);

        self::assertEquals($expected, Reflection::getPropertyValues($collection, 'city'));
        self::assertEquals($expected, Reflection::getPropertyValues($collection, 'city', true));
    }

    public function testGetPropertyValuesFromChainAndSingleObject(): void
    {
        $f = new F(1000, 'New York', 'USA', 'john.scott');
        $j = new J();

        self::assertEquals(['John'], Reflection::getPropertyValues($f, 'g.firstName'));
        self::assertEquals(['John'], Reflection::getPropertyValues($f, 'g.firstName', true));

        self::assertEquals(['John'], Reflection::getPropertyValues($j, 'f.g.firstName'));
        self::assertEquals(['John'], Reflection::getPropertyValues($j, 'f.g.firstName', true));
    }

    public function testGetPropertyValuesFromChainAndMultipleObjects(): void
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

        self::assertEquals($expected, Reflection::getPropertyValues($objects, 'g.firstName'));
        self::assertEquals($expected, Reflection::getPropertyValues($objects, 'g.firstName', true));

        $collection = new ObjectsCollection($objects);

        self::assertEquals($expected, Reflection::getPropertyValues($collection, 'g.firstName'));
        self::assertEquals($expected, Reflection::getPropertyValues($collection, 'g.firstName', true));
    }

    public function testGetMaxNumberConstantUsingClassWithoutConstants(): void
    {
        static::assertNull(Reflection::getMaxNumberConstant(A::class));
    }

    public function testGetMaxNumberConstant(): void
    {
        static::assertSame(5, Reflection::getMaxNumberConstant(H::class));
    }

    public function testHasMethodUsingClassWithoutMethod(): void
    {
        static::assertFalse(Reflection::hasMethod(A::class, 'getUser'));
    }

    public function testHasMethod(): void
    {
        static::assertTrue(Reflection::hasMethod(A::class, 'getCount'));
    }

    public function testHasPropertyUsingClassWithoutProperty(): void
    {
        static::assertFalse(Reflection::hasProperty(A::class, 'users'));
    }

    public function testHasProperty(): void
    {
        static::assertTrue(Reflection::hasProperty(A::class, 'count'));
    }

    public function testHasConstantUsingClassWithoutConstant(): void
    {
        static::assertFalse(Reflection::hasConstant(H::class, 'users'));
    }

    public function testHasConstant(): void
    {
        static::assertTrue(Reflection::hasConstant(H::class, 'LOREM'));
    }

    public function testGetConstantValueUsingClassWithoutConstant(): void
    {
        static::assertNull(Reflection::getConstantValue(H::class, 'users'));
    }

    /**
     * @param object|string $class    The object or name of object's class
     * @param array         $expected Expected constants
     *
     * @dataProvider provideClassToGetConstants
     */
    public function testGetConstants($class, array $expected): void
    {
        static::assertSame($expected, Reflection::getConstants($class));
    }

    public function testGetConstantValue(): void
    {
        static::assertSame(H::LOREM, Reflection::getConstantValue(H::class, 'LOREM'));
    }

    public function testIsInterfaceImplementedUsingClassWithoutInterface(): void
    {
        static::assertFalse(Reflection::isInterfaceImplemented(A::class, I::class));
    }

    public function testIsInterfaceImplemented(): void
    {
        static::assertTrue(Reflection::isInterfaceImplemented(B::class, I::class));
    }

    public function testIsChildOfClassUsingClassWithoutChildClass(): void
    {
        static::assertFalse(Reflection::isChildOfClass(A::class, B::class));
    }

    public function testIsChildOfClass(): void
    {
        static::assertTrue(Reflection::isChildOfClass(B::class, A::class));
    }

    public function testGetPropertyUsingClassWithoutProperty(): void
    {
        static::assertNull(Reflection::getProperty(A::class, 'lorem'));
    }

    public function testGetPropertyUsingClassWithPrivateProperty(): void
    {
        $property = Reflection::getProperty(A::class, 'count', ReflectionProperty::IS_PRIVATE);

        static::assertInstanceOf(ReflectionProperty::class, $property);
        static::assertTrue($property->isPrivate());
        static::assertSame('count', $property->getName());
    }

    public function testGetPropertyUsingClassWithProtectedProperty(): void
    {
        $property = Reflection::getProperty(B::class, 'name', ReflectionProperty::IS_PROTECTED);

        static::assertInstanceOf(ReflectionProperty::class, $property);
        static::assertTrue($property->isProtected());
        static::assertSame('name', $property->getName());
    }

    /**
     * @param mixed  $object   Object that should contains given property
     * @param string $property Name of the property
     *
     * @dataProvider provideObjectAndNotExistingProperty
     */
    public function testSetPropertyValueUsingNotExistingProperty($object, $property): void
    {
        $this->expectException(NotExistingPropertyException::class);
        Reflection::setPropertyValue($object, $property, 'test test test');
    }

    /**
     * @param mixed  $object   Object that should contains given property
     * @param string $property Name of the property
     * @param mixed  $value    Value of the property
     *
     * @dataProvider provideObjectPropertyAndValue
     */
    public function testSetPropertyValue($object, $property, $value): void
    {
        $oldValue = Reflection::getPropertyValue($object, $property);
        Reflection::setPropertyValue($object, $property, $value);
        $newValue = Reflection::getPropertyValue($object, $property);

        static::assertNotSame($oldValue, $value);
        static::assertSame($newValue, $value);
    }

    public function testSetPropertiesValuesWithoutProperties(): void
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
    public function testSetPropertiesValuesUsingNotExistingProperties($object, array $propertiesValues): void
    {
        $this->expectException(NotExistingPropertyException::class);
        Reflection::setPropertiesValues($object, $propertiesValues);
    }

    /**
     * @param mixed $object           Object that should contains given property
     * @param array $propertiesValues Key-value pairs, where key - name of the property, value - value of the property
     *
     * @dataProvider provideObjectAndPropertiesValues
     */
    public function testSetPropertiesValues($object, array $propertiesValues): void
    {
        Reflection::setPropertiesValues($object, $propertiesValues);

        foreach ($propertiesValues as $property => $value) {
            $realValue = Reflection::getPropertyValue($object, $property);
            static::assertSame($value, $realValue);
        }
    }

    public function provideInvalidClassAndTrait(): ?Generator
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
    }

    public function provideObjectAndNotExistingProperty(): ?Generator
    {
        yield[
            new stdClass(),
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

    public function provideObjectPropertyAndValue(): ?Generator
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

    public function provideObjectAndNotExistingProperties(): ?Generator
    {
        yield[
            new stdClass(),
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

    public function provideObjectAndPropertiesValues(): ?Generator
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
                'g' => new G(),
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

    public function provideClassToGetConstants(): ?Generator
    {
        yield[
            new stdClass(),
            [],
        ];

        yield[
            stdClass::class,
            [],
        ];

        yield[
            H::class,
            [
                'DOLOR'     => 'sit',
                'LOREM'     => 'ipsum',
                'MAX_USERS' => 5,
                'MIN_USERS' => 2,
            ],
        ];
    }
}
