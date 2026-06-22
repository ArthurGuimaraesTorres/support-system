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

        public function update(int $id, string $name, string $email, string $phone): bool
        {
            $stmt = $this->pdo->prepare("UPDATE users SET name = :name, email = :email, phone = :phone WHERE id = :id");

            return $stmt->execute([
                ":id"=> $id,
                ':name' => $name,
                ':email' => $email,
                ':phone' => $phone,
            ]);
        }

        public function updatePassword(int $id, string $password): bool
        {
            $stmt = $this->pdo->prepare("UPDATE users SET password = :password WHERE id = :id");

            return $stmt->execute([
                ':password' => password_hash($password, PASSWORD_DEFAULT),
                ':id' => $id
            ]);
        }

        public function findByEmail(string $email): ?array
        {
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->execute([':email' => $email]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        }

        public function findById(int $id): ?array
        {
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        }
    }