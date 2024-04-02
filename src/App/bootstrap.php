<?php

declare(strict_types=1);

require_once(__DIR__ . '/../../vendor/autoload.php');

use Dotenv\Dotenv;
use Framework\App;
use App\Config\Paths;
use function App\Config\registerRoutes;
use function App\Config\registerMiddleware;

$dotenv = Dotenv::createImmutable(Paths::ROOT);
$dotenv->load();

$app = new App(Paths::SOURCE . 'App/container-definitions.php');

registerRoutes($app);
registerMiddleware($app);

return $app;