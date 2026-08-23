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

## Why this exists

A router that only answers *which controller* leaves the interesting cases to whoever calls it.
A path nobody registered and a path registered for a different method are different answers, and
an API that returns `404` for both is lying about one of them. `HEAD` is answered by whatever
answers `GET`, unless somebody registered `HEAD` separately. And a route that requires
authentication should say so where it is declared, not in the controller where it can be
forgotten.

So dispatching here gives back a route object rather than a match: it knows whether it succeeded,
which methods a known path does allow, what the parameters matched, and whether reaching it
requires anybody. **One middleware enforces that last one**, and a guarded route in an
application with nothing to enforce it refuses to boot — see
[quillstack/auth](https://github.com/quillstack/auth).

## Requirements

- PHP 8.1 or newer

## Installation

```shell
composer require quillstack/router
```

## Usage

### Registering

```php
use Quillstack\Router\Router;

$router = new Router();
```

It needs nothing to be built, and neither does the dispatcher: `new Dispatcher($router)`.


```php
$router->get('/', HomeController::class)->name('home');
$router->post('/users', CreateUserController::class)->name('users.create');
$router->delete('/users/:id', DeleteUserController::class)->name('users.delete');
```

`get()`, `post()`, `put()`, `patch()`, `delete()`, `options()` and `head()` register one
method. `map('GET', …)` takes the method as an argument, `match(['PUT', 'PATCH'], …)` takes a
few of them, and `any()` takes them all.

### Parameters

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

### Naming, and finding by name

```php
$router->get('/users/:id', UserController::class)->name('users.show');

$router->getRoute('users.show')->getPath();   // '/users/:id'
$router->getRoutes();                          // every route, keyed by `METHOD /path`
```

### Guarding a route

A route says what reaching it requires, and one place enforces it — a rule kept in each
controller instead is a rule which is one day not kept:

```php
$router->get('/orders', OrdersController::class)->requireAuthentication();
$router->delete('/orders/:id', DeleteOrderController::class)->requireAuthentication('admin');
$router->match(['PUT', 'PATCH'], '/orders/:id', UpdateOrderController::class)
    ->requireAuthentication('admin', 'support');
```

Any one of the roles will do. Nothing is guarded unless it says so, so this changed no route
anybody had already written.

A route which says this implements `GuardedRouteInterface`, which is kept apart from
`RouteInterface` for the same reason: something implementing a route without answering these
is a route nobody guards, which is what every route was before.

Enforcing it is [quillstack/auth](https://github.com/quillstack/auth)'s job — this package
only carries what was asked for.

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

## Technical documentation

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

## Benchmark

Measured with [quillstack/benchmark](https://github.com/quillstack/benchmark) on forty routes —
five per resource across eight resources, most of them carrying a parameter — matching
`GET /projects/42`. All four find the same route and read the same `id`. Runs are interleaved
and unconcurrent, each figure is the median of five, and PHP is 8.5.7.

| | Version |
| --- | --- |
| quillstack/router | v0.8.0 |
| nikic/fast-route | 1.3.1 |
| symfony/routing | v7.4.17 |
| league/route | 6.2.0 |

**Registering forty routes and matching one, in a fresh process:**

| | Time | Relative |
| --- | --- | --- |
| nikic/fast-route | 3.10 ms | 0.47× |
| symfony/routing | 4.91 ms | 0.74× |
| **quillstack/router** | **6.60 ms** | — |
| league/route | 12.67 ms | 1.9× |

**Matching alone**, from a router already built:

| | Per match | Relative |
| --- | --- | --- |
| nikic/fast-route | 1.09 µs | 0.35× |
| symfony/routing, compiled and dumped | 2.11 µs | 0.67× |
| **quillstack/router** | **3.13 µs** | — |
| league/route | 7.61 µs | 2.4× |
| symfony/routing, uncompiled | 22.9 µs | 7.3× |

**`nikic/fast-route` is three times faster at matching and this one is third of four**, which is
worth stating rather than arranging around. It is also a different job: fast-route takes a method
string and a path string and gives back an array. This takes a PSR-7 request and gives back a
route that knows whether it matched, which methods the path allows if it did not, and whether
reaching it needs authentication — and that request has to be built and read, which is most of
the gap.

The last row is Symfony's matcher without its dump, which is a build step this one does not have;
the row above it is the fair one.

## Tests

```shell
composer test
composer test:coverage
composer stan
```

## The rest of Quillstack

This is one component of [Quillstack](https://github.com/quillstack), a PHP framework which is
as simple to use as it is strict about what it does.

- [quillstack/middleware](https://github.com/quillstack/middleware) — what calls this
- [quillstack/auth](https://github.com/quillstack/auth) — what enforces a guarded route
- [quillstack/server-request](https://github.com/quillstack/server-request) — what is dispatched
- [quillstack/framework](https://github.com/quillstack/framework) — where it is all wired up

## License

MIT. See [LICENSE](LICENSE).
