<?php

    require_once __DIR__ . '/../app/controllers/AuthController.php';
    require_once __DIR__ . '/../app/controllers/TicketController.php';
    require_once __DIR__ . '/../app/controllers/ProfileController.php';

    $authController = new AuthController($pdo);
    $ticketController = new TicketController($pdo);
    $profileController = new ProfileController($pdo);

    $page = $_GET['page'] ?? 'login';

    switch ($page) {
        case 'home':
            if (!isset($_SESSION['user_id'])) {
                header('Location: ?page=login');
                exit;
            }
            
            require __DIR__ . '/../app/views/home.php';
            break;

        case 'profile':
            if (!isset($_SESSION['user_id'])) {
                header('Location: ?page=login');
                exit;
            }
            $profileController->showProfile();
            break;

        case 'profile_update':
            if (!isset($_SESSION['user_id'])) {
                header('Location: ?page=login');
                exit;
            }

            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                header('Location: ?page=profile');
                exit;
            }

            $profileController->updateProfile();
            break;

        case 'login':
            $authController->showLogin();
            break;

        case 'register':
            $authController->showRegister();
            break;

        case 'register_submit':
            $authController->register();
            break;

        case 'login_submit':
            $authController->login();
            break;

        case 'logout':
            $authController->logout();
            break;

        case 'create_ticket':
            $ticketController->showCreate();
            break;

        case 'create_ticket_submit':
            $ticketController->store();
            break;

        case 'track_tickets':
            if (!isset($_SESSION['user_id'])) {
                header('Location: ?page=login');
                exit;
            }

            $ticketController->showTrack();
            break;

        case 'technician_tickets':
            $authController->requireRole(['technician', 'admin']);
            $ticketController->showTechnicianDashboard();
            break;

        case 'technician_ticket_show':
            $authController->requireRole(['technician','admin']);
            $ticketController->showTechnicianTicket();
            break;

        case 'technician_ticket_status_update':
            $authController->requireRole(['technician','admin']);
            $ticketController->updateTechnicianTicketStatus();
            break;

        case 'technician_ticket_reply_store':
            $authController->requireRole(['technician', 'admin']);
            $ticketController->storeTechnicianReply();
            break;

        case 'customer_ticket_reply_store':
            if (!isset($_SESSION['user_id'])) {
                header('Location: ?page=login');
                exit;
            }

            $ticketController->storeCustomerReply();
            break;

        default:
            echo "Página não encontrada.";
    }