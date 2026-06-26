<?php require __DIR__ ."/../layouts/header.php"; ?>

<div class="container mt-5">
    <h2>Acompanhar Chamado</h2>

    <?php if (!empty($sucessMessage)): ?>
        <div class="alert alert-success mt-3" role="alert">
            <?= htmlspecialchars($sucessMessage, ENT_QUOTES, 'UTF-8'); ?>
        </div>
    <?php endif; ?>

    <form action="index.php" method="GET">
        <input type="hidden" name="page" value="track_tickets">
        
        <div class="mb-3">
            <label for="ticket_id" class="form-label">
                ID do Chamado
            </label>

            <input 
                type="number" 
                class="form-control" 
                id="ticket_id" 
                name="ticket_id"
                min="1"
                value="<?= htmlspecialchars((string) $ticketId, ENT_QUOTES, 'UTF-8') ?>"
            >
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="bi bi-search me-2"></i>
            Pesquisar
        </button>
    
        <a href="?page=track_tickets" class="btn btn-secondary"><i class="bi bi-eraser-fill me-2"></i>Limpar Pesquisa</a>
    </form>

    <?php if ($error): ?>
        <div class="alert alert-danger mt-4">
            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
        </div>
    <?php endif; ?>

        <?php if ($ticket): ?>
            <div class="card mt-4">
                <div class="card-header">
                    Chamado #<?= htmlspecialchars($ticket['id'], ENT_QUOTES, 'UTF-8'); ?> - <?= htmlspecialchars($ticket['subject'], ENT_QUOTES, 'UTF-8'); ?>
                </div>

                <div class="card-body">
                    <p>
                        <strong>Assunto:</strong>
                        <?= htmlspecialchars($ticket['subject'], ENT_QUOTES, 'UTF-8'); ?>
                    </p>

                    <p>
                        <strong>Categoria:</strong>
                        <?= htmlspecialchars($categoryLabels[$ticket['category']], ENT_QUOTES, 'UTF-8'); ?>
                    </p>

                    <p>
                        <strong>Prioridade:</strong>
                            <span class="badge <?= $priorityClasses[$ticket['priority']] ?? 'bg-secondary' ?>">
                                <?= htmlspecialchars($priorityLabels[$ticket['priority']], ENT_QUOTES, 'UTF-8'); ?>
                            </span>
                    </p>

                    <p>
                        <strong>Status:</strong>
                            <span class="badge <?= $statusClasses[$ticket['status']] ?? 'bg-secondary' ?>">
                                <?= htmlspecialchars($statusLabels[$ticket['status']], ENT_QUOTES, 'UTF-8'); ?>
                            </span>
                    </p>

                    <p class="mb-1"><strong>Descrição:</strong></p>

                    <p>
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
                            <div class="border rounded p-3 mb-3">
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
        <?php endif; ?>

        <hr>

        <form action="index.php?page=customer_ticket_reply_store" method="POST">
            <input type="hidden" name="ticket_id" value="<?= (int) $ticket['id'] ?>">

            <div class="mb-3">
                <label for="message" class="form-label">Responder chamado</label>

                <textarea
                    name="message"
                    id="message"
                    class="form-control"
                    rows="4"
                    required
                ></textarea>
            </div>

            <button type="submit" class="btn btn-primary">
                Enviar resposta
            </button>
        </form>
    </div>

    <div class="container mt-5">
        <hr>
        <h3>Últimos Chamados</h3>

        <?php if (empty($recentTickets)): ?>
            <div class="alert alert-info">
                Você ainda não criou nenhum chamado.
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Assunto</th>
                            <th>Categoria</th>
                            <th>Prioridade</th>
                            <th>Status</th>
                            <th>Aberto em</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($recentTickets as $recentTicket): ?>
                            <tr>
                                <td>
                                    #<?= $recentTicket['id'] ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($recentTicket['subject'], ENT_QUOTES, 'UTF-8') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $categoryLabels[$recentTicket['category']] ?? $recentTicket['category'], ENT_QUOTES, 'UTF-8'
                                    ) ?>
                                </td>

                                <td>
                                    <span class="badge <?= $priorityClasses[$recentTicket['priority']] ?? 'bg-secondary' ?>">
                                        <?= htmlspecialchars(
                                            $priorityLabels[$recentTicket['priority']] ?? $recentTicket['priority'], ENT_QUOTES, 'UTF-8'
                                        ) ?>
                                    </span>
                                </td>

                                <td>
                                    <span class="badge <?= $statusClasses[$recentTicket['status']] ?? 'bg-secondary' ?>">
                                        <?= htmlspecialchars(
                                            $statusLabels[$recentTicket['status']], ENT_QUOTES, 'UTF-8'
                                        ) ?>
                                    </span>
                                </td>

                                <td>
                                    <?= (new DateTime ($recentTicket['created_at']))->format('d/m/Y'); ?>
                                </td>

                                <td class="text-end">
                                    <a
                                        class="btn btn-sm btn-outline-primary"
                                        href="?page=track_tickets&ticket_id=<?= (int) $recentTicket['id'] ?>"
                                    >
                                        Ver Chamado
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ ."/../layouts/footer.php"; ?>