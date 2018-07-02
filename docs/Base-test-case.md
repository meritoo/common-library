# Meritoo Common Library

Common and useful classes, methods, exceptions etc.

# Base test case (with common methods and data providers)

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

# More

1. [**Base test case (with common methods and data providers)**](Base-test-case.md)
2. [Collection of elements](Collection-of-elements.md)
3. [Exceptions](Exceptions.md)
4. [Static methods](Static-methods.md)
5. [Value Objects](Value-Objects.md)

[&lsaquo; Back to `Readme`](../README.md)
