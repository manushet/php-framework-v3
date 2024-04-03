<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Database;
use Framework\Exceptions\ValidationException;

class UserService
{
    public function __construct(private Database $db)
    {
    }

    public function isEmailTaken(string $email)
    {
        $emailCont = $this->db->query(
            "SELECT COUNT(*) FROM users WHERE email=:email",
            [
                'email' => $email
            ]
        )->count();

        if ($emailCont > 0) {
            throw new ValidationException([
                'email' => ['Email already taken']
            ], 422);
        }
    }

    public function createUser($user)
    {
        $this->db->query(
            "INSERT INTO users(email, password, age, country, social_media_url) VALUES(:email, :password, :age, :country, :url)",
            $user
        );

        session_regenerate_id();

        $_SESSION['user'] = $this->db->id();
    }

    public function loginUser($user)
    {
        $find_user = $this->db->query(
            "SELECT * FROM users WHERE email=:email;",
            ['email' => $user['email']]
        )->findOne();

        if (!$find_user) {
            throw new ValidationException([
                'email' => ['Email not found']
            ], 422);
        }

        $passwordsMatch = password_verify($user['password'], $find_user->password);

        if (!$passwordsMatch) {
            throw new ValidationException([
                'password' => ['Invalid credentials']
            ], 422);
        }

        session_regenerate_id();

        $_SESSION['user'] = $find_user->id;
    }

    public function logout(): void
    {
        unset($_SESSION['user']);

        session_regenerate_id();
    }
}