<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;

class ErrorController
{
    public function __construct(private TemplateEngine $templateEngine)
    {
    }

    public function notFound()
    {
        echo $this->templateEngine->render("errors/not-found.php");
    }
}