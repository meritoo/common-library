# Meritoo Common Library
Useful classes, methods, extensions etc.

## Installation

Run [Composer](https://getcomposer.org) to install new package:

    ```bash
    $ composer require meritoo/common-library
    ```

> How to install Composer: https://getcomposer.org/download

## Static methods

This package contains a lot of class with static methods, so usage is not so complicated. Just run the static method who would you like to use. Example:

```php
use Meritoo\Common\Utilities\Arrays;

$firstElement = Arrays::getFirstElement(['lorem', 'ipsum']);
var_dump($firstElement); // string(5) "lorem"
```

## Base test case with common methods and data providers

Located here: `Meritoo\Common\Test\Base\BaseTestCase`. Just extend the `BaseTestCase` class and use it like in `Meritoo\Common\Test\Utilities\DateTest` class:

```php
class DateTest extends BaseTestCase
{
    /**
     * @param mixed $value Empty value, e.g. ""
     * @dataProvider provideEmptyValue
     */
    public function testGetDateTimeEmptyValue($value)
    {
        self::assertFalse(Date::getDateTime($value));
    }

	(...)
}
```

or in `Meritoo\Common\Test\Utilities\MimeTypesTest` class:

```php
class MimeTypesTest extends BaseTestCase
{
	(...)

    /**
     * @param bool $mimeType The mime type, e.g. "video/mpeg"
     * @dataProvider provideBooleanValue
     */
    public function testGetExtensionBooleanMimeType($mimeType)
    {
        self::assertEquals('', MimeTypes::getExtension($mimeType));
    }

	(...)
}
```

## Collection of elements

Located here: `Meritoo\Common\Collection\Collection`. It's a set of some elements, e.g. objects. It's iterable and countable. Provides very useful methods. Some of them:
- `getFirst()` - returns the first element in the collection
- `getLast()` - returns the last element in the collection
- `isEmpty()` - returns information if collection is empty
- `add($element, $index = null)` - adds given element (at the end of collection)
- `addMultiple($elements, $useIndexes = false)` - adds given elements (at the end of collection)
- `prepend($element)` - prepends given element (adds given element at the beginning of collection)
- `remove($element)` - removes given element

Examples of usage below.

#### An empty collection

```php
use Meritoo\Common\Collection\Collection;

$emptyCollection = new Collection();
var_dump($emptyCollection->isEmpty()); // bool(true)
```

#### Simple collection

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

Enjoy!
