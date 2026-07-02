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

        public function getAdminStats(): array
        {
            $sql = "SELECT
                        COUNT(*) AS total,
                        SUM(role = 'customer') AS customers,
                        SUM(role = 'technician') AS technicians,
                        SUM(role = 'admin') AS admins
                    FROM users";

            $stmt = $this->pdo->query($sql);

            return $stmt->fetch(PDO::FETCH_ASSOC) ?: [
                'total' => 0,
                'customers' => 0,
                'technicians' => 0,
                'admins' => 0
            ];
        }

        public function findAllForAdmin(array $filters = []): array
        {
            $sql = "SELECT id, name, email, phone, role
                    FROM users
                    WHERE 1=1";

            $params = [];

            if (!empty($filters['search'])) {
                $sql .= " AND (name LIKE :search OR email LIKE :search)";
                $params[':search'] = '%' . $filters['search'] . '%';
            }

            if (!empty($filters['role'])) {
                $sql .= " AND role = :role";
                $params[':role'] = $filters['role'];
            }

            $sql .= " ORDER BY name ASC";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);

            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        }

        public function updateRole(int $id, string $role): bool
        {
            $stmt = $this->pdo->prepare(
                "UPDATE users SET role = :role WHERE id = :id"
            );

            return $stmt->execute([
                ':role' => $role,
                ':id' => $id
            ]);
        }

        public function findTechnicians(): array
        {
            $sql = "SELECT id, name
                    FROM users
                    WHERE role = 'technician'
                    ORDER BY name ASC";

            $stmt = $this->pdo->query($sql);

            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        }
    }