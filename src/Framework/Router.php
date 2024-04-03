<?php

namespace Framework;

use Framework\Contracts\MiddlewareInterface;

class Router
{
    private array $routes = [];

    private array $middlewares = [];

    public function add(string $method, string $path, array $handler): void
    {
        $path = $this->normalizePath($path);

        $regexPath = preg_replace('#{[^/]+}#', '([^/]+)', $path);

        $this->routes[] = [
            'path' => $path,
            'method' => strtoupper($method),
            'handler' => $handler,
            'middlewares' => [],
            'regexPath' => $regexPath,
        ];
    }

    private function normalizePath(string $path): string
    {
        $path = "/{$path}/";

        $path = preg_replace('/[\/]+/', '/', $path);

        return strtolower($path);
    }

    public function dispatch(string $path, string $method, Container $container = null): void
    {
        $path = $this->normalizePath($path);

        $method = strtoupper($_POST['_METHOD'] ?? $method);

        foreach ($this->routes as $route) {
            if (
                !preg_match("#^{$route['regexPath']}$#", $path, $paramValues) ||
                $route['method'] !== $method
            ) {
                continue;
            }

            array_shift($paramValues);

            preg_match_all('#{([^/]+)}#', $route['path'], $paramKeys);

            $paramKeys = $paramKeys[1];

            $params = array_combine($paramKeys, $paramValues);

            [$className, $methodName] = $route['handler'];

            $controllerInstance = $container ?
                $container->resolve($className) :
                new $className;

            $action = fn () => $controllerInstance->{$methodName}($params);

            $allMiddlwares = [...$route['middlewares'], ...$this->middlewares];

            foreach ($allMiddlwares as $middleware) {
                /**
                 * @var MiddlewareInterface $middlewareInstance
                 */
                $middlewareInstance = $container ?
                    $container->resolve($middleware) :
                    new $middleware;

                $action = fn () => $middlewareInstance->process($action);
            }

            $action();

            return;
        }
    }

    public function addMiddleware(string $middleware)
    {
        $this->middlewares[] = $middleware;
    }

    public function addRouteMiddleware(string $middleware)
    {
        $lastRouteKey = array_key_last($this->routes);

        $this->routes[$lastRouteKey]['middlewares'][] = $middleware;
    }
}