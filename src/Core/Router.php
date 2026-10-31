<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Registra le rotte e le smista. Il permesso di una rotta è:
 *  - null            → pubblica
 *  - 'auth'          → serve solo il login
 *  - ['modulo','az'] → serve quel permesso (controllo lato server)
 */
final class Router
{
    private array $routes = [];

    public function add(string $method, string $pattern, array $handler, string|array|null $permission = 'auth'): void
    {
        $regex = '#^' . preg_replace_callback('#\{(\w+)\}#', static fn(array $m): string => in_array($m[1], ['token', 'code'], true) ? '(?P<' . $m[1] . '>[^/]+)' : '(?P<' . $m[1] . '>\d+)', $pattern) . '$#';
        $this->routes[] = compact('method', 'regex', 'handler', 'permission');
    }

    public function get(string $pattern, array $handler, string|array|null $permission = 'auth'): void
    {
        $this->add('GET', $pattern, $handler, $permission);
    }

    public function post(string $pattern, array $handler, string|array|null $permission = 'auth'): void
    {
        $this->add('POST', $pattern, $handler, $permission);
    }

    public function dispatch(Context $ctx): Response
    {
        $request = $ctx->request;
        foreach ($this->routes as $route) {
            if ($route['method'] !== $request->method() || !preg_match($route['regex'], $request->path(), $m)) {
                continue;
            }
            $this->guard($route, $ctx);
            $params = array_map(static fn(string $value): int|string => ctype_digit($value) ? (int) $value : $value, array_filter($m, 'is_string', ARRAY_FILTER_USE_KEY));
            [$class, $action] = $route['handler'];
            return (new $class($ctx))->$action(...array_values($params));
        }
        throw new HttpException(404);
    }

    private function guard(array $route, Context $ctx): void
    {
        if ($route['method'] === 'POST' && !$ctx->csrf->verify($ctx->request->post('_csrf'))) {
            throw new HttpException(419, 'Sessione scaduta, ricarica la pagina.');
        }
        $permission = $route['permission'];
        if ($permission === null) {
            return;
        }
        if (!$ctx->auth->check()) {
            throw new HttpException(401);
        }
        if (is_array($permission) && !$ctx->auth->can($permission[0], $permission[1])) {
            throw new HttpException(403);
        }
    }
}
