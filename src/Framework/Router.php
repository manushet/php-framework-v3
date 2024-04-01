<?php

namespace Framework;

class Router
{
    private array $routes = [];

    public function add(string $method, string $path, array $handler): void
    {
        $this->routes[] = [
            'path' => $this->normalizePath($path),
            'method' => strtoupper($method),
            'handler' => $handler,
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

        $method = strtoupper($method);

        foreach ($this->routes as $route) {
            if (
                !preg_match("#^{$route['path']}$#", $path, $matches) ||
                $route['method'] !== $method
            ) {
                continue;
            }

            [$className, $methodName] = $route['handler'];

            $controllerInstance = $container ?
                $container->resolve($className) :
                new $className;

            $controllerInstance->{$methodName}();
        }
    }
}