<?php require __DIR__ ."/layouts/header.php"; ?>

<div class="container mt-5">
    <h2>Perfil do Usuário</h2>
    <form action="?page=profile_update" method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">Nome</label>
            <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8'); ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8'); ?>" required>
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Telefone</label>
            <input type="text" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($user['phone'], ENT_QUOTES, 'UTF-8'); ?>">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Nova Senha (opcional)</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>
        <?php if (isset($_SESSION['profile_error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['profile_error'], ENT_QUOTES, 'UTF-8'); ?></div>
            <?php unset($_SESSION['profile_error']); ?>
        <?php endif; ?>
        <?php if (isset($_SESSION['profile_success'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_SESSION['profile_success'], ENT_QUOTES, 'UTF-8'); ?></div>
            <?php unset($_SESSION['profile_success']); ?>
        <?php endif; ?>
        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        <a href="?page=home" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<?php require __DIR__ ."/layouts/footer.php"; ?>