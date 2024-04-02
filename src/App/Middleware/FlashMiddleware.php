<?php

declare(strict_types=1);

namespace App\Middleware;

use Framework\Contracts\MiddlewareInterface;
use Framework\TemplateEngine;

class FlashMiddleware implements MiddlewareInterface
{
    public function __construct(private TemplateEngine $templateEngine)
    {
    }

    public function process(callable $next): void
    {
        $this->templateEngine->addGlobal('errors', $_SESSION['errors'] ?? []);

        unset($_SESSION['errors']);

        $this->templateEngine->addGlobal('oldFormData', $_SESSION['oldFormData'] ?? []);

        unset($_SESSION['oldFormData']);

        $next();
    }
}