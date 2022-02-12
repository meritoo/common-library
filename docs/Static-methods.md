# Meritoo Common Library

Common and useful classes, methods, exceptions etc.

# Static methods

This package contains a lot of class with static methods, so usage is not so complicated. Just run the static method who
would you like to use. Example:

```php
use Meritoo\Common\Utilities\Arrays;

$firstElement = Arrays::getFirstElement(['lorem', 'ipsum']);
var_dump($firstElement); // string(5) "lorem"
```

# More

1. [Base test case (with common methods and data providers)](Base-test-case.md)
2. [Collection of elements](Collection/BaseCollection.md)
3. [Templates](Collection/Templates.md)
4. [Exceptions](Exceptions.md)
5. [**Static methods**](Static-methods.md)
    1. [Arrays](Static-methods/Arrays.md)
    2. [Regex](Static-methods/Regex.md)
    3. [Uri](Static-methods/Uri.md)
6. [Value Objects](Value-Objects.md)

[&lsaquo; Back to `Readme`](../README.md)
