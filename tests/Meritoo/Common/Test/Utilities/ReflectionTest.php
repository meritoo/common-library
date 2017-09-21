<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Test\Utilities;

use DateTime;
use Generator;
use Meritoo\Common\Exception\Reflection\CannotResolveClassNameException;
use Meritoo\Common\Exception\Reflection\MissingChildClassesException;
use Meritoo\Common\Exception\Reflection\TooManyChildClassesException;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Test\Utilities\Reflection\A;
use Meritoo\Common\Test\Utilities\Reflection\B;
use Meritoo\Common\Test\Utilities\Reflection\C;
use Meritoo\Common\Test\Utilities\Reflection\D;
use Meritoo\Common\Test\Utilities\Reflection\E;
use Meritoo\Common\Utilities\Reflection;

/**
 * Tests of the useful reflection methods
 *
 * @author    Krzysztof Niziol <krzysztof.niziol@meritoo.pl>
 * @copyright Meritoo.pl
 */
class ReflectionTest extends BaseTestCase
{
    public function verifyConstructor()
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
            self::assertEquals('Symfony\Bundle\SecurityBundle\SecurityBundle', Reflection::getClassName('Symfony\Bundle\SecurityBundle\SecurityBundle'));
            self::assertEquals('SecurityBundle', Reflection::getClassName('Symfony\Bundle\SecurityBundle\SecurityBundle', true));
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
            self::assertEquals('Symfony\Bundle\SecurityBundle', Reflection::getClassNamespace('Symfony\Bundle\SecurityBundle\SecurityBundle'));
        }
    }

    /**
     * @param mixed $invalidClass Empty value, e.g. ""
     * @dataProvider provideEmptyValue
     */
    public function testGetChildClassesInvalidClass($invalidClass)
    {
        $this->expectException(CannotResolveClassNameException::class);

        self::assertNull(Reflection::getChildClasses($invalidClass));
        self::assertNull(Reflection::getChildClasses(123));
    }

    public function testGetChildClassesNotExistingClass()
    {
        $this->expectException(CannotResolveClassNameException::class);
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
        $this->expectException(MissingChildClassesException::class);
        self::assertEquals('LoremIpsum', Reflection::getOneChildClass(C::class));
    }

    public function testGetOneChildClassWithTooManyChildClasses()
    {
        $this->expectException(TooManyChildClassesException::class);

        self::assertEquals(B::class, Reflection::getOneChildClass(A::class));
        self::assertEquals(C::class, Reflection::getOneChildClass(A::class));
    }

    public function testGetOneChildClass()
    {
        self::assertEquals(C::class, Reflection::getOneChildClass(B::class));
    }

    public function testGetMethods()
    {
        self::assertEquals(0, count(Reflection::getMethods(B::class, true)));
        self::assertEquals(1, count(Reflection::getMethods(B::class)));
        self::assertEquals(1, count(Reflection::getMethods(A::class)));
        self::assertEquals(2, count(Reflection::getMethods(C::class, true)));
        self::assertEquals(3, count(Reflection::getMethods(C::class)));
    }

    /**
     * @param array|object|string $class An array of objects, namespaces, object or namespace
     * @param array|string        $trait An array of strings or string
     *
     * @dataProvider provideInvalidClassAndTrait
     */
    public function testUsesTraitInvalidClass($class, $trait)
    {
        $this->expectException(CannotResolveClassNameException::class);
        self::assertNull(Reflection::usesTrait($class, $trait));
    }

    /**
     * @param mixed $trait Empty value, e.g. ""
     * @dataProvider provideEmptyValue
     */
    public function testUsesTraitInvalidTrait($trait)
    {
        $this->expectException(CannotResolveClassNameException::class);
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
}
