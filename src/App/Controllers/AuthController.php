<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\UserService;
use Framework\TemplateEngine;
use App\Services\ValidatorService;

class AuthController
{
    public function __construct(
        private TemplateEngine $templateEngine,
        private ValidatorService $validatorService,
        private UserService $userService
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
        $this->validatorService->validateRegister($_POST);

        $this->userService->isEmailTaken($_POST['email']);

        $user = [
            'email' => strtolower($_POST['email']),
            'age' => $_POST['age'],
            'country' => $_POST['country'],
            'url' => strtolower($_POST['social-media-url']),
            'password' => password_hash($_POST['password'], PASSWORD_BCRYPT, ['cost' => 12]),
        ];

        $this->userService->createUser($user);

        redirectTo('/');
    }

    public function loginView(): void
    {
        echo $this->templateEngine->render("/login.php", [
            'title' => 'Login Page'
        ]);
    }

    public function login(): void
    {
        $this->validatorService->validateLogin($_POST);

        $user = [
            'email' => strtolower($_POST['email']),
            'password' => $_POST['password'],
        ];

        $this->userService->loginUser($user);

        redirectTo('/');
    }

    public function logout()
    {
        $this->userService->logout();

        redirectTo('/login');
    }
}