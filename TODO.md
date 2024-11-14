# Meritoo Common Library

## Things to do

- [ ] Bump PHP version: `8.0` -> `8.2`
- [ ] Run GitHub Actions for `8.2`, `8.3`, and `8.4` (do not run for `8.0` and `8.1`)
- [ ] Fix deprecations when running PHPUnit tests
- [ ] Replace all the `*Type` classes
  with [enumerations](https://www.php.net/manual/en/language.types.enumerations.php) (
  classes that extend `Meritoo\Common\Type\Base\BaseType`)

  **A new `enum`:**

  ```php
  <?php
  
  declare(strict_types=1);
  
  namespace Meritoo\Common\Enums;
  
  enum OopVisibility: string
  {
      case Private = '3';
      case Protected = '2';
      case Public = '1';
  }
  ```

  **A new piece of `[CHANGELOG.md](CHANGELOG.md)`:**

  6. Replace all the `*Type` classes
     with [enumerations](https://www.php.net/manual/en/language.types.enumerations.php) (
     classes that extend `Meritoo\Common\Type\Base\BaseType`)

     | Before                    | After                |
     |---------------------------|----------------------|
     | `class OopVisibilityType` | `enum OopVisibility` |
     | ...                       | ...                  |
     | ...                       | ...                  |

- [ ] Bump PHPStan level: `1` -> `2`
