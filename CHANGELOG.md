# Meritoo Common Library

Common and useful classes, methods, exceptions etc.

# 1.0.5

1. Collection > trait > return "void" where "self" causes type hinting problem and is not required

# 1.0.4

1. PHP Coding Standards Fixer > update configuration
2. Phing > tests > add task for Psalm (https://psalm.dev)
3. Collection > trait > split into smaller traits (to make it more flexible)

# 1.0.3

1. Travis CI > run many tasks using Phing > update configuration
2. Template with placeholders > verification of placeholders without values > make stronger and point out which are 
missing
3. Reflection > getPropertyValue() method > look for the property in parent classes

# 1.0.2

1. Phing > remove old and unused tools
2. Phing > configuration > minor updates
3. Implement Mutation Testing Framework (infection/infection package)
4. Travis CI > run many tasks using Phing (instead of PHPUnit only)
5. Fix integration with [Coveralls](https://www.coveralls.io) (available as the badge in [README.md](README.md))
6. Implement [PHPStan](https://github.com/phpstan/phpstan)
7. PHPUnit > execute tests in random order
8. Implement [Psalm](https://github.com/vimeo/psalm)
9. Infection (Mutation Testing Framework) > fix bugs while running (generate proper code coverage, bugs while running
tests randomly)
10. Phing > php-coveralls > add task

# 1.0.1

1. Regex > make compatible with PHP 7.3 Tests > Regex > fix "preg_match(): Compilation failed: invalid range in 
character class at offset 4" bug
2. Collection/storage of templates
3. Template with placeholders that may be filled by real data
4. RenderableInterface > something that may be rendered

# 1.0.0

1. Composer > support/require PHP 7.2+ (instead of 5.6+)

# 0.1.8

1. Size, e.g. of image

# 0.1.7

1. Collection > create trait (to make it more flexible)

# 0.1.6

1. Arrays > refactoring & more tests
2. ValueObject > Human > represents a human
3. Tests > use `Meritoo\Test\Common` namespace (instead of `Meritoo\Common\Test`)
4. Tests > use @dataProvider

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
