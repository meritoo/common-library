# Meritoo Common Library

Common and useful classes, methods, exceptions etc.

# 0.1.5

1. Tests > Date > one more test case
2. Phing > update configuration
3. Miscellaneous > variableDump() method > remove, because unnecessary
4. Regex > createSlug() method > returns slug for given value
5. Arrays > getNonEmptyValues() method > returns non-empty values, e.g. without "" (empty string), null or []
6. Arrays > getNonEmptyValuesAsString() method > returns non-empty values concatenated by given separator
7. ValueObject > Company > represents a company
8. ValueObject > BankAccount > represents bank account
9. ValueObject > Address > represents address of company, institution, user etc.

# 0.1.4

1. Phing > update configuration
2. Utilities > Date > update descriptions of methods
3. Docker > docker-compose.yml > add "phpunit" service > used to run PHPUnit's tests
4. Reflection > setPropertiesValues() method > sets values of properties in given object

# 0.1.3

1. Tests > refactoring & minor improvements
2. Utilities > CssSelector > useful methods related to CSS selectors
3. Utilities > Bootstrap4CssSelector > useful methods related to CSS selectors and the Bootstrap4 (front-end component library)

# 0.1.2

1. Documentation > Value Objects
2. Docker > improve performance
3. Utilities > Reflection > setPropertyValue() method > sets value of given property

# 0.1.1

1. TravisCI > run using PHP 7.2 too
2. ValueObject > class Version > represents version of software
3. Move version of this package to `VERSION` file (from `composer.json` file)

# 0.1.0

1. Composer > support/require PHP 5.6+ (instead of 5.5.9+)
2. Docker > rename `php-cli` service to `php`
3. Exceptions > create instance of exception using static `create()` method (instead of constructor)
4. Documentation > Exceptions

# 0.0.21

1. Composer > require ext-pcre
2. Arrays > minor refactoring
3. Update @author and @copyright in classes' descriptions

# 0.0.20

1. Collection > add() method > treat empty string as not provided index (same as null)

# 0.0.19

1. Add this changelog
2. Reorganize documentation & update [Readme](README.md)
3. Docker: use project-related binaries globally
4. StyleCI & PHP Coding Standards Fixer: update configuration
5. Documentation > Docker > add paragraph for PHP Coding Standards Fixer
6. Coding standard > fix automatically
7. StyleCI configuration > fix bug "The provided fixer 'binary_operator_spaces' cannot be enabled again because it was already enabled"
8. StyleCI > disable & remove
