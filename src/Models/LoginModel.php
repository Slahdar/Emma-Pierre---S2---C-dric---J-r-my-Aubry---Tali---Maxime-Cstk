<?php

namespace App\Models;

class LoginModel
{
    private $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getUserByUsername(string $username): ?array
    {
        $statement = $this->pdo->prepare("SELECT * FROM user WHERE username = :username");
        $statement->execute(['username' => $username]);

        return $statement->fetch(\PDO::FETCH_ASSOC);
    }

    public function getUserById(int $userId): ?array
    {
        $statement = $this->pdo->prepare("SELECT * FROM user WHERE user_id = :user_id");
        $statement->execute(['user_id' => $userId]);

        return $statement->fetch(\PDO::FETCH_ASSOC);
    }

    public function saveUserSession(int $userId, int $status): bool
    {
        $_SESSION['user_id'] = $userId;
        $_SESSION['status'] = $status;

        return true;
    }

    public function clearUserSession(): void
    {
        unset($_SESSION['user_id']);
        unset($_SESSION['status']);
    }
}