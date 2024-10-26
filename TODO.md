# Meritoo Common Library

## Things to do

### Fix PHPStan errors

- [ ] `Undefined variable: $isEqual` - replace `eval()` with callable
  in [src/Utilities/Regex.php:151](./src/Utilities/Regex.php)
- [ ] `Unsafe usage of new static()` - chose one
  of [possible solutions](https://phpstan.org/blog/solving-phpstan-error-unsafe-usage-of-new-static) -
  in [src/Type/Base/BaseType.php:37](./src/Type/Base/BaseType.php)
- [ ] `Unsafe usage of new static()` - chose one
  of [possible solutions](https://phpstan.org/blog/solving-phpstan-error-unsafe-usage-of-new-static) -
  in [src/Exception/Base/UnknownTypeException.php:40](./src/Exception/Base/UnknownTypeException.php)
- [ ] Clean and remove the [phpstan-baseline.neon](phpstan-baseline.neon) file finally

### Refactoring

- [ ] Replace `Meritoo\Common\Type\OopVisibilityType` class
  with [PHP enum](https://www.php.net/manual/en/language.types.enumerations.php)
