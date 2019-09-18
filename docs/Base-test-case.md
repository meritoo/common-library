# Meritoo Common Library

Common and useful classes, methods, exceptions etc.

# Base test case (with common methods and data providers)

Located here: `Meritoo\Common\Test\Base\BaseTestCase`.

##### Usage

1. Just extend the `BaseTestCase` class or implement `Meritoo\Common\Traits\Test\Base\BaseTestCaseTrait` trait.
2. Use one of available data providers, e.g. `@dataProvider provideEmptyValue`, or asserts,
e.g. `static::assertMethodVisibility($method, $visibilityType);`

##### Examples

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
2. [Collection of elements](Collection/BaseCollection.md)
3. [Templates](Collection/Templates.md)
4. [Exceptions](Exceptions.md)
5. [Static methods](Static-methods.md)
   1. [Arrays](Static-methods/Arrays.md)
   2. [Regex](Static-methods/Regex.md)
   3. [Uri](Static-methods/Uri.md)
6. [Value Objects](Value-Objects.md)

[&lsaquo; Back to `Readme`](../README.md)
