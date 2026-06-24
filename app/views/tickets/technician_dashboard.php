<?php require __DIR__ ."/../layouts/header.php"; ?>

<div class="container mt-4">
    <h1 class="h3 mb-4">Chamados para atendimento</h1>

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
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require __DIR__ ."/../layouts/footer.php"; ?>