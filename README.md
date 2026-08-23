# Quillstack Router

[![Tests](https://github.com/quillstack/router/actions/workflows/tests.yml/badge.svg)](https://github.com/quillstack/router/actions/workflows/tests.yml)
[![Latest Version](https://img.shields.io/packagist/v/quillstack/router.svg)](https://packagist.org/packages/quillstack/router)
[![Downloads](https://img.shields.io/packagist/dt/quillstack/router.svg)](https://packagist.org/packages/quillstack/router)
[![PHP Version](https://img.shields.io/packagist/php-v/quillstack/router)](https://packagist.org/packages/quillstack/router)
[![StyleCI](https://github.styleci.io/repos/291464195/shield?branch=main)](https://github.styleci.io/repos/291464195?branch=main)
[![CodeFactor](https://www.codefactor.io/repository/github/quillstack/router/badge)](https://www.codefactor.io/repository/github/quillstack/router)
[![Quality Gate](https://sonarcloud.io/api/project_badges/measure?project=quillstack_router&metric=alert_status)](https://sonarcloud.io/summary/new_code?id=quillstack_router)
[![Coverage](https://sonarcloud.io/api/project_badges/measure?project=quillstack_router&metric=coverage)](https://sonarcloud.io/summary/new_code?id=quillstack_router)
[![Maintainability](https://sonarcloud.io/api/project_badges/measure?project=quillstack_router&metric=sqale_rating)](https://sonarcloud.io/summary/new_code?id=quillstack_router)
[![Reliability](https://sonarcloud.io/api/project_badges/measure?project=quillstack_router&metric=reliability_rating)](https://sonarcloud.io/summary/new_code?id=quillstack_router)
[![Security](https://sonarcloud.io/api/project_badges/measure?project=quillstack_router&metric=security_rating)](https://sonarcloud.io/summary/new_code?id=quillstack_router)
[![License](https://img.shields.io/packagist/l/quillstack/router)](https://github.com/quillstack/router/blob/main/LICENSE)

The routing library working with [PSR-7](https://www.php-fig.org/psr/psr-7/) requests. Full
documentation: https://quillstack.org/router

Routes are registered once and matched against the request. A path without parameters is found
by a straight lookup; one with them walks a tree, so adding routes does not make matching
slower in the way a list of patterns tried in turn does.

### Requirements

- PHP 8.1 or newer

### Installation

```shell
composer require quillstack/router
```

### Usage

#### Registering

```php
$router->get('/', HomeController::class)->name('home');
$router->post('/users', CreateUserController::class)->name('users.create');
$router->delete('/users/:id', DeleteUserController::class)->name('users.delete');
```

`get()`, `post()`, `put()`, `patch()`, `delete()`, `options()` and `head()` register one
method. `map('GET', …)` takes the method as an argument, `match(['PUT', 'PATCH'], …)` takes a
few of them, and `any()` takes them all.

#### Parameters

A segment written as `:id` or as `{id}` is a parameter:

```php
$router->get('/users/:user/posts/:post', UserPostController::class)->name('user.post');
```

The values come back on the route, and the framework puts them on the request as attributes:

```php
$route = $dispatcher->dispatch($request);

$route->getParameters();          // ['user' => '42', 'post' => '7']
$route->getParameter('user');     // '42'
$route->getParameter('page', '1') // '1' — nothing matched, so the default
```

A literal segment always wins over a parameter, whichever was registered first:

```php
$router->get('/users/me', MeController::class);
$router->get('/users/:id', UserController::class);

// GET /users/me   → MeController
// GET /users/42   → UserController
```

#### Naming, and finding by name

```php
$router->get('/users/:id', UserController::class)->name('users.show');

$router->getRoute('users.show')->getPath();   // '/users/:id'
$router->getRoutes();                          // every route, keyed by `METHOD /path`
```

### Dispatching

`Dispatcher::dispatch()` answers with the route that matched, or with one of two standing for
having matched nothing:

| Route | Means |
| --- | --- |
| `NotFoundRoute` | nothing is registered for this path |
| `MethodNotAllowedRoute` | the path is registered, but not for this method |

Both say `false` to `isSuccess()`, so anything only asking whether something matched keeps
working. `MethodNotAllowedRoute::getAllowedMethods()` names the methods the path does answer
to, which is what a `405` has to carry.

A path registered for `GET` also answers `HEAD`, which is what a server offering GET is
expected to do — registering `head()` for the path still wins where it was done.

Dispatching reads the path out of the URI, so a query string does not turn a known route into
a 404.

### Technical documentation

| Class | What it is |
| --- | --- |
| `Router` | where routes are registered, and what holds them |
| `Dispatcher` | matches a PSR-7 request against them |
| `Route` | one registered route |
| `Routes\NotFoundRoute`, `Routes\MethodNotAllowedRoute` | what matching nothing means |
| `RouteTree\RouteTreeBuilder`, `RouteTree\RouteTreeFinder` | the tree paths with parameters are matched in |

`Router::normalisePath()` brings a path to the form used as a key: a leading slash and no
trailing one, so `/users/` and `users` are the same route.

`RouteNameNotSetException` is thrown by `name()` when there is no route to name — that is,
when it is called before any routing method.

### Unit tests

```shell
composer test
composer test:coverage
composer stan
```

### Docker

```shell
docker-compose up -d
docker exec -w /var/www/html -it quillstack_router sh
```

### License

MIT. See [LICENSE](LICENSE).
