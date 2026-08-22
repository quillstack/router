# Quillstack Router

[![Tests](https://github.com/quillstack/router/actions/workflows/tests.yml/badge.svg)](https://github.com/quillstack/router/actions/workflows/tests.yml)
[![Latest Version](https://img.shields.io/packagist/v/quillstack/router.svg)](https://packagist.org/packages/quillstack/router)
[![Downloads](https://img.shields.io/packagist/dt/quillstack/router.svg)](https://packagist.org/packages/quillstack/router)
[![PHP Version](https://img.shields.io/packagist/php-v/quillstack/router)](https://packagist.org/packages/quillstack/router)
[![StyleCI](https://github.styleci.io/repos/291464195/shield?branch=main)](https://github.styleci.io/repos/291464195?branch=main)
[![CodeFactor](https://www.codefactor.io/repository/github/quillstack/router/badge)](https://www.codefactor.io/repository/github/quillstack/router)
[![License](https://img.shields.io/packagist/l/quillstack/router)](https://github.com/quillstack/router/blob/main/LICENSE)

The routing library working with PSR-7 requests.

### Dispatching

`Dispatcher::dispatch()` answers with the route that matched, or with one of two routes
standing for having matched nothing:

- `NotFoundRoute` — nothing is registered for this path.
- `MethodNotAllowedRoute` — the path is registered, but not for this method.
  `getAllowedMethods()` names the ones it does answer to, which is what a 405 has to carry.

Both say `false` to `isSuccess()`, so anything only asking whether it matched keeps working.

A path registered for `GET` also answers `HEAD`, which is what a server offering GET is
expected to do — registering `head()` for the path still wins where it was done.

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