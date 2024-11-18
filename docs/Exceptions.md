# Meritoo Common Library

Common and useful classes, methods, exceptions etc.

# Exceptions

### Create instance of exception

This package contains a lot of exceptions. Each of them contains static method `create()` with proper arguments that is
used to create instance of the exception. Example:

```php
use Meritoo\Common\Exception\Bundle\IncorrectBundleNameException;

// ...

throw IncorrectBundleNameException::create('RisusIpsum');
```

# More

1. [Base test case (with common methods and data providers)](Base-test-case.md)
2. [Collection of elements](Collection/BaseCollection.md)
3. [Templates](Collection/Templates.md)
4. [**Exceptions**](Exceptions.md)
5. [Static methods](Static-methods.md)
    1. [Arrays](Static-methods/Arrays.md)
    2. [Regex](Static-methods/Regex.md)
    3. [Uri](Static-methods/Uri.md)
6. [Value Objects](Value-Objects.md)

[&lsaquo; Back to `Readme`](../README.md)
