<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Test\Common\Collection;

use ArrayIterator;
use DateTime;
use Generator;
use Meritoo\Common\Collection\BaseCollection;
use Meritoo\Common\Collection\DateTimeCollection;
use Meritoo\Common\Collection\StringCollection;
use Meritoo\Common\Contract\Collection\CollectionInterface;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Type\OopVisibilityType;
use Meritoo\Test\Common\Collection\BaseCollection\FirstNamesCollection;
use Meritoo\Test\Common\Collection\BaseCollection\User;
use ReflectionClass;

/**
 * Test case of the collection of elements
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 *
 * @internal
 * @covers    \Meritoo\Common\Collection\BaseCollection
 */
class BaseCollectionTest extends BaseTestCase
{
    /**
     * An empty collection
     *
     * @var StringCollection
     */
    private $emptyCollection;

    /**
     * Simple collection
     *
     * @var StringCollection
     */
    private $simpleCollection;

    /**
     * Elements of simple collection
     *
     * @var array
     */
    private $simpleElements;

    public function provideElementGetByIndex()
    {
        yield [
            'An empty collection and empty index',
            new StringCollection(),
            '',
            null,
        ];

        yield [
            'An empty collection and non-empty index',
            new StringCollection(),
            'test',
            null,
        ];

        yield [
            'Non-empty collection and not existing index',
            new StringCollection([
                'lorem' => 'ipsum',
                'dolor' => 'sit',
            ]),
            'test',
            null,
        ];

        yield [
            'Collection with existing index',
            new StringCollection([
                'lorem' => 'ipsum',
                'dolor' => 'sit',
            ]),
            'lorem',
            'ipsum',
        ];

        yield [
            'Collection with existing index (collection of arrays)',
            new FirstNamesCollection([
                new User('John', 'Scott'),
                new User('Jane', 'Brown'),
            ]),
            0,
            'John',
        ];

        yield [
            'Collection with existing index (collection of objects)',
            new DateTimeCollection([
                'x' => new DateTime(),
                'y' => new DateTime('2001-01-01'),
                'z' => new DateTime('yesterday'),
            ]),
            'y',
            new DateTime('2001-01-01'),
        ];

        yield [
            'Collection with first names',
            new FirstNamesCollection([
                new User('John', 'Scott'),
                new User('Jane', 'Brown'),
            ]),
            1,
            'Jane',
        ];
    }

    /**
     * Provides element to add to collection
     *
     * @return Generator
     */
    public function provideElementToAdd()
    {
        yield [
            'This is test 1',
            1,
            0,
            new StringCollection(),
        ];

        yield [
            'This is test 2',
            2,
            1,
            new StringCollection([
                'I am 1st',
            ]),
        ];

        yield [
            'This is test 3',
            3,
            2,
            new StringCollection([
                'I am 1st',
                'I am 2nd',
            ]),
        ];
    }

    /**
     * Provides element with index to add to collection
     *
     * @return Generator
     */
    public function provideElementToAddWithIndex()
    {
        yield [
            'This is test 1',
            'test1',
            1,
            'test1',
            new StringCollection(),
        ];

        yield [
            'This is test 2',
            'test2',
            2,
            'test2',
            new StringCollection([
                'test1' => 'I am 1st',
            ]),
        ];

        yield [
            'This is test 3',
            null,
            3,
            0,
            new StringCollection([
                'test1' => 'I am 1st',
                'test2' => 'I am 2nd',
            ]),
        ];

        yield [
            'This is test 4',
            '',
            4,
            '',
            new StringCollection([
                'test1' => 'I am 1st',
                'test2' => 'I am 2nd',
                'I am 3rd',
            ]),
        ];

        yield [
            'This is test 5',
            'test5',
            5,
            'test5',
            new StringCollection([
                'test1' => 'I am 1st',
                'test2' => 'I am 2nd',
                2 => 'I am 3rd',
                3 => 'I am 4th',
            ]),
        ];

        yield [
            'This is test 6',
            'test2',
            4,
            'test2',
            new StringCollection([
                'test1' => 'I am 1st',
                'test2' => 'I am 2nd',
                2 => 'I am 3rd',
                3 => 'I am 4th',
            ]),
        ];
    }

    public function provideElementToAddWithInvalidType(): ?Generator
    {
        yield [
            ['test'],
            0,
            new StringCollection(),
        ];

        yield [
            123,
            2,
            new StringCollection([
                'I am 1st',
                'I am 2nd',
            ]),
        ];
    }

    public function provideElementsToValidateType(): ?Generator
    {
        yield [
            'An empty array',
            [],
            [],
        ];

        yield [
            'Valid elements only',
            [
                new User('John', 'Scott'),
                new User('Jane', 'Brown'),
            ],
            [
                'John',
                'Jane',
            ],
        ];

        yield [
            'Mixed elements',
            [
                1,
                'test',
                '',
                new User('John', 'Scott'),
                [],
                234,
                'test',
                new User('Jane', 'Brown'),
            ],
            [
                'John',
                'Jane',
            ],
        ];
    }

    public function provideResultOfLimit(): ?Generator
    {
        yield 'Negative value of maximum & negative offset' => [
            [],
            -1,
            -2,
        ];

        yield 'Negative value of maximum & positive offset' => [
            [],
            -1,
            2,
        ];

        yield 'Maximum set to 0 & negative offset' => [
            [],
            0,
            -2,
        ];

        yield 'Maximum set to 0 & positive offset' => [
            [],
            0,
            2,
        ];

        yield 'Maximum set to 1 & offset set to 2' => [
            [
                123 => 'dolor',
            ],
            1,
            2,
        ];

        yield 'Maximum set to 2 & offset set to 2' => [
            [
                123 => 'dolor',
                345 => 'sit',
            ],
            2,
            2,
        ];

        yield 'Maximum set to 3 & offset set to latest element' => [
            [
                346 => 'adipiscing elit',
            ],
            3,
            6,
        ];

        yield 'Maximum set to 1 & offset greater than size of collection' => [
            [],
            1,
            10,
        ];
    }

    public function provideResultOfLimitWithDefaultOffset(): ?Generator
    {
        yield 'Negative value of maximum' => [
            [],
            -1,
        ];

        yield 'Maximum set to 0' => [
            [],
            0,
        ];

        yield 'Maximum set to 1' => [
            [
                'lorem',
            ],
            1,
        ];

        yield 'Maximum set to 3' => [
            [
                'lorem',
                'ipsum',
                123 => 'dolor',
            ],
            3,
        ];

        yield 'Maximum greater than size of collection' => [
            [
                'lorem',
                'ipsum',
                123 => 'dolor',
                345 => 'sit',
                'a' => 'amet',
                'c' => 'consectetur',
                346 => 'adipiscing elit',
            ],
            10,
        ];
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
        static::assertSame(4, $this->emptyCollection->count());

        static::assertSame('test1', $this->emptyCollection[0]);
        static::assertSame('test2', $this->emptyCollection[1]);
        static::assertSame('test3', $this->emptyCollection[2]);
        static::assertSame('test4', $this->emptyCollection[3]);
    }

    public function testAddMultipleUsingEmptyArray()
    {
        $this->emptyCollection->addMultiple([]);

        static::assertSame(0, $this->emptyCollection->count());
        static::assertTrue($this->emptyCollection->isEmpty());
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
        static::assertSame(4, $this->emptyCollection->count());

        static::assertSame('test1', $this->emptyCollection[0]);
        static::assertSame('test2', $this->emptyCollection[1]);
        static::assertSame('test3', $this->emptyCollection[1234]);
        static::assertSame('test4', $this->emptyCollection[5678]);
    }

    /**
     * @param mixed               $element       The element to add
     * @param mixed               $index         Index of element to add
     * @param int                 $expectedCount Expected count of elements in collection
     * @param int                 $expectedIndex Expected index of added element in collection
     * @param CollectionInterface $collection    The collection
     *
     * @dataProvider provideElementToAddWithIndex
     */
    public function testAddWithIndex($element, $index, $expectedCount, $expectedIndex, CollectionInterface $collection)
    {
        $collection->add($element, $index);

        static::assertTrue($collection->has($element));
        static::assertSame($expectedCount, $collection->count());
        static::assertSame($element, $collection[$expectedIndex]);
    }

    /**
     * @param mixed               $element       The element to add
     * @param int                 $expectedCount Expected count of elements in collection
     * @param CollectionInterface $collection    The collection
     *
     * @dataProvider provideElementToAddWithInvalidType
     */
    public function testAddWithInvalidType(
        $element,
        int $expectedCount,
        CollectionInterface $collection
    ): void {
        $collection->add($element);

        static::assertFalse($collection->has($element));
        static::assertSame($expectedCount, $collection->count());
    }

    /**
     * @param mixed               $element       The element to add
     * @param int                 $expectedCount Expected count of elements in collection
     * @param int                 $expectedIndex Expected index of added element in collection
     * @param CollectionInterface $collection    The collection
     *
     * @dataProvider provideElementToAdd
     */
    public function testAddWithoutIndex(
        $element,
        int $expectedCount,
        int $expectedIndex,
        CollectionInterface $collection
    ) {
        $collection->add($element);

        static::assertTrue($collection->has($element));
        static::assertSame($expectedCount, $collection->count());
        static::assertSame($element, $collection[$expectedIndex]);
    }

    public function testClear(): void
    {
        self::assertCount(7, $this->simpleCollection);
        $this->simpleCollection->clear();
        self::assertCount(0, $this->simpleCollection);
    }

    public function testClearIfIsEmpty(): void
    {
        self::assertCount(0, $this->emptyCollection);
        $this->emptyCollection->clear();
        self::assertCount(0, $this->emptyCollection);
    }

    public function testCount()
    {
        static::assertSame(0, $this->emptyCollection->count());
        static::assertSame(7, $this->simpleCollection->count());
    }

    public function testEmptyCollection()
    {
        static::assertSame(0, $this->emptyCollection->count());
        static::assertCount(0, $this->emptyCollection);
        static::assertEmpty($this->emptyCollection);

        static::assertTrue($this->emptyCollection->isEmpty());
        static::assertSame([], $this->emptyCollection->toArray());
        static::assertEmpty($this->emptyCollection->toArray());

        static::assertNull($this->emptyCollection->getFirst());
        static::assertNull($this->emptyCollection->getLast());
        static::assertNull($this->emptyCollection[1]);
        static::assertNull($this->emptyCollection['abc']);
    }

    public function testExistsVisibilityAndArguments()
    {
        $reflectionClass = new ReflectionClass(BaseCollection::class);
        $method = $reflectionClass->getMethod('exists');

        static::assertMethodVisibility($method, OopVisibilityType::IS_PRIVATE);
        static::assertMethodArgumentsCount($method, 1, 1);
    }

    /**
     * @param string              $description Description of test
     * @param CollectionInterface $collection  Collection to search for element with given index
     * @param mixed               $index       Index / key of the element
     * @param mixed               $expected    Expected element with given index
     *
     * @dataProvider provideElementGetByIndex
     */
    public function testGetByIndex($description, CollectionInterface $collection, $index, $expected)
    {
        static::assertEquals($expected, $collection->getByIndex($index), $description);
    }

    /**
     * @param string $description
     * @param array  $elements
     * @param array  $expected
     *
     * @dataProvider provideElementsToValidateType
     */
    public function testGetElementsWithValidType(string $description, array $elements, array $expected): void
    {
        $collection = new FirstNamesCollection($elements);
        static::assertSame($expected, $collection->toArray(), $description);
    }

    public function testGetFirst()
    {
        static::assertNull($this->emptyCollection->getFirst());
        static::assertSame('lorem', $this->simpleCollection->getFirst());
    }

    public function testGetIterator()
    {
        static::assertInstanceOf(ArrayIterator::class, $this->simpleCollection->getIterator());
    }

    public function testGetLast()
    {
        static::assertNull($this->emptyCollection->getLast());
        static::assertSame('adipiscing elit', $this->simpleCollection->getLast());
    }

    public function testGetNext()
    {
        static::assertNull($this->emptyCollection->getNext('abc'));
        static::assertNull($this->simpleCollection->getNext('abc'));
        static::assertNull($this->simpleCollection->getNext('adipiscing elit'));

        static::assertSame('dolor', $this->simpleCollection->getNext('ipsum'));
        static::assertSame('sit', $this->simpleCollection->getNext('dolor'));
    }

    public function testGetPrevious()
    {
        static::assertNull($this->emptyCollection->getPrevious('abc'));
        static::assertNull($this->simpleCollection->getPrevious('abc'));
        static::assertNull($this->simpleCollection->getPrevious('lorem'));

        static::assertSame('lorem', $this->simpleCollection->getPrevious('ipsum'));
        static::assertSame('dolor', $this->simpleCollection->getPrevious('sit'));
    }

    public function testHas()
    {
        static::assertFalse($this->emptyCollection->has('abc'));
        static::assertFalse($this->simpleCollection->has('abc'));
        static::assertTrue($this->simpleCollection->has('lorem'));
        static::assertTrue($this->simpleCollection->has('dolor'));
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
        static::assertTrue($this->simpleCollection->isLast('adipiscing elit'));
    }

    /**
     * @param array $expected
     * @param int   $max
     * @param int   $offset
     *
     * @dataProvider provideResultOfLimit
     */
    public function testLimit(array $expected, int $max, int $offset): void
    {
        $result = $this->simpleCollection->limit($max, $offset);
        self::assertSame($expected, $result->toArray());
    }

    public function testLimitIfIsEmpty(): void
    {
        $result = $this->emptyCollection->limit(10);
        self::assertEquals(new StringCollection(), $result);
    }

    /**
     * @param array $expected
     * @param int   $max
     *
     * @dataProvider provideResultOfLimitWithDefaultOffset
     */
    public function testLimitWithDefaultOffset(array $expected, int $max): void
    {
        $result = $this->simpleCollection->limit($max);
        self::assertSame($expected, $result->toArray());
    }

    public function testNotEmptyCollection()
    {
        static::assertSame(7, $this->simpleCollection->count());
        static::assertCount(7, $this->simpleCollection);
        static::assertNotEmpty($this->simpleCollection);

        static::assertFalse($this->simpleCollection->isEmpty());
        static::assertSame($this->simpleElements, $this->simpleCollection->toArray());
        static::assertNotEmpty($this->simpleCollection->toArray());

        static::assertSame('lorem', $this->simpleCollection->getFirst());
        static::assertSame('adipiscing elit', $this->simpleCollection->getLast());
        static::assertSame('dolor', $this->simpleCollection[123]);
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

        static::assertSame('lorem', $this->simpleCollection[0]);
        static::assertSame('sit', $this->simpleCollection[345]);
    }

    public function testOffsetSet()
    {
        $this->emptyCollection['test1'] = 1234;
        $this->simpleCollection['test2'] = 5678;

        static::assertTrue($this->emptyCollection->has(1234));
        static::assertSame(1234, $this->emptyCollection['test1']);

        static::assertTrue($this->simpleCollection->has(5678));
        static::assertSame(5678, $this->simpleCollection['test2']);
    }

    public function testOffsetUnset()
    {
        unset($this->simpleCollection[0]);

        static::assertFalse($this->simpleCollection->has('lorem'));
        static::assertSame('ipsum', $this->simpleCollection[1]);
        static::assertSame(6, $this->simpleCollection->count());

        unset($this->simpleCollection[123]);

        static::assertFalse($this->simpleCollection->has('dolor'));
        static::assertSame('ipsum', $this->simpleCollection[1]);
        static::assertSame(5, $this->simpleCollection->count());
    }

    public function testAppend(): void
    {
        $this->emptyCollection->append('lorem-ipsum');

        static::assertFalse($this->emptyCollection->isEmpty());
        static::assertSame(1, $this->emptyCollection->count());
        static::assertSame('lorem-ipsum', $this->emptyCollection[0]);

        $this->simpleCollection->append('lorem-ipsum');

        static::assertFalse($this->simpleCollection->isEmpty());
        static::assertSame(8, $this->simpleCollection->count());
        static::assertSame('lorem-ipsum', $this->simpleCollection[347]);
    }

    public function testPrepend()
    {
        $this->emptyCollection->prepend('lorem-ipsum');

        static::assertFalse($this->emptyCollection->isEmpty());
        static::assertSame(1, $this->emptyCollection->count());
        static::assertSame('lorem-ipsum', $this->emptyCollection[0]);

        $this->simpleCollection->prepend('lorem-ipsum');

        static::assertFalse($this->simpleCollection->isEmpty());
        static::assertSame(8, $this->simpleCollection->count());
        static::assertSame('lorem-ipsum', $this->simpleCollection[0]);
    }

    public function testRemove()
    {
        static::assertFalse($this->simpleCollection->isEmpty());
        static::assertSame(7, $this->simpleCollection->count());
        static::assertSame('ipsum', $this->simpleCollection[1]);

        $this->simpleCollection->remove('ipsum');

        static::assertFalse($this->simpleCollection->isEmpty());
        static::assertSame(6, $this->simpleCollection->count());
        static::assertNull($this->simpleCollection[1]);
    }

    public function testRemoveNotExistingElement()
    {
        $this->emptyCollection->remove('abc');

        static::assertTrue($this->emptyCollection->isEmpty());
        static::assertSame(0, $this->emptyCollection->count());

        $this->simpleCollection->remove('abc');

        static::assertFalse($this->simpleCollection->isEmpty());
        static::assertSame(7, $this->simpleCollection->count());
    }

    public function testToArray()
    {
        static::assertSame([], $this->emptyCollection->toArray());
        static::assertSame($this->simpleElements, $this->simpleCollection->toArray());
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->simpleElements = [
            'lorem',
            'ipsum',
            123 => 'dolor',
            345 => 'sit',
            'a' => 'amet',
            'c' => 'consectetur',
            'adipiscing elit',
        ];

        $this->emptyCollection = new StringCollection();
        $this->simpleCollection = new StringCollection($this->simpleElements);
    }
}
