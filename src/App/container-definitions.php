<?php

declare(strict_types=1);

use App\config\Paths;
use App\Services\ValidatorService;
use Framework\Database;
use Framework\TemplateEngine;

return [
    TemplateEngine::class => fn () => new TemplateEngine(Paths::VIEW),
    ValidatorService::class => fn () => new ValidatorService(),
    Database::class, fn () => new Database(
        getenv('DB_DRIVER'),
        [
            'host' => getenv('DB_HOST'),
            'port' => getenv('DB_PORT'),
            'dbname' => getenv('DB_DBNAME'),
        ],
        getenv('DB_USER'),
        getenv('DB_PASSWORD'),
    ),
];