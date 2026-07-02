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

    public function tickets(): void
    {
        $filters = [
            'status' => $_GET['status'] ?? '',
            'priority' => $_GET['priority'] ?? '',
            'category' => $_GET['category'] ?? '',
            'assignment' => $_GET['assignment'] ?? '',
            'search' => trim($_GET['search'] ?? ''),
        ];

        $tickets = $this->ticketModel->findAllForAdmin($filters);
        $ticketStats = $this->ticketModel->getAdminStats();

        require __DIR__ . '/../views/admin/tickets.php';
    }

    public function showTicket(): void
    {
        $ticketId = $_GET['ticket_id'] ?? '';
        $ticket = null;
        $replies = [];
        $error = null;

        if (filter_var($ticketId, FILTER_VALIDATE_INT) === false || (int) $ticketId <= 0) {
            $error = 'Informe um ID de chamado válido.';
        } else {
            $ticket = $this->ticketModel->findByIdForAdmin((int) $ticketId);

            if (!$ticket) {
                $error = 'Chamado não encontrado.';
            } else {
                $replies = $this->ticketModel->findRepliesByTicket((int) $ticketId);
            }
        }

        $technicians = $this->userModel->findTechnicians();

        require __DIR__ . '/../views/admin/ticket_show.php';
    }

    public function updateTicketStatus(): void
        {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                header('Location: ?page=admin_tickets');
                exit;
            }

            $ticketId = $_POST['ticket_id'] ??'';
            $status = $_POST['status'] ??'';

            $allowedStatuses = ['open', 'in_progress', 'resolved', 'closed'];

            if (
                filter_var($ticketId, FILTER_VALIDATE_INT) === false || 
                (int) $ticketId <= 0 || 
                !in_array($status, $allowedStatuses, true)
            ) {
                    header('Location: ?page=admin_tickets');
                    exit;
            }

            $this->ticketModel->updateStatusForTechnician((int) $ticketId, $status);

            header('Location: ?page=admin_ticket_show&ticket_id=' . (int) $ticketId);
            exit;
        }

    public function updateTicketAssignment(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?page=admin_tickets');
            exit;
        }

        $ticketId = $_POST['ticket_id'] ?? '';
        $assignedTo = $_POST['assigned_to'] ?? '';

        if (
            filter_var($ticketId, FILTER_VALIDATE_INT) === false || 
            (int) $ticketId <= 0 || 
            ($assignedTo !== '' && filter_var($assignedTo, FILTER_VALIDATE_INT) === false)
        ) {
            header('Location: ?page=admin_tickets');
            exit;
        }

        $this->ticketModel->updateAssignmentForAdmin((int) $ticketId, $assignedTo !== '' ? (int) $assignedTo : null);

        header('Location: ?page=admin_ticket_show&ticket_id=' . (int) $ticketId);
        exit;
    }
}