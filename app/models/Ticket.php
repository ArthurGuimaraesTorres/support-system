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

            $sql = "SELECT id, subject, category, priority, status, created_at 
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
                    INNER JOIN users ON users.id = tickets.user_id
                    ORDER BY tickets.created_at DESC";

            $stmt = $this->pdo->query($sql);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function findByIdForTechnician(int $ticketId): ?array
        {
            $sql = "SELECT tickets.*, users.name AS user_name, users.email AS user_email
                    FROM tickets
                    INNER JOIN users ON users.id = tickets.user_id
                    WHERE tickets.id = :id
                    LIMIT 1";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $ticketId]);

            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        }

        public function updateStatusForTechnician(int $ticketId, string $status): bool
        {
            $sql = "UPDATE tickets
                    SET status = :status
                    WHERE id = :id";
            
            $stmt = $this->pdo->prepare($sql);

            return $stmt->execute([':status' => $status,':id'=> $ticketId]);        
        }

        public function addReply(int $ticketId, int $userId, string $messsage): bool
        {
            $sql = "INSERT INTO ticket_replies (ticket_id, user_id, message)
                    VALUES (:ticket_id, :user_id, :message)";

            $stmt = $this->pdo->prepare($sql);

            return $stmt->execute([
                ':ticket_id' => $ticketId,
                'user_id'=> $userId,
                ':message'=> $messsage
                ]);
        }

        public function findRepliesByTicket(int $ticketId): array
        {
            $sql = "SELECT ticket_replies.*, users.name AS user_name, users.role AS user_role
                    FROM ticket_replies
                    INNER JOIN users ON users.id = ticket_replies.user_id
                    WHERE ticket_replies.ticket_id = :ticket_id
                    ORDER BY ticket_replies.created_at ASC";
                    
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':ticket_id' => $ticketId
            ]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        }
    }