<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;

class AboutController
{
    public function __construct(
        private TemplateEngine $templateEngine
    ) {
    }

    public function about(): void
    {
        echo $this->templateEngine->render("/about.php", [
            //'title' => 'About us',
            'maliciousData' => '<script>alert(123)</script>',
        ]);
    }
}