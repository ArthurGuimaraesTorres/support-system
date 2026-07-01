<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Ticket.php';

class AdminController
{
    private User $userModel;
    private Ticket $ticketModel;

    public function __construct(PDO $pdo)
    {
        $this->userModel = new User($pdo);
        $this->ticketModel = new Ticket($pdo);
    }

    public function dashboard(): void
    {
        $userStats = $this->userModel->getAdminStats();
        $ticketStats = $this->ticketModel->getAdminStats();
        $recentTickets = $this->ticketModel->findRecentForAdmin(5);

        require __DIR__ . '/../views/admin/dashboard.php';
    }

    public function users(): void
    {
        $filters = [
            'search' => trim($_GET['search'] ?? ''),
            'role' => $_GET['role'] ?? '',
        ];

        $users = $this->userModel->findAllForAdmin($filters);

        require __DIR__ . '/../views/admin/users.php';
    }

    public function updateUserRole(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?page=admin_users');
            exit;
        }

        $userId = $_POST['user_id'] ?? '';
        $role = $_POST['role'] ?? '';

        $allowedRoles = ['customes', 'technician', 'admin'];

        if (
            filter_has_var($userId, FILTER_VALIDATE_INT) === false ||
            (int) $userId <= 0 ||
            !in_array($role, $allowedRoles, true)
        ) {
            header('Location: ?page=admin_users');
            exit;
        }

        $this->userModel->updateRole((int) $userId, $role);

        header('Location: ?page=admin_users');
        exit;
    }
}