# Meritoo Common Library

Common and useful classes, methods, exceptions etc.

# Collection of elements

Located here: `Meritoo\Common\Collection\Collection`. It's a set of some elements, e.g. objects. It's iterable and countable. Provides very useful methods. Some of them:
- `getFirst()` - returns the first element in the collection
- `getLast()` - returns the last element in the collection
- `isEmpty()` - returns information if collection is empty
- `add($element, $index = null)` - adds given element (at the end of collection)
- `addMultiple($elements, $useIndexes = false)` - adds given elements (at the end of collection)
- `prepend($element)` - prepends given element (adds given element at the beginning of collection)
- `remove($element)` - removes given element

Examples of usage below.

### An empty collection

```php
use Meritoo\Common\Collection\Collection;

$emptyCollection = new Collection();
var_dump($emptyCollection->isEmpty()); // bool(true)
```

### Simple collection

```php
use Meritoo\Common\Collection\Collection;

$elements = [
    'lorem',
    'ipsum',
    123 => 'dolor',
    345 => 'sit',
];

$simpleCollection = new Collection($elements);
var_dump($simpleCollection->has('dolor')); // bool(true)
```

# More

1. [Base test case (with common methods and data providers)](Base-test-case.md)
2. [**Collection of elements**](Collection-of-elements.md)
3. [Exceptions](Exceptions.md)
4. [Static methods](Static-methods.md)
5. [Value Objects](Value-Objects.md)

[&lsaquo; Back to `Readme`](../README.md)
