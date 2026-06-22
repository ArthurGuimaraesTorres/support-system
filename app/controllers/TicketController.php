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

            $allowedCategories = ['Bug', 'Hardware', 'Rede', 'Outros'];
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

            header('Location: ?page=track_tickets');
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
            $this->requireAuthentication();

            $ticket = null;
            $error = null;
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

            require __DIR__ . '/../views/tickets/track.php';
        }
    }