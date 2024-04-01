<?php

declare(strict_types=1);

namespace Framework;

use Framework\Container;

class App
{
    private Router $router;

    private Container $container;

    public function __construct(string $containerDefinitionsPath = null)
    {
        $this->router = new Router();
        $this->container = new Container();

        if (!empty($containerDefinitionsPath)) {
            $containerDefinitionsPath = require($containerDefinitionsPath);
            $this->container->addDefinition(($containerDefinitionsPath));
        }
    }

    public function run()
    {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $method = $_SERVER['REQUEST_METHOD'];

        $this->router->dispatch($path, $method, $this->container);
    }

    public function get(string $path, array $handler): void
    {
        $this->router->add('GET', $path, $handler);
    }

    public function post(string $path, array $handler): void
    {
        $this->router->add('POST', $path, $handler);
    }
}