<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-4">
    <h1 class="h3 mb-4">Painel Administrativo</h1>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="text-muted">Total de Usuários</div>
                    <div class="h4"><?= (int) $userStats['total'] ?></div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="text-muted">Clientes</div>
                    <div class="h4"><?= (int) $userStats['customers'] ?></div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="text-muted">Técnicos</div>
                    <div class="h4"><?= (int) $userStats['technicians'] ?></div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="text-muted">Chamados em Aberto</div>
                    <div class="h4"><?= (int) $ticketStats['open'] ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2 mb-4">
        <a href="?page=admin_users" class="btn btn-primary">
            <i class="bi bi-people me-2"></i>
            Gerenciar Usuários
        </a>

        <a href="?page=admin_tickets" class="btn btn-primary">
            <i class="bi bi-ticket-perforated me-2"></i>
            Monitorar Chamados
        </a>
    </div>

    <hr>

    <h2 class="h5 mb-3">Últimos Chamados</h2>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Assunto</th>
                    <th>Cliente</th>
                    <th>Status</th>
                    <th>Prioridade</th>
                    <th>Criado em</th>
                    <th>Técnico</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recentTickets as $ticket): ?>
                <tr>
                    <td><?= htmlspecialchars($ticket['id'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($ticket['subject'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($ticket['user_name'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td>
                        <span class="badge <?= $statusClasses[$ticket['status']] ?? 'bg-secondary' ?>">
                            <?= htmlspecialchars(ucfirst($statusLabels[$ticket['status']] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                        </span>
                    </td>
                    <td>
                        <span class="badge <?= $priorityClasses[$ticket['priority']] ?? 'bg-secondary' ?>">
                            <?= htmlspecialchars(ucfirst($priorityLabels[$ticket['priority']]), ENT_QUOTES, 'UTF-8') ?>
                        </span>
                    </td>
                    <td><?= htmlspecialchars(date('d/m/Y H:i', strtotime($ticket['created_at'])), ENT_QUOTES, 'UTF-8') ?>
                    </td>
                    <td><?= htmlspecialchars($ticket['assigned_name'], ENT_QUOTES, 'UTF-8') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>