<?php

declare(strict_types=1);

namespace App\Config;

use App\Middleware\FlashMiddleware;
use Framework\App;
use App\Middleware\TemplateDataMiddleware;
use App\Middleware\ValidationExceptionMiddleware;
use App\Middleware\SessionMiddleware;

function registerMiddleware(App $app)
{
    $app->addMiddleware(TemplateDataMiddleware::class);
    $app->addMiddleware(ValidationExceptionMiddleware::class);
    $app->addMiddleware(FlashMiddleware::class);
    $app->addMiddleware(SessionMiddleware::class);
}