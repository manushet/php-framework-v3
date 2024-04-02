<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\ValidatorService;
use Framework\TemplateEngine;

class AuthController
{
    public function __construct(
        private TemplateEngine $templateEngine,
        private ValidatorService $validatorService
    ) {
    }

    public function registerView(): void
    {
        echo $this->templateEngine->render("/register.php", [
            'title' => 'Registration Page'
        ]);
    }

    public function register(): void
    {
        $post = $_POST;

        $this->validatorService->validateRegister($post);

        echo $this->templateEngine->render("/register.php", [
            'title' => 'Register New Account',
        ]);
    }
}