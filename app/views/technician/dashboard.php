<?php require __DIR__ ."/../layouts/header.php"; ?>

<div class="container mt-4">
    <h1 class="h3 mb-4">Chamados para atendimento</h1>

    <div class="row g-3 mb-4">

        <div class="col-md">
            <div class="card dashboard-stat-card stat-open">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div class="text-muted">Abertos</div>
                    <div class="h4 mb-0"><?= (int) $ticketStats['open'] ?></div>
                </div>
            </div>
        </div>

        <div class="col-md">
            <div class="card dashboard-stat-card stat-progress">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div class="text-muted">Em Progresso</div>
                    <div class="h4 mb-0"><?= (int) $ticketStats['in_progress'] ?></div>
                </div>
            </div>
        </div>

        <div class="col-md">
            <div class="card dashboard-stat-card stat-resolved">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div class="text-muted">Resolvidos</div>
                    <div class="h4 mb-0"><?= (int) $ticketStats['resolved'] ?></div>
                </div>
            </div>
        </div>

        <div class="col-md">
            <div class="card dashboard-stat-card stat-high">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div class="text-muted">Alta Prioridade</div>
                    <div class="h4 mb-0"><?= (int) $ticketStats['high'] ?></div>
                </div>
            </div>
        </div>
    </div>

    <form method="GET" action="index.php" class="row g-3 mb-4">
        <input type="hidden" name="page" value="technician_tickets">

        <div class="col-md-2">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-select">
                <option value="">Todos</option>
                <?php foreach ($statusLabels as $value => $label): ?>
                    <option value="<?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?>"
                        <?= ($filters['status'] ?? '') === $value ? 'selected' : '' ?>>
                        <?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-2">
            <label for="priority" class="form-label">Prioridade</label>
            <select name="priority" id="priority" class="form-select">
                <option value="">Todos</option>
                <?php foreach ($priorityLabels as $value => $label): ?>
                    <option value="<?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?>"
                        <?= ($filters['priority'] ?? '') === $value ? 'selected' : '' ?>>
                        <?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-2">
            <label for="category" class="form-label">Categoria</label>
            <select name="category" id="status" class="form-select">
                <option value="">Todos</option>
                <?php foreach ($categoryLabels as $value => $label): ?>
                    <option value="<?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?>"
                        <?= ($filters['category'] ?? '') === $value ? 'selected' : '' ?>>
                        <?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-2">
            <label for="assignment" class="form-label">Atribuição</label>
            <select name="assignment" id="assignment" class="form-select">
                <option value="">Todos</option>
                <option value="mine" <?= ($filters['assignment'] ?? '') === 'mine' ? 'selected' : '' ?>>
                    Meus chamados
                </option>
                <option value="unassigned" <?= ($filters['assignment'] ?? '') === 'unassigned' ? 'selected' : '' ?>>
                    Não atribuídos
                </option>
            </select>
        </div>

        <div class="col-md-2">
            <label for="search" class="form-label">Buscar</label>
            <input
                type="text"
                name="search"
                id="search"
                class="form-control"
                value="<?= htmlspecialchars($filters['search'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                placeholder="Assunto ou usuário"
            >
        </div>

        <div class="col-12 d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="bi bi-filter-circle-fill"></i>
                Filtrar
            </button>

            <a href="?page=technician_tickets" class="btn btn-secondary"><i class="bi bi-eraser-fill me-2"></i>
                Limpar
            </a>
        </div>
    </form>

    <hr>

    <?php if (empty($tickets)): ?>
        <div class="alert alert-info">
            Não há nenhum chamado disponível.
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Usuário</th>
                        <th>Assunto</th>
                        <th>Categoria</th>
                        <th>Prioridade</th>
                        <th>Status</th>
                        <th>Criado em</th>
                        <th>Atribuído a</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tickets as $ticket): ?>
                        <tr>
                            <td><?= htmlspecialchars($ticket['id']) ?></td>
                            <td><?= htmlspecialchars($ticket['user_name']) ?></td>
                            <td><?= htmlspecialchars($ticket['subject']) ?></td>
                            <td><?= htmlspecialchars($categoryLabels[$ticket['category']]) ?></td>
                            <td>
                                <span class="badge <?= $priorityClasses[$ticket['priority']] ?? 'bg-secondary' ?>">
                                    <?= htmlspecialchars($priorityLabels[$ticket['priority']], ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge <?= $statusClasses[$ticket['status']] ?? 'bg-secondary' ?>">
                                    <?= htmlspecialchars($statusLabels[$ticket['status']], ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                            </td>
                            <td><?= (new DateTime ($ticket['created_at']))->format('d/m/Y H:i'); ?></td>
                            <td>
                                <?php if (!empty($ticket['assigned_name'])): ?>
                                    <span class="badge bg-success">
                                        <?= htmlspecialchars($ticket['assigned_name'], ENT_QUOTES, 'UTF-8') ?>
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">
                                        Não atribuído
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <?php if (empty($ticket['assigned_to'])): ?>
                                    <a
                                        class="btn btn-sm btn-outline-secondary"
                                        href="?page=technician_ticket_show&ticket_id=<?= (int) $ticket['id'] ?>"
                                    >
                                        Visualizar
                                    </a>
                                <?php elseif ((int) $ticket['assigned_to'] === (int) $_SESSION['user_id']): ?>
                                    <a
                                        class="btn btn-sm btn-outline-primary"
                                        href="?page=technician_ticket_show&ticket_id=<?= (int) $ticket['id'] ?>"
                                    >
                                        Atender
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>


<?php require __DIR__ ."/../layouts/footer.php"; ?>