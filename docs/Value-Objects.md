# Meritoo Common Library

Common and useful classes, methods, exceptions etc.

# Value Objects

Located in `Meritoo\Common\ValueObject` namespace.

### Version

##### Namespace

`Meritoo\Common\ValueObject\Version`

##### Info

Represents version of software. Contains 3 properties:
1. `$majorPart` - the "major" part of version
2. `$minorPart` - the "minor" part of version
3. `$patchPart` - the "patch" part of version

##### New instance

New instance can be created using:

1. Constructor:

	```php
    new Version(1, 0, 2);
    ```

2. Static methods:
	1. `fromArray()` - creates new instance using given version as array

	```php
	Version::fromArray([1, 0, 2]);
	```

	2. `fromString()` - creates new instance using given version as string:

	```php
    Version::fromString('1.0.2');
    ```

# More

1. [Base test case (with common methods and data providers)](Base-test-case.md)
2. [Collection of elements](Collection-of-elements.md)
3. [Exceptions](Exceptions.md)
4. [Static methods](Static-methods.md)
5. [**Value Objects**](Value-Objects.md)

[&lsaquo; Back to `Readme`](../README.md)
