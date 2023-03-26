# Meritoo Common Library

Common and useful classes, methods, exceptions etc.

# 1.2.1

1. Bump packages versions to the latest stable and supported by PHP `8.0`+

# 1.2.0

1. Support PHP `8.0`+

# 1.1.9

1. Remove old, unnecessary methods. Implement setUp() methods. Increase code coverage.

# 1.1.8

1. [Composer] Upgrade all dev packages (to the latest, stable versions for PHP `7.4`)
2. [BaseCollection] Interfaces of different types of collections. May be used to build specific collections.

# 1.1.7

1. [Arrays] Allow to define a key of next level elements in a function that returns elements from given level

# 1.1.6

1. [Arrays] Function that returns elements from given level

# 1.1.5

1. [BaseCollection] Prepare elements while adding them by `addMultiple()` method in the same way as passing them in
   constructor.

# 1.1.4

1. [BaseCollection] Fix incorrectly working limit() method

# 1.1.3

1. Move `Renderable` class: `Meritoo\Common` -> `Meritoo\Common\Contract`
2. Create and implement `CollectionInterface` as contract of all collections (e.g. based on the `BaseCollection` class)

# 1.1.2

1. Change mode of `Xdebug` to `coverage` in Docker's configuration to make it possible to generate code coverage by
   `PHPUnit`
2. Mark PHPUnit test as risky when it does not have a `@covers` annotation

# 1.1.1

1. [BaseCollection] Treat the `null` index as "no index" only while adding new element, iow. do not treat empty string
   as "no index" behaviour.
2. [Miscellaneous] [Regex] Use simpler & stronger pattern to match name of file
3. Do not install `hirak/prestissimo` package while running Travis CI (incompatible with your PHP version, PHP
   extensions and Composer version)
4. Use PHP `7.4` while running build in Travis CI

# 1.1.0

1. Rename Meritoo\Common\Collection\Collection class to Meritoo\Common\Collection\BaseCollection. Add BaseCollection::
   isValidType() method to validate type of element before add it to collection. Add BaseCollection ::prepareElements()
   method to allow preparation of elements in custom way.

# 1.0.6

1. Use `.env` instead of `.env.dist`
2. Docker > use images (instead of Dockerfiles)
3. composer > squizlabs/php_codesniffer package > use ^3.4 (instead of ^2.9)
4. Do not require name of class by BaseTestCaseTrait::assertMethodVisibilityAndArguments() method
5. PHP CS Fixer > configuration > make more readable & remove unnecessary code
6. Update .gitignore, docker-compose.yml, phpunit.xml.dist

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
3. Utilities > Bootstrap4CssSelector > useful methods related to CSS selectors and the Bootstrap4 (front-end component
   library)

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
7. StyleCI configuration > fix bug "The provided fixer 'binary_operator_spaces' cannot be enabled again because it was
   already enabled"
8. StyleCI > disable & remove
