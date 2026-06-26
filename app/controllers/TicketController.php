<?php

    require_once __DIR__ . '/../models/Ticket.php';

    class TicketController
    {
        private Ticket $ticketModel;

        public function __construct(PDO $pdo)
        {
            $this->ticketModel = new Ticket($pdo);
        }

        public function showCreate(): void
        {
            require __DIR__ . '/../views/tickets/create.php';
        }

        public function store(): void
        {
            $this->requireAuthentication();

            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                header('Location: ?page=create_ticket');
                exit;
            }

            $subject = $_POST['subject'] ?? '';
            $category = $_POST['category'] ?? '';
            $priority = $_POST['priority'] ?? '';
            $description = $_POST['description'] ?? '';

            $allowedCategories = ['bug', 'hardware', 'network', 'other'];
            $allowedPriorities = ['low', 'medium', 'high'];

            $errors = [];

            if ($subject === '' || mb_strlen($subject) > 150) {
                $errors[] = "O assunto é obrigatório e deve ter no máximo 150 caracteres.";
            }

            if (!in_array($category, $allowedCategories, true)) {
                $errors[] = "Categoria inválida.";
            }

            if (!in_array($priority, $allowedPriorities, true)) {
                $errors[] = "Prioridade inválida.";
            }

            if (mb_strlen($description) < 10) {
                $errors[] = "A descrição deve ter no mínimo 10 caracteres.";
            }

            if ($errors) {
                require __DIR__ . '/../views/tickets/create.php';
                return;
            }

            $ticketId = $this->ticketModel->create(
                (int )$_SESSION['user_id'], 
                $subject, 
                $category, 
                $priority, 
                $description
                );

            $_SESSION['success_message'] = "Ticket criado com sucesso! ID: $ticketId";

            header("Location: ?page=track_tickets&ticket_id=$ticketId");
            exit;
        }

        public function requireAuthentication(): void
        {
            if (!isset($_SESSION['user_id'])) {
                header('Location: ?page=login');
                exit;
            }
        }

        public function showTrack(): void
        {
            $sucessMessage = $_SESSION['success_message'] ?? null;
            unset($_SESSION['success_message']);

            $this->requireAuthentication();

            $ticket = null;
            $error = null;
            $replies = [];
            $ticketId = $_GET['ticket_id'] ?? '';

            if ($ticketId !== '') {
                if (
                    filter_var($ticketId, FILTER_VALIDATE_INT) === false || (int)$ticketId <= 0
                ) {
                    $error = 'Informe um ID de ticket válido.';
                } else {
                    $ticket = $this->ticketModel->findByIdAndUser((int)$ticketId, (int)$_SESSION['user_id']);

                    if (!$ticket) {
                        $error = "Ticket não encontrado ou você não tem permissão para visualizá-lo.";
                    }
                }
            }

            $recentTickets = $this->ticketModel->findRecentByUser((int)$_SESSION['user_id'], 5);

            require __DIR__ . '/../views/tickets/track.php';
        }

        public function showTechnicianDashboard(): void
        {
            $tickets = $this->ticketModel->findAllForTechnician();

            require __DIR__ .'/../views/tickets/technician_dashboard.php';
        }

        public function showTechnicianTicket(): void
        {
            $ticketId = $_GET['ticket_id'] ??'';
            $ticket = null;
            $replies = [];
            $error = null;

            if (filter_var($ticketId, FILTER_VALIDATE_INT) === false || (int)$ticketId <= 0) {
                $error = 'Informe um ID de chamado válido.';
            } else {
                $ticket = $this->ticketModel->findByIdForTechnician((int)$ticketId);

                if (!$ticket) {
                    $error = 'Chamado não encontrado.';
                } else {
                    $replies = $this->ticketModel->findRepliesByTicket((int) $ticketId);
                }
            }

            require __DIR__ .'/../views/tickets/technician_show.php';
        }

        public function updateTechnicianTicketStatus(): void
        {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                header('Location: ?page=technician_tickets');
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
                    header('Location: ?page=technician_tickets');
                    exit;
            }

            $this->ticketModel->updateStatusForTechnician((int) $ticketId, $status);

            header('Location: ?page=technician_ticket_show&ticket_id=' . (int) $ticketId);
            exit;
        }

        public function storeTechnicianReply(): void
        {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                header('Location: ?page=technician_tickets');
                exit;
            }

            $ticketId = $_POST['ticket_id'] ?? '';
            $message = trim($_POST['message'] ?? '');

            if (
                filter_var($ticketId, FILTER_VALIDATE_INT) === false ||
                (int) $ticketId <= 0 ||
                mb_strlen($message) < 2
            ) {
                header('Location: ?page=technician_ticket_show&ticket_id=' . (int) $ticketId);
                exit;
            }

            $ticket = $this->ticketModel->findByIdForTechnician((int) $ticketId);

            if (!$ticket) {
                header('Location: ?page=technician_tickets');
                exit;
            }

            $this->ticketModel->addReply(
                (int) $ticketId,
                (int) $_SESSION['user_id'],
                $message
            );

            header('Location: ?page=technician_ticket_show&ticket_id='. (int) $ticketId);
            exit;
        }

        public function storeCustomerReply(): void
        {
            $this->requireAuthentication();

            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                header('Location: ?page=track_tickets');
                exit;
            }

            $ticketId = $_POST['ticket_id'] ?? '';
            $message = trim($_POST['message'] ?? '');
            
            if (
                filter_var($ticketId, FILTER_VALIDATE_INT) === false ||
                (int) $ticketId <= 0||
                mb_strlen($message) < 2
            ) {
                header('Location: ?page=track_tickets&ticket_id=' . (int) $ticketId);
                exit;
            }

            $ticket = $this->ticketModel->findByIdAndUser(
                (int) $ticketId,
                (int) $_SESSION['user_id']
            );

            if (!$ticket) {
                header('Location: ?page=track_tickets');
                exit;
        }

        $this->ticketModel->addReply(
            (int) $ticketId,
            (int) $_SESSION['user_id'],
            $message
        );

        header('Location: ?page=track_tickets&ticket_id='. (int) $ticketId);
        exit;
        }
    }