# Meritoo Common Library

Common and useful classes, methods, exceptions etc.

# Arrays

> Useful methods related to arrays

Class: `Meritoo\Common\Utilities\Arrays`
File: `src/Utilities/Arrays.php`

### containsEmptyStringsOnly(array): bool

> Returns information if given array contains an empty strings only

##### Arguments

- `array $array` - The array to verify

##### Examples

1)

  - array: `[]` (an empty array)
  - result: `false`

2)
  - array: `["", -1]`
  - result: `false`

3)
  - array: `["", null, ""]`
  - result: `true`

### getNonEmptyValues(array $values)

> Returns non-empty values, e.g. without "" (empty string), null or []

##### Arguments

- `array $values` - The values to filter

##### Examples

1)

  - values: `[]` (no values)
  - result: `[]` (an empty array)

2)

  - values: `[null, ""]` (all empty values)
  - result: `[]` (an empty array)

3)

  - values: `["test 1", "", 123, null, 0]`
  - result: `["test 1", 123, 0]`

### getNonEmptyValuesAsString(array $values, $separator = ', ')

> Returns non-empty values concatenated by given separator

##### Arguments

- `array $values` - The values to filter
- `[string $separator]` - (optional) Separator used to implode the values. Default: ", ".

##### Examples

1)

  - values: `[]` (no values)
  - separator: default or any other string
  - result: `""` (an empty string)

2)

  - values: `[null, ""]` (all empty values)
  - separator: default or any other string
  - result: `""` (an empty string)

3)

  - values: `["test 1", "", 123, null, 0]`
  - separator: `", "` (default)
  - result: `"test 1, 123, 0"`

4)

  - values: `["test 1", "", 123, null, 0]`
  - separator: `" | "`
  - result: `"test 1 | 123 | 0"`

# More

1. [Base test case (with common methods and data providers)](../Base-test-case.md)
2. [Collection of elements](../Collection/Collection.md)
3. [Templates](../Collection/Templates.md)
4. [Exceptions](../Exceptions.md)
5. [Static methods](../Static-methods.md)
   1. [**Arrays**](Arrays.md)
   2. [Regex](Regex.md)
6. [Value Objects](../Value-Objects.md)

[&lsaquo; Back to `Readme`](../../README.md)
