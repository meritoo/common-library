# Meritoo Common Library

Development-related information

# Requirements

* [Docker](https://www.docker.com)
* Your favourite IDE :)

# Getting started

1. Build, create and start Docker's containers by running command:

    ```bash
    docker-compose up -d
    ```

2. Rebuild project by running command (installs packages, prepares required directories and runs tests):

	```bash
	docker-compose exec php phing
	```

> [What is Docker?](https://www.docker.com/what-docker)

# Composer

Available as `composer` service. You can run any Composer's command using the `composer` service:

```bash
docker-compose run --rm composer [command]
```

Examples below.

##### Install packages

```bash
docker-compose run --rm composer install
```

##### Update packages

```bash
docker-compose run --rm composer update
```

##### Add package

```bash
docker-compose run --rm composer require [vendor]/[package]
```

##### Remove package

```bash
docker-compose run --rm composer remove [vendor]/[package]
```

# Coding Standards Fixer

Fix coding standard by running command:

```bash
docker-compose exec php php-cs-fixer fix
```

or

```bash
docker-compose exec php phing -f phing/tests.xml build:fix-coding-standards
```

Omit cache and run the Fixer from scratch by running command:

```bash
docker-compose exec php rm .php_cs.cache && docker-compose exec php php-cs-fixer fix
```

> [Want more?](https://cs.sensiolabs.org)

# Tests

### Prerequisites

Install required packages by running command: `docker-compose run --rm composer install`.

### Running [PHPUnit](https://phpunit.de) tests

##### Easy (with code coverage)

```bash
docker-compose run --rm phpunit --verbose
```

or

```bash
docker-compose exec php phing -f phing/tests.xml test:phpunit
```

##### Quick (without code coverage)

```bash
docker-compose run --rm phpunit --verbose --no-coverage
```

# Versions of packages

### squizlabs/php_codesniffer

I have to use [squizlabs/php_codesniffer](https://packagist.org/packages/squizlabs/php_codesniffer) `^2.9` instead of
`^3.3`, because [Phing doesn't support 3.x PHP_CodeSniffer](https://github.com/phingofficial/phing/issues/716).

# Mutation Tests

Served by [Infection — Mutation Testing Framework](https://infection.github.io).

### Running tests

```bash
docker-compose exec php vendor/bin/infection --threads=5
```

or

```bash
docker-compose exec php phing -f phing/tests.xml test:infection
```

### Result of testing

##### Terminal

Example of output:
```bash
125 mutations were generated:
     105 mutants were killed
       3 mutants were not covered by tests
       5 covered mutants were not detected
       0 errors were encountered
      12 time outs were encountered

Metrics:
         Mutation Score Indicator (MSI): 93%
         Mutation Code Coverage: 97%
         Covered Code MSI: 95%
```

##### Stored in `build/reports/infection` directory

* `build/reports/infection/infection-log.txt`
* `build/reports/infection/summary-log.txt`

# Other

Rebuild project and run tests by running command:

```bash
docker-compose exec php phing
```

[&lsaquo; Back to `Readme`](../README.md)
