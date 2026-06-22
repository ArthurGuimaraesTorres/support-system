<?php

    class User
    {
        private PDO $pdo;

        public function __construct(PDO $pdo)
        {
            $this->pdo = $pdo;
        }

        public function create(string $name, string $email, string $phone, string $password): bool
        {
            $stmt = $this->pdo->prepare("INSERT INTO users (name, email, phone, password) VALUES (:name, :email, :phone, :password)");
            return $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':phone' => $phone,
                ':password' => password_hash($password, PASSWORD_DEFAULT)
            ]);
        }

        public function findByEmail(string $email): ?array
        {
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->execute([':email' => $email]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        }
    }