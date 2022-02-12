# Meritoo Common Library

Common and useful classes, methods, exceptions etc.

# BaseCollection

### Namespace

`Meritoo\Common\Collection\BaseCollection`

### Info

It's a set of some elements with the same type, e.g. objects. It's iterable and countable. Provides very useful methods.
Some of them:

- `getFirst()` - returns the first element in the collection
- `getLast()` - returns the last element in the collection
- `isEmpty()` - returns information if collection is empty
- `add($element, $index = null)` - adds given element (at the end of collection)
- `addMultiple($elements, $useIndexes = false)` - adds given elements (at the end of collection)
- `prepend($element)` - prepends given element (adds given element at the beginning of collection)
- `remove($element)` - removes given element

### Implementation

You have to implement:

```php
abstract protected function isValidType($element): bool;
```

This method verifies 1 element before it will be added to collection. Returns information if the element has valid,
expected type.

Example (from `Meritoo\Common\Collection\Templates` class):

```php
protected function isValidType($element): bool
{
    return $element instanceof Template;
}
```

### Methods to overwrite

You can, if you wish, overwrite these methods:

1. To prepare elements used to initialize the collection in your own way:

    ```php
    protected function prepareElements(array $elements): array
    ```

2. To validate type of elements in your own way:

    ```php
    protected function getElementsWithValidType(array $elements): array
    ```

### Examples of usage

```php
use Meritoo\Common\Collection\StringCollection;

$emptyCollection = new StringCollection();
var_dump($emptyCollection->isEmpty()); // bool(true)

$elements = [
    'lorem',
    'ipsum',
    123 => 'dolor',
    345 => 'sit',
];

$simpleCollection = new StringCollection($elements);
var_dump($simpleCollection->has('dolor')); // bool(true)
```

# More

1. [Base test case (with common methods and data providers)](../Base-test-case.md)
2. [**Collection of elements**](BaseCollection.md)
3. [Templates](Templates.md)
4. [Exceptions](../Exceptions.md)
5. [Static methods](../Static-methods.md)
    1. [Arrays](../Static-methods/Arrays.md)
    2. [Regex](../Static-methods/Regex.md)
6. [Value Objects](../Value-Objects.md)

[&lsaquo; Back to `Readme`](../../README.md)
