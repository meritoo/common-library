# Meritoo Common Library

Common and useful classes, methods, exceptions etc.

# Uri

> Useful methods related to uri

Class: `Meritoo\Common\Utilities\Uri`
File: `src/Utilities/Uri.php`

### buildUrl(string, string ...): string

> Builds url with given root url and parts of url (concatenates them using "/")

##### Arguments

- `string $rootUrl` - Protocol and domain (or domain only)
- `string ...$urlParts` - Parts of url that will be concatenated with the rool url by "/"

##### Examples

1)

  - rootUrl: `"http://my.example"`
  - urlParts: `""` (an empty string)
  - result: `"http://my.example"`

2)

  - rootUrl: `"http://my.example"`
  - urlParts: `"/test", "/123"`
  - result: `"http://my.example/test/123"`

# More

1. [Base test case (with common methods and data providers)](../Base-test-case.md)
2. [Collection of elements](../Collection/Collection.md)
3. [Templates](../Collection/Templates.md)
4. [Exceptions](../Exceptions.md)
5. [Static methods](../Static-methods.md)
   1. [Arrays](Arrays.md)
   2. [Regex](Regex.md)
   3. [**Uri**](Uri.md)
6. [Value Objects](../Value-Objects.md)

[&lsaquo; Back to `Readme`](../../README.md)
