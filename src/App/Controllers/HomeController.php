<?php

declare(strict_types=1);

namespace App\Controllers;

use App\config\Paths;
use Framework\TemplateEngine;

class HomeController
{
    private TemplateEngine $templateEngine;

    public function __construct()
    {
        $this->templateEngine = new TemplateEngine(Paths::VIEW);
    }

    public function home(): void
    {
        $this->templateEngine->render("/index.php");
    }
}