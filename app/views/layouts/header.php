<?php

    $currentPage = $_GET['page'] ?? 'home';
    $currentAssignment = $_GET['assignment'] ?? '';

    $roleLabels = [
        "customer"=> "Usuário",
        "technician"=> "Técnico",
        "admin"=> "Administrador",
    ];

    $categoryLabels = [
        'bug' => 'Bug',
        'hardware'=> 'Hardware',
        'network' => 'Rede',
        'other' => 'Outros',
    ];

    $priorityLabels = [
        'low' => 'Baixa',
        'medium' => 'Média',
        'high' => 'Alta',
    ];

    $statusLabels = [
        'open' => 'Aberto',
        'in_progress' => 'Em andamento',
        'resolved' => 'Resolvido',
        'closed' => 'Fechado',
    ];

    $priorityClasses = [
        'low' => 'bg-success',
        'medium' => 'bg-warning text-dark',
        'high' => 'bg-danger',
    ];

    $statusClasses = [
        'open' => 'bg-primary',
        'in_progress' => 'bg-warning text-dark',
        'resolved' => 'bg-success',
        'closed' => 'bg-secondary', 
    ];

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GTs. | Support</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/style.css">
</head>

<body class="d-flex flex-column min-vh-100">
    <?php if (empty($hideNavbar)): ?>
    <nav class="navbar navbar-expand-lg navbar-light navbar-color">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="?page=home">GTs. | Support</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav gap-2">
                    <?php if ($_SESSION['role'] === 'customer'): ?>
                    <li class="nav-item">
                        <a class="btn <?= $currentPage === 'create_ticket' ? 'btn-light text-primary' : 'btn-outline-light' ?> fw-semibold"
                            href="?page=create_ticket">
                            <i class="bi bi-plus-circle me-2"></i>
                            Criar Chamado
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="btn <?= $currentPage === 'track_tickets' ? 'btn-light text-primary' : 'btn-outline-light' ?> fw-semibold"
                            href="?page=track_tickets">
                            <i class="bi bi-search me-2"></i>
                            Acompanhar Chamados
                        </a>
                    </li>
                    <?php endif; ?>

                    <?php if (($_SESSION['role'] ?? '') === 'technician'): ?>
                    <li class="nav-item">
                        <a class="btn <?= ($currentPage === 'technician_tickets' && $currentAssignment === 'unassigned') ? 'btn-light text-primary' : 'btn-outline-light' ?> fw-semibold"
                            href="?page=technician_tickets&assignment=unassigned">
                            <i class="bi bi-tools me-2"></i>
                            Atender Chamados
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="btn <?= ($currentPage === 'technician_tickets' && $currentAssignment === 'mine') ? 'btn-light text-primary' : 'btn-outline-light' ?> fw-semibold"
                            href="?page=technician_tickets&assignment=mine">
                            <i class="bi bi-chat-square-dots-fill me-2"></i>
                            Meus Atendimentos
                        </a>
                    </li>
                    <?php endif; ?>

                    <?php if (($_SESSION['role'] ?? '') === 'admin'): ?>
                    <li class="nav-item">
                        <a class="btn <?= $currentPage === 'admin_dashboard' ? 'btn-light text-primary' : 'btn-outline-light' ?> fw-semibold"
                            href="?page=admin_dashboard">
                            <i class="bi bi-speedometer2 me-2"></i>
                            Painel Administrativo
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="btn <?= $currentPage === 'admin_users' ? 'btn-light text-primary' : 'btn-outline-light' ?> fw-semibold"
                            href="?page=admin_users">
                            <i class="bi bi-people-fill me-2"></i>
                            Gerenciar Usuários
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>

                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <button class="btn nav-link dropdown-toggle" type="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="bi bi-person-circle fs-4"></i>
                        </button>

                        <ul class="dropdown-menu dropdown-menu-end user-dropdown-menu">
                            <li>
                                <div class="dropdown-item-text user-dropdown-header">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-person-circle fs-3 text-primary"></i>

                                        <div>
                                            <strong class="d-block user-dropdown-name"><?= htmlspecialchars($_SESSION["name"] ?? '', ENT_QUOTES, 'UTF-8'); ?></strong>
                                            <small class="d-block text-muted">
                                                <?= htmlspecialchars($roleLabels[$_SESSION['role']] ?? 'Sem perfil', ENT_QUOTES, 'UTF-8'); ?>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <li>
                                <a class="dropdown-item" href="?page=profile">
                                    <i class="bi bi-person me-2"></i>
                                    Perfil
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="?page=logout">
                                    <i class="bi bi-box-arrow-right me-2"></i>
                                    Sair
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <?php endif; ?>
