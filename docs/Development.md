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

2. Install packages by running command:

    ```bash
    docker-compose run composer install
    ```

> [What is Docker?](https://www.docker.com/what-docker)

# Tests

### Prerequisites

Install required packages by running command: `docker-compose run composer install`.

### Running tests

#### Simply & quick, without code coverage

Tests are running using Docker and `php-cli` service defined in `docker-compose.yml`. Example:

```bash
docker-compose exec php-cli phpunit --no-coverage
```

You can also run them in container. In this case you have to run 2 commands:
1. Enter container:

    ```bash
	docker-compose exec php-cli bash
    ```

2. Run tests:

    ```bash
    phpunit --no-coverage
    ```

#### With code coverage

```bash
docker-compose exec php-cli phpunit
```

# Other

Rebuild project and run tests by running command:

```bash
docker-compose exec php-cli phing
```

[&lsaquo; Back to `Readme`](../README.md)
