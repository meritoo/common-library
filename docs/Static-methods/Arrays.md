# Meritoo Common Library

Common and useful classes, methods, exceptions etc.

# Arrays

> Useful methods related to arrays

Class: `Meritoo\Common\Utilities\Arrays`
File: `src/Utilities/Arrays.php`

### getNonEmptyValues(array $values)

> Returns non-empty values, e.g. without "" (empty string), null or []

##### Arguments

- `array $values` - The values to filter

##### Example 1

- values: `[]` (no values)
- result: `[]` (an empty array)

##### Example 2

- values: `[null, ""]` (all empty values)
- result: `[]` (an empty array)

##### Example 3

- values: `["test 1", "", 123, null, 0]`
- result: `["test 1", 123, 0]`

### getNonEmptyValuesAsString(array $values, $separator = ', ')

> Returns non-empty values concatenated by given separator

##### Arguments

- `array $values` - The values to filter
- `[string $separator]` - (optional) Separator used to implode the values. Default: ", ".

##### Example 1

- values: `[]` (no values)
- separator: default or any other string
- result: `""` (an empty string)

##### Example 2

- values: `[null, ""]` (all empty values)
- separator: default or any other string
- result: `""` (an empty string)

##### Example 3

- values: `["test 1", "", 123, null, 0]`
- separator: `", "` (default)
- result: `"test 1, 123, 0"`

##### Example 4

- values: `["test 1", "", 123, null, 0]`
- separator: `" | "`
- result: `"test 1 | 123 | 0"`

# More

1. [Base test case (with common methods and data providers)](../Base-test-case.md)
2. [Collection of elements](../Collection-of-elements.md)
3. [Exceptions](../Exceptions.md)
4. [Static methods](../Static-methods.md)
   1. [**Arrays**](Arrays.md)
   2. [Regex](Regex.md)
5. [Value Objects](../Value-Objects.md)

[&lsaquo; Back to `Readme`](../../README.md)
