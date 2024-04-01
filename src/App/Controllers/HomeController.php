<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;

class HomeController
{
    public function __construct(
        private TemplateEngine $templateEngine
    ) {
    }

    public function home(): void
    {
        echo $this->templateEngine->render("/index.php", [
            'title' => 'Home page'
        ]);
    }
}