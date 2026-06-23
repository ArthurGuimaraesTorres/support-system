<?php

    class Ticket
    {
        public function __construct(private PDO $pdo)
        {
        }

        public function create(
            int $userId,
            string $subject,
            string $category,
            string $priority,
            string $description
        ): int {
            $sql = "INSERT INTO tickets (user_id, subject, category, priority, description) VALUES (:user_id, :subject, :category, :priority, :description)";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':user_id' => $userId,
                ':subject' => $subject,
                ':category' => $category,
                ':priority' => $priority,
                ':description' => $description
            ]);
            return $this->pdo->lastInsertId();
        }

        public function findByIdAndUser(int $ticketId, int $userId): ?array
        {
            $sql = "SELECT * FROM tickets WHERE id = :id AND user_id = :user_id";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':id' => $ticketId,
                ':user_id' => $userId
            ]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        }

        public function findRecentByUser(int $userId, int $limit = 5): array
        {
            $limit = max(1, min($limit,20));

            $sql = "SELECT id, subject, category, priority, status 
                    FROM tickets 
                    WHERE user_id = :user_id 
                    ORDER BY created_at DESC 
                    LIMIT $limit";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':user_id' => $userId]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        }

        public function findAllForTechnician(): array
        {
            $sql = "SELECT tickets.*, users.name AS user_name
                    FROM tickets
                    INNER JOIN users ON users.id = tickets.users_id
                    ORDER BY tickets.created_at DESC";

            $stmt = $this->pdo->query($sql);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }