<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-4 d-flex flex-column align-items-center">
    <h1>Criar Ticket</h1>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger w-50" role="alert">
            <?php foreach ($errors as $error): ?>
                <div><?= htmlspecialchars($error) ?> </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form action="?page=create_ticket_submit" method="POST" class="w-50">
        <div class="mb-3">
            <label for="subject" class="form-label">Assunto*</label>
            <input 
                type="text"
                name="subject"
                id="subject"
                class="form-control"
                maxlength="150"
                required
            >
        </div>

        <div class="mb-3">
            <label for="category" class="form-label">Categoria*</label>
            <select class="form-control" id="category" name="category" required>
                <option value="">Selecione uma categoria</option>
                <option value="bug">Bug</option>
                <option value="hardware">Hardware</option>
                <option value="network">Rede</option>
                <option value="other">Outros</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="priority" class="form-label">Prioridade*</label>
            <select class="form-control" id="priority" name="priority" required>
                <option value="">Selecione uma prioridade</option>
                <option value="low">Baixa</option>
                <option value="medium">Média</option>
                <option value="high">Alta</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Descrição*</label>
            <textarea 
                class="form-control" 
                id="description" 
                name="description" 
                rows="6"
                required
            ></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Criar Ticket</button>
    </form>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>