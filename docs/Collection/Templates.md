# Meritoo Common Library

Common and useful classes, methods, exceptions etc.

# Templates

### Namespace

`Meritoo\Common\Collection\Templates`

### Info

Collection/storage of templates, instance of `Meritoo\Common\ValueObject\Template` class.

##### New instance

New instance can be created using:

1. Constructor:

	```php
    new Templates([
    	'first' => new Template('First name: %first_name%'),
        'last'  => new Template('Last name: %last_name%'),
    ]);
    ```

2. Static method `fromArray(array $templates)` - creates and returns the collection from given array

	```php
	Templates::fromArray([
    	'first' => 'First name: %first_name%',
        'last'  => 'Last name: %last_name%',
    ]);
	```

##### Methods

Has all methods of parent class `Meritoo\Common\Collection\Collection` + `findTemplate(string $index)` method that finds and returns template with given index.

Example of usage:

```php
$templates = new Templates([
	'first' => new Template('First name: %first_name%'),
    'last'  => new Template('Last name: %last_name%'),
]);

$template = $templates->findTemplate('first'); // new Template('First name: %first_name%')
```

Throws an `Meritoo\Common\Exception\ValueObject\Template\TemplateNotFoundException` exception if template with given index was not found.

# More

1. [Base test case (with common methods and data providers)](../Base-test-case.md)
2. [Collection of elements](Collection.md)
3. [**Templates**](Templates.md)
4. [Exceptions](../Exceptions.md)
5. [Static methods](../Static-methods.md)
   1. [Arrays](../Static-methods/Arrays.md)
   2. [Regex](../Static-methods/Regex.md)
6. [Value Objects](../Value-Objects.md)

[&lsaquo; Back to `Readme`](../../README.md)
