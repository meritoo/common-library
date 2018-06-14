# Meritoo Common Library
Development-related information

# Getting started

### Docker

Build, create and start Docker's containers by running command:

```bash
docker-compose up -d
```

> [What is Docker?](https://www.docker.com/what-docker)

### Composer

Install packages by running command:

```bash
docker-compose run composer install
```

Update packages by running command:

```bash
docker-compose run composer update
```

### Tests

Rebuild project and run tests by running command:

```bash
docker-compose exec php-cli phing
```

Run tests only by running command:

```bash
docker-compose exec php-cli phpunit
```

[&lsaquo; Back to `Readme`](../README.md)
