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

        public function findAllForTechnician(array $filters = []): array
        {
            $sql = "SELECT tickets.*, 
                           users.name AS user_name,
                           assigned_users.name AS assigned_name
                    FROM tickets
                    INNER JOIN users ON users.id = tickets.user_id
                    LEFT JOIN users AS assigned_users ON assigned_users.id = tickets.assigned_to
                    WHERE 1=1";

            $sql .= " AND (tickets.assigned_to IS NULL OR tickets.assigned_to = :current_user_id)";
            $params = [':current_user_id' => $filters['current_user_id']];

            if (!empty($filters['status'])) {
                $sql .= " AND tickets.status = :status";
                $params[':status'] = $filters['status'];
            }

            if (!empty($filters['priority'])) {
                $sql .= " AND tickets.priority = :priority";
                $params[':priority'] = $filters['priority'];
            }

            if (!empty($filters['category'])) {
                $sql .= " AND tickets.category = :category";
                $params[':category'] = $filters['category'];
            }

            if (!empty($filters['search'])) {
                $sql .= " AND (tickets.subject LIKE :search OR users.name LIKE :search)";
                $params[':search'] = '%' . $filters['search'] .'%';
            }

            if (($filters['assignment'] ?? '') === 'mine') {
                $sql .= " AND tickets.assigned_to = :current_user_id";
                $params[':current_user_id'] = $filters['current_user_id'];
            }

            if (($filters['assignment'] ?? '') === 'unassigned') {
                $sql .= " AND tickets.assigned_to IS NULL";
            }

            if (($filters['assignment'] ?? '') === 'assigned') {
                $sql .= " AND tickets.assigned_to IS NOT NULL";
            }

            $sql .= " ORDER BY tickets.created_at DESC";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);

            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        }

        public function findByIdForTechnician(int $ticketId, int $technicianId): ?array
        {
            $sql = "SELECT tickets.*, users.name AS user_name, users.email AS user_email
                    FROM tickets
                    INNER JOIN users ON users.id = tickets.user_id
                    WHERE tickets.id = :id
                    AND (tickets.assigned_to IS NULL OR tickets.assigned_to = :technician_id)
                    LIMIT 1";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':id' => $ticketId,
                ':technician_id' => $technicianId,    
            ]);

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

        public function addReply(int $ticketId, int $userId, string $message): bool
        {
            $sql = "INSERT INTO ticket_replies (ticket_id, user_id, message)
                    VALUES (:ticket_id, :user_id, :message)";

            $stmt = $this->pdo->prepare($sql);

            return $stmt->execute([
                ':ticket_id' => $ticketId,
                ':user_id'=> $userId,
                ':message'=> $message
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

        public function getTechnicianStats(): array
        {
            $sql = "SELECT
                        SUM(status = 'open') AS open,
                        SUM(status = 'in_progress') AS in_progress,
                        SUM(status = 'resolved') AS resolved,
                        SUM(priority = 'high') AS high
                    FROM tickets";  

            $stmt = $this->pdo->query($sql);

            return $stmt->fetch(PDO::FETCH_ASSOC) ?: [
                'total'=> 0,
                'open'=> 0,
                'in_progress'=> 0,
                'resolved'=> 0,
                'high_priority'=> 0,
            ];
        }

        public function assignToTechnician(int $ticketId, int $technicianId): bool
        {
            $sql = 'UPDATE tickets
                    SET assigned_to = :technician_id
                    WHERE id = :ticket_id
                    AND assigned_to IS NULL';

            $stmt = $this->pdo->prepare($sql);

            return $stmt->execute([
                ':technician_id' => $technicianId,
                'ticket_id'=> $ticketId
            ]);
        }

        public function findAssignedToTechnician(int $ticketId, int $technicianId): ?array
        {
            $sql = "SELECT *
                    FROM tickets
                    WHERE id = :id
                    AND assigned_to = :technician_id
                    LIMIT 1";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':id' => $ticketId,
                ':technician_id' => $technicianId,
            ]);

            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        }

        public function getAdminStats(): array
        {
            $sql = "SELECT
                        COUNT(*) AS total,
                        SUM(status = 'open') AS open,
                        SUM(status = 'in_progress') AS in_progress,
                        SUM(status = 'resolved') AS resolved,
                        SUM(status = 'closed') AS closed,
                        SUM(priority = 'high') AS high
                    FROM tickets";

            $stmt = $this->pdo->query($sql);

            return $stmt->fetch(PDO::FETCH_ASSOC) ?: [
                'total' => 0,
                'open' => 0,
                'in_progress' => 0,
                'resolved' => 0,
                'closed' => 0,
                'high' => 0
            ];
        }

        public function findRecentForAdmin(int $limit = 5): array
        {
            $limit = max(1, min($limit, 20));

            $sql = "SELECT tickets.*,
                           users.name AS user_name,
                           assigned_users.name AS assigned_name
                    FROM tickets
                    INNER JOIN users ON users.id = tickets.user_id
                    LEFT JOIN users AS assigned_users ON assigned_users.id = tickets.assigned_to
                    ORDER BY tickets.created_at DESC
                    LIMIT $limit";

            $stmt = $this->pdo->query($sql);

            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        }

        public function findAllForAdmin(array $filters = []): array
        {
            $sql = "SELECT tickets.*, 
                           users.name AS user_name,
                           assigned_users.name AS assigned_name
                    FROM tickets
                    INNER JOIN users ON users.id = tickets.user_id
                    LEFT JOIN users AS assigned_users ON assigned_users.id = tickets.assigned_to
                    WHERE 1=1";

            $params = [];

            if (!empty($filters['status'])) {
                $sql .= " AND tickets.status = :status";
                $params[':status'] = $filters['status'];
            }

            if (!empty($filters['priority'])) {
                $sql .= " AND tickets.priority = :priority";
                $params[':priority'] = $filters['priority'];
            }

            if (!empty($filters['category'])) {
                $sql .= " AND tickets.category = :category";
                $params[':category'] = $filters['category'];
            }

            if (!empty($filters['search'])) {
                $sql .= " AND (tickets.subject LIKE :search OR users.name LIKE :search)";
                $params[':search'] = '%' . $filters['search'] . '%';
            }

            if (($filters['assignment'] ?? '') === 'assigned') {
                $sql .= " AND tickets.assigned_to IS NOT NULL";
            }

            if (($filters['assignment'] ?? '') === 'unassigned') {
                $sql .= " AND tickets.assigned_to IS NULL";
            }

            $sql .= " ORDER BY tickets.created_at DESC";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);

            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        }
        
        public function findByIdForAdmin(int $ticketId): ?array
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

        public function updateAssignmentForAdmin(int $ticketId, ?int $technicianId): bool
        {
            $sql = "UPDATE tickets
                    SET assigned_to = :technician_id
                    WHERE id = :ticket_id";

            $stmt = $this->pdo->prepare($sql);

            return $stmt->execute([
                ':technician_id' => $technicianId,
                ':ticket_id' => $ticketId
            ]);
        }
    }