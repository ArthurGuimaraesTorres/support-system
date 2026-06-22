<?php require __DIR__ ."/../layouts/header.php"; ?>

<div class="container mt-5">
    <h2>Acompanhar Chamado</h2>

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
                required
            >
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="bi bi-search me-2"></i>
            Pesquisar
        </button>
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
                        <?= htmlspecialchars($ticket['category'], ENT_QUOTES, 'UTF-8'); ?>
                    </p>

                    <p>
                        <strong>Prioridade:</strong>
                        <?= htmlspecialchars($ticket['priority'], ENT_QUOTES, 'UTF-8'); ?>
                    </p>

                    <p>
                        <strong>Status:</strong>
                        <?= htmlspecialchars($ticket['status'], ENT_QUOTES, 'UTF-8'); ?>
                    </p>

                    <p class="mb-1"><strong>Descrição:</strong>

                    <p>
                        <?= nl2br(htmlspecialchars($ticket['description'], ENT_QUOTES, 'UTF-8')); ?>
                    </p>
                </div>
            </div>
        <?php endif; ?>
    </div>

<?php require __DIR__ ."/../layouts/footer.php"; ?>