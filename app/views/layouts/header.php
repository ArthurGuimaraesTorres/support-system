<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GTs. | Support</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <?php if (empty($hideNavbar)): ?>
        <nav class="navbar navbar-expand-lg navbar-light navbar-color">
            <div class="container">
                <a class="navbar-brand" href="?page=home">GTs. | Support</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav gap-2">
                        <li class="nav-item">
                            <a class="btn btn-light text-primary fw-semibold" href="?page=create_ticket"><i class="bi bi-plus-circle me-2"></i>Criar Chamado</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-light text-primary fw-semibold" href="?page=track_tickets"><i class="bi bi-search me-2"></i>Acompanhar meu Chamado</a>
                        </li>
                        <?php if (($_SESSION['role'] ?? '') === 'technician'): ?>
                            <li class="nav-item">
                                <a class="btn btn-light text-primary fw-semibold" href="?page=technician_tickets">
                                    <i class="bi bi-tools me-2"></i>Atender Chamados
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>

                    <ul class="navbar-nav ms-auto">
                        <div class="nav-item d-flex align-items-center me-3">
                            <div class="nav-link">
                                <?= htmlspecialchars($_SESSION["name"] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                            </div>
                        </div>
                        <li class="nav-item dropdown">
                            <button
                                class="btn nav-link dropdown-toggle"
                                type="button"
                                data-bs-toggle="dropdown"
                                aria-expanded="false"
                            >
                                <i class="bi bi-person-circle fs-4"></i>
                            </button>

                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="?page=profile"><i class="bi bi-person me-2"></i>Perfil</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="?page=logout"><i class="bi bi-box-arrow-right me-2"></i>Sair</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    <?php endif; ?>