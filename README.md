# Quillstack Router

[![Build Status](https://app.travis-ci.com/quillstack/router.svg?branch=master)](https://app.travis-ci.com/quillstack/router)
[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=quillstack_router&metric=alert_status)](https://sonarcloud.io/dashboard?id=quillstack_router)
[![Downloads](https://img.shields.io/packagist/dt/quillstack/router.svg)](https://packagist.org/packages/quillstack/router)
[![StyleCI](https://github.styleci.io/repos/291464195/shield?branch=main)](https://github.styleci.io/repos/291464195?branch=main)
[![CodeFactor](https://www.codefactor.io/repository/github/quillstack/router/badge)](https://www.codefactor.io/repository/github/quillstack/router)
[![Maintainability Rating](https://sonarcloud.io/api/project_badges/measure?project=quillstack_router&metric=sqale_rating)](https://sonarcloud.io/dashboard?id=quillstack_router)
[![Lines of Code](https://sonarcloud.io/api/project_badges/measure?project=quillstack_router&metric=ncloc)](https://sonarcloud.io/dashboard?id=quillstack_router)
[![Coverage](https://sonarcloud.io/api/project_badges/measure?project=quillstack_router&metric=coverage)](https://sonarcloud.io/dashboard?id=quillstack_router)
![Packagist License](https://img.shields.io/packagist/l/quillstack/router)

The routing library working with PSR-7 requests.

### Unit tests

Run tests using a command:

```
phpdbg -qrr ./vendor/bin/unit-tests
```

### Docker

```shell
$ docker-compose up -d
$ docker exec -w /var/www/html -it quillstack_router sh
```