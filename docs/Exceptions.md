# Meritoo Common Library

Common and useful classes, methods, exceptions etc.

# Exceptions

### Create instance of exception

This package contains a lot of exceptions. Each of them contains static method `create()` with proper arguments that is
used to create instance of the exception. Example:

```php
use Meritoo\Common\Exception\Bundle\IncorrectBundleNameException;
throw IncorrectBundleNameException::create('RisusIpsum');
```

### Base exception for unknown type of something

##### Short description

It's a `Meritoo\Common\Exception\Base\UnknownTypeException` class. Related to `Meritoo\Common\Type\Base\BaseType` class
that represents type of something, e.g. type of button, order.

##### Usage

You can extend `Meritoo\Common\Exception\Base\UnknownTypeException` class and create your own static method,
e.g. `createException()`, which will be used create instance of the exception. Inside the `createException()` method you
can call `parent::create()` method.

##### Example

```php
<?php

namespace Your\Package\Exception\Type;

use Meritoo\Common\Exception\Base\UnknownTypeException;
use Your\Package\Type\SimpleType;

class UnknownSimpleTypeException extends UnknownTypeException
{
    /**
     * Creates exception
     *
     * @param string $unknownType Unknown and simple type
     * @return UnknownSimpleTypeException
     */
    public static function createException($unknownType)
    {
        /* @var UnknownSimpleTypeException $exception */
        $exception = parent::create($unknownType, new SimpleType(), 'my simple type of something');

        return $exception;
    }
}
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
