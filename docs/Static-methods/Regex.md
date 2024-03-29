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

##### Examples

1)

- value: non-scalar or `null`
- result: `false`

2)

- value: `""` (an empty string)
- result: `""` (an empty string)

3)

- value: `"Lorem ipsum. Dolor sit 12.34 amet."`
- result: `"lorem-ipsum-dolor-sit-1234-amet"`

### clearBeginningSlash(string): string

> Clears, removes slash from the beginning of given string

##### Arguments

- `string $string` - String that may contains slash as the 1st character

##### Examples

1)

- string: `"lorem ipsum"`
- result: `"lorem ipsum"`

2)

- string: `"/lorem ipsum"`
- result: `"lorem ipsum"`

3)

- string: `"/ lorem 123 ipsum"`
- result: `" lorem 123 ipsum"`

### clearEndingSlash(string): string

> Clears, removes slash from the end of given string

##### Arguments

- `string $string` - String that may contains slash as the last character

##### Examples

1)

- string: `"lorem ipsum"`
- result: `"lorem ipsum"`

2)

- string: `"lorem ipsum/"`
- result: `"lorem ipsum"`

3)

- string: `"lorem 123 ipsum /"`
- result: `"lorem 123 ipsum "`

# More

1. [Base test case (with common methods and data providers)](../Base-test-case.md)
2. [Collection of elements](../Collection/BaseCollection.md)
3. [Templates](../Collection/Templates.md)
4. [Exceptions](../Exceptions.md)
5. [Static methods](../Static-methods.md)
    1. [Arrays](../Static-methods/Arrays.md)
    2. [**Regex**](Regex.md)
    3. [Uri](Uri.md)
6. [Value Objects](../Value-Objects.md)

[&lsaquo; Back to `Readme`](../../README.md)
