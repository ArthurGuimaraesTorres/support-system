<?php

    require __DIR__ . '/../app/controllers/AuthController.php';
    require __DIR__ . '/../app/controllers/TicketController.php';

    $authController = new AuthController($pdo);
    $ticketController = new TicketController($pdo);

    $page = $_GET['page'] ?? 'login';

    switch ($page) {
        case 'home':
            if (!isset($_SESSION['user_id'])) {
                header('Location: ?page=login');
                exit;
            }
            
            require __DIR__ . '/../app/views/home.php';
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
        default:
            echo "Página não encontrada.";
    }