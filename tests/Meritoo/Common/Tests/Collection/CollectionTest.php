<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Tests\Collection;

use ArrayIterator;
use Meritoo\Common\Collection\Collection;
use Meritoo\Common\Type\OopVisibilityType;
use Meritoo\Common\Utilities\TestCase;

/**
 * Tests of the collection of elements
 *
 * @author    Krzysztof Niziol <krzysztof.niziol@meritoo.pl>
 * @copyright Meritoo.pl
 */
class CollectionTest extends TestCase
{
    /**
     * An empty collection
     *
     * @var Collection
     */
    private $emptyCollection;

    /**
     * Simple collection
     *
     * @var Collection
     */
    private $simpleCollection;

    /**
     * Elements of simple collection
     *
     * @var array
     */
    private $simpleElements;

    public function testEmptyCollection()
    {
        static::assertEquals(0, $this->emptyCollection->count());
        static::assertCount(0, $this->emptyCollection);
        static::assertEmpty($this->emptyCollection);

        static::assertTrue($this->emptyCollection->isEmpty());
        static::assertEquals([], $this->emptyCollection->toArray());
        static::assertEmpty($this->emptyCollection->toArray());

        static::assertNull($this->emptyCollection->getFirst());
        static::assertNull($this->emptyCollection->getLast());
        static::assertNull($this->emptyCollection[1]);
        static::assertNull($this->emptyCollection['abc']);
    }

    public function testNotEmptyCollection()
    {
        static::assertEquals(4, $this->simpleCollection->count());
        static::assertCount(4, $this->simpleCollection);
        static::assertNotEmpty($this->simpleCollection);

        static::assertFalse($this->simpleCollection->isEmpty());
        static::assertEquals($this->simpleElements, $this->simpleCollection->toArray());
        static::assertNotEmpty($this->simpleCollection->toArray());

        static::assertEquals('lorem', $this->simpleCollection->getFirst());
        static::assertEquals('sit', $this->simpleCollection->getLast());
        static::assertEquals('dolor', $this->simpleCollection[123]);
    }

    public function testCount()
    {
        static::assertEquals(0, $this->emptyCollection->count());
        static::assertEquals(4, $this->simpleCollection->count());
    }

    public function testOffsetExists()
    {
        static::assertFalse(isset($this->emptyCollection['abc']));
        static::assertFalse(isset($this->simpleCollection['abc']));

        static::assertTrue(isset($this->simpleCollection[0]));
        static::assertTrue(isset($this->simpleCollection[345]));
    }

    public function testOffsetGet()
    {
        static::assertNull($this->emptyCollection['abc']);
        static::assertNull($this->simpleCollection['abc']);

        static::assertEquals('lorem', $this->simpleCollection[0]);
        static::assertEquals('sit', $this->simpleCollection[345]);
    }

    public function testOffsetSet()
    {
        $this->emptyCollection['test1'] = 1234;
        $this->simpleCollection['test2'] = 5678;

        static::assertTrue($this->emptyCollection->has(1234));
        static::assertEquals(1234, $this->emptyCollection['test1']);

        static::assertTrue($this->simpleCollection->has(5678));
        static::assertEquals(5678, $this->simpleCollection['test2']);
    }

    public function testOffsetUnset()
    {
        unset($this->simpleCollection[0]);

        static::assertFalse($this->simpleCollection->has('lorem'));
        static::assertEquals('ipsum', $this->simpleCollection[1]);
        static::assertEquals(3, $this->simpleCollection->count());

        unset($this->simpleCollection[123]);

        static::assertFalse($this->simpleCollection->has('dolor'));
        static::assertEquals('ipsum', $this->simpleCollection[1]);
        static::assertEquals(2, $this->simpleCollection->count());
    }

    public function testGetIterator()
    {
        static::assertInstanceOf(ArrayIterator::class, $this->simpleCollection->getIterator());
    }

    public function testAdd()
    {
        $this->emptyCollection->add('test1');

        static::assertTrue($this->emptyCollection->has('test1'));
        static::assertEquals(1, $this->emptyCollection->count());
        static::assertEquals('test1', $this->emptyCollection[0]);
    }

    public function testAddWithIndex()
    {
        $this->emptyCollection->add('test2', 1234);

        static::assertTrue($this->emptyCollection->has('test2'));
        static::assertEquals(1, $this->emptyCollection->count());
        static::assertEquals('test2', $this->emptyCollection[1234]);
    }

    public function testAddMultipleUsingEmptyArray()
    {
        $this->emptyCollection->addMultiple([]);

        static::assertEquals(0, $this->emptyCollection->count());
        static::assertTrue($this->emptyCollection->isEmpty());
    }

    public function testAddMultiple()
    {
        $elements = [
            'test1',
            'test2',
            1234 => 'test3',
            5678 => 'test4',
        ];

        $this->emptyCollection->addMultiple($elements);

        static::assertFalse($this->emptyCollection->isEmpty());
        static::assertEquals(4, $this->emptyCollection->count());

        static::assertEquals('test1', $this->emptyCollection[0]);
        static::assertEquals('test2', $this->emptyCollection[1]);
        static::assertEquals('test3', $this->emptyCollection[2]);
        static::assertEquals('test4', $this->emptyCollection[3]);
    }

    public function testAddMultipleUsingIndexes()
    {
        $elements = [
            'test1',
            'test2',
            1234 => 'test3',
            5678 => 'test4',
        ];

        $this->emptyCollection->addMultiple($elements, true);

        static::assertFalse($this->emptyCollection->isEmpty());
        static::assertEquals(4, $this->emptyCollection->count());

        static::assertEquals('test1', $this->emptyCollection[0]);
        static::assertEquals('test2', $this->emptyCollection[1]);
        static::assertEquals('test3', $this->emptyCollection[1234]);
        static::assertEquals('test4', $this->emptyCollection[5678]);
    }

    public function testPrepend()
    {
        $this->emptyCollection->prepend('lorem-ipsum');

        static::assertFalse($this->emptyCollection->isEmpty());
        static::assertEquals(1, $this->emptyCollection->count());
        static::assertEquals('lorem-ipsum', $this->emptyCollection[0]);

        $this->simpleCollection->prepend('lorem-ipsum');

        static::assertFalse($this->simpleCollection->isEmpty());
        static::assertEquals(5, $this->simpleCollection->count());
        static::assertEquals('lorem-ipsum', $this->simpleCollection[0]);
    }

    public function testRemoveNotExistingElement()
    {
        $this->emptyCollection->remove('abc');

        static::assertTrue($this->emptyCollection->isEmpty());
        static::assertEquals(0, $this->emptyCollection->count());

        $this->simpleCollection->remove('abc');

        static::assertFalse($this->simpleCollection->isEmpty());
        static::assertEquals(4, $this->simpleCollection->count());
    }

    public function testRemove()
    {
        static::assertFalse($this->simpleCollection->isEmpty());
        static::assertEquals(4, $this->simpleCollection->count());
        static::assertEquals('ipsum', $this->simpleCollection[1]);

        $this->simpleCollection->remove('ipsum');

        static::assertFalse($this->simpleCollection->isEmpty());
        static::assertEquals(3, $this->simpleCollection->count());
        static::assertNull($this->simpleCollection[1]);
    }

    public function testIsEmpty()
    {
        static::assertTrue($this->emptyCollection->isEmpty());
        static::assertFalse($this->simpleCollection->isEmpty());
    }

    public function testIsFirst()
    {
        static::assertFalse($this->emptyCollection->isFirst('abc'));
        static::assertFalse($this->simpleCollection->isFirst('abc'));
        static::assertFalse($this->simpleCollection->isFirst('dolor'));
        static::assertTrue($this->simpleCollection->isFirst('lorem'));
    }

    public function testIsLast()
    {
        static::assertFalse($this->emptyCollection->isLast('abc'));
        static::assertFalse($this->simpleCollection->isLast('abc'));
        static::assertFalse($this->simpleCollection->isLast('dolor'));
        static::assertTrue($this->simpleCollection->isLast('sit'));
    }

    public function testHas()
    {
        static::assertFalse($this->emptyCollection->has('abc'));
        static::assertFalse($this->simpleCollection->has('abc'));
        static::assertTrue($this->simpleCollection->has('lorem'));
        static::assertTrue($this->simpleCollection->has('dolor'));
    }

    public function testGetPrevious()
    {
        static::assertNull($this->emptyCollection->getPrevious('abc'));
        static::assertNull($this->simpleCollection->getPrevious('abc'));
        static::assertNull($this->simpleCollection->getPrevious('lorem'));

        static::assertEquals('lorem', $this->simpleCollection->getPrevious('ipsum'));
        static::assertEquals('dolor', $this->simpleCollection->getPrevious('sit'));
    }

    public function testGetNext()
    {
        static::assertNull($this->emptyCollection->getNext('abc'));
        static::assertNull($this->simpleCollection->getNext('abc'));
        static::assertNull($this->simpleCollection->getNext('sit'));

        static::assertEquals('dolor', $this->simpleCollection->getNext('ipsum'));
        static::assertEquals('sit', $this->simpleCollection->getNext('dolor'));
    }

    public function testGetFirst()
    {
        static::assertNull($this->emptyCollection->getFirst());
        static::assertEquals('lorem', $this->simpleCollection->getFirst());
    }

    public function testGetLast()
    {
        static::assertNull($this->emptyCollection->getLast());
        static::assertEquals('sit', $this->simpleCollection->getLast());
    }

    public function testToArray()
    {
        static::assertEquals([], $this->emptyCollection->toArray());
        static::assertEquals($this->simpleElements, $this->simpleCollection->toArray());
    }

    public function testExistsVisibilityAndArguments()
    {
        $this->verifyMethodVisibilityAndArguments(Collection::class, 'exists', OopVisibilityType::IS_PRIVATE, 1, 1);
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->simpleElements = [
            'lorem',
            'ipsum',
            123 => 'dolor',
            345 => 'sit',
        ];

        $this->emptyCollection = new Collection();
        $this->simpleCollection = new Collection($this->simpleElements);
    }
}
