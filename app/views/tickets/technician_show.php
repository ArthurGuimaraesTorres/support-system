<?php require __DIR__ ."/../layouts/header.php"; ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Atendimento do chamado</h1>

        <a href="?page=technician_tickets" class="btn btn-outline-secondary btn-sm">
            Voltar
        </a>
    </div>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
        </div>
    <?php elseif (!empty($ticket)): ?>
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong>
                    Chamado #<?= (int) $ticket['id'] ?>
                </strong>

                <form
                    action="index.php?page=technician_ticket_status_update"
                    method="POST"
                    class="d-flex align-items-center gap-2 mb-0"
                >
                    <input type="hidden" name="ticket_id" value="<?= (int) $ticket['id']; ?>">

                    <label for="status" class="visually-hidden">Status</label>

                    <select name="status" 
                            id="status" 
                            class="form-select form-select-sm w-auto"
                            onchange="this.form.submit()"        
                    >
                        <?php foreach ($statusLabels as $statusValue => $statusLabel): ?>
                            <option
                                value="<?= htmlspecialchars($statusValue, ENT_QUOTES, 'UTF-8'); ?>"
                                <?= $ticket['status'] === $statusValue ? 'selected' : ''; ?>
                            >
                                <?= htmlspecialchars($statusLabel, ENT_QUOTES, 'UTF-8') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </form>
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

                <hr>

                <form action="index.php?page=technician_ticket_reply_store" method="POST">
                    <input type="hidden" name="ticket_id" value="<?= (int) $ticket['id'] ?>">

                        <label for="message" class="form-label">Responder chamado</label>

                        <div class="d-flex gap-2 align-items-end">
                            <textarea
                                name="message"
                                id="message"
                                class="form-control"
                                rows="4"
                                required
                            ></textarea>

                            <button type="submit" class="btn btn-primary" aria-label="Enviar resposta">
                                <i class="bi bi-send-fill"></i>
                            </button>
                        </div>
                </form>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require __DIR__ .'/../layouts/footer.php';