<?php

    require __DIR__ ."/../layouts/header.php"; 

    $canHandleTicket = (int) ($ticket['assigned_to'] ?? 0) === (int) $_SESSION['user_id'];
    $isUnassigned = empty($ticket['assigned_to']);
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Monitoramento do chamado</h1>

        <div class="d-flex gap-2">
            <a href="?page=admin_tickets" class="btn btn-outline-secondary btn-sm">
                Voltar
            </a>
        </div>
    </div>

    <?php if (!empty($error)): ?>
    <div class="alert alert-danger">
        <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
    </div>
    <?php elseif (!empty($ticket)): ?>
    <div class="card">
        <div class="card-header d-flex flex-lg-row justify-content-between align-items-lg-center gap-3">
            <strong>
                Chamado #<?= (int) $ticket['id'] ?>
            </strong>

            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-lg-end gap-5">
                <form action="index.php?page=admin_ticket_assign_update" method="POST"
                    class="d-flex align-items-center gap-2 mb-0">
                    <input type="hidden" name="ticket_id" value="<?= (int) $ticket['id']; ?>">

                    <label for="assigned_to" class="form-label mb-0 small text-muted text-nowrap">Atribuído a:</label>

                    <select name="assigned_to" id="assigned_to" class="form-select form-select-sm w-auto"
                        data-current="<?= (int) $ticket['assigned_to']; ?>"
                        onchange="if (confirm('Tem certeza que deseja atribuir este chamado?')) { this.form.submit() } else { this.value = this.dataset.current; }">
                        <option value="">Não atribuído</option>
                        <?php foreach ($technicians as $technician): ?>
                        <option value="<?= (int) $technician['id']; ?>"
                            <?= (int) $ticket['assigned_to'] === (int) $technician['id'] ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($technician['name'], ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </form>

                <form action="index.php?page=admin_ticket_status_update" method="POST"
                    class="d-flex align-items-center gap-2 mb-0">
                    <input type="hidden" name="ticket_id" value="<?= (int) $ticket['id']; ?>">

                    <label for="status" class="form-label mb-0 small text-muted text-nowrap">Status:</label>

                    <select name="status" id="status" class="form-select form-select-sm w-auto"
                        data-current="<?= htmlspecialchars($ticket['status'], ENT_QUOTES, 'UTF-8'); ?>"
                        onchange="if (confirm('Tem certeza que deseja atualizar o status deste chamado?')) { this.form.submit() } else { this.value = this.dataset.current; }">
                        <?php foreach ($statusLabels as $statusValue => $statusLabel): ?>
                        <option value="<?= htmlspecialchars($statusValue, ENT_QUOTES, 'UTF-8'); ?>"
                            <?= $ticket['status'] === $statusValue ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($statusLabel, ENT_QUOTES, 'UTF-8') ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </form>
            </div>
        </div>

        <div class="card-body">
            <h2 class="h5 mb-3">
                <?= htmlspecialchars($ticket['subject'], ENT_QUOTES, 'UTF-8'); ?>
            </h2>

            <div class="row mb-3">
                <div class="col-md-6">
                    <p class="mb-1">
                        <strong>Usuário:</strong>
                        <?= htmlspecialchars($ticket['user_name'], ENT_QUOTES, 'UTF-8'); ?>
                    </p>

                    <p class="mb-1">
                        <strong>E-mail:</strong>
                        <?= htmlspecialchars($ticket['user_email'], ENT_QUOTES, 'UTF-8'); ?>
                    </p>

                    <p class="mb-1">
                        <strong>Categoria:</strong>
                        <?= htmlspecialchars($categoryLabels[$ticket['category']], ENT_QUOTES, 'UTF-8'); ?>
                    </p>

                    <p class="mb-1">
                        <strong>Prioridade:</strong>
                        <span class="badge <?= $priorityClasses[$ticket['priority']] ?? 'bg-secondary' ?>">
                            <?= htmlspecialchars($priorityLabels[$ticket['priority']], ENT_QUOTES, 'UTF-8'); ?>
                        </span>
                    </p>

                    <p class="mb-1">
                        <strong>Criado em:</strong>
                        <?= (new DateTime($ticket['created_at']))->format('d/m/Y H:i'); ?>
                    </p>
                </div>
            </div>

            <hr>

            <h3 class="h6">Descrição</h3>

            <p class="mb-0">
                <?= nl2br(htmlspecialchars($ticket['description'], ENT_QUOTES, 'UTF-8')); ?>
            </p>

            <hr>

            <h3 class="h6 mb-3">Histórico de respostas</h3>

            <?php if (empty($replies)): ?>
            <div class="alert alert-info">
                Ainda não há respostas neste chamado.
            </div>
            <?php else: ?>
            <?php foreach ($replies as $reply): ?>
            <?php
                            $isStaffReply = in_array($reply['user_role'], ['technician', 'admin'], true);
                            $replyClass = $isStaffReply 
                            ? 'ticket-reply ticket-reply-staff ms-auto'
                            : 'ticket-reply ticket-reply-user me-auto';
                        ?>
            <div class="<?= $replyClass ?>">
                <div class="d-flex justify-content-between mb-2">
                    <strong>
                        <?= htmlspecialchars($reply['user_name'], ENT_QUOTES, 'UTF-8'); ?> |
                        <?= htmlspecialchars($roleLabels[$reply['user_role']], ENT_QUOTES, 'UTF-8'); ?>
                    </strong>

                    <small class="text-muted">
                        <?= (new DateTime($reply['created_at']))->format('d/m/Y H:i'); ?>
                    </small>
                </div>

                <p class="mb-0">
                    <?= nl2br(htmlspecialchars($reply['message'], ENT_QUOTES, 'UTF-8')); ?>
                </p>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>