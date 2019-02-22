# Meritoo Common Library

Common and useful classes, methods, exceptions etc.

# Regex

> Useful methods related to regular expressions

Class: `Meritoo\Common\Utilities\Regex`
File: `src/Utilities/Regex.php`

### createSlug($value)

> Returns slug for given value

##### Arguments

- `string $value` - Value that should be transformed to slug

##### Example 1

- value: non-scalar or `null`
- result: `false`

##### Example 2

- value: `""` (an empty string)
- result: `""` (an empty string)

##### Example 3

- value: `"Lorem ipsum. Dolor sit 12.34 amet."`
- result: `"lorem-ipsum-dolor-sit-1234-amet"`

# More

1. [Base test case (with common methods and data providers)](../Base-test-case.md)
2. [Collection of elements](../Collection-of-elements.md)
3. [Exceptions](../Exceptions.md)
4. [Static methods](../Static-methods.md)
   1. [Arrays](../Static-methods/Arrays.md)
   2. [**Regex**](Regex.md)
5. [Value Objects](../Value-Objects.md)

[&lsaquo; Back to `Readme`](../../README.md)
