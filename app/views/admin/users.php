<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-4">
    <h1 class="h3 mb-4">Gerenciar Usuários</h1>

    <form method="GET" action="index.php" class="row g-3 mb-4">
        <input type="hidden" name="page" value="admin_users">

        <div class="col-md-5">
            <label for="search" class="form-label">Buscar</label>
            <input
                type="text"
                class="form-control"
                id="search"
                name="search"
                value="<?= htmlspecialchars($filters['search'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                placeholder="Nome ou e-mail"
            >
        </div>

        <div class="col-md-3">
            <label for="role" class="form-label">Perfil</label>
            <select name="role" id="role" class="form-select">
                <option value="">Todos</option>
                <option value="customer" <?= ($filters['role'] ?? '') === 'customer' ? 'selected' : '' ?>>Cliente</option>
                <option value="technician" <?= ($filters['role'] ?? '') === 'technician' ? 'selected' : '' ?>>Técnico</option>
                <option value="admin" <?= ($filters['role'] ?? '') === 'admin' ? 'selected' : '' ?>>Administrador</option>
            </select>
        </div>

        <div class="col-md-4 d-flex align-items-end gap-2">
            <button type="submit" class="btn btn-primary"><i class="bi bi-filter-circle-fill me-2"></i>Filtrar</button>
            <a href="?page=admin_users" class="btn btn-secondary"><i class="bi bi-eraser-fill me-2"></i>Limpar</a>
        </div>
    </form>

    <hr>

    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Telefone</th>
                    <th>Perfil</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($user['phone'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($roleLabels[$user['role']] ?? $user['role'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td class="text-end">
                            <form
                                action="?page=admin_user_update_role"
                                method="POST"
                                class="d-flex justify-content-end gap-2"
                            >
                                <input type="hidden" name="user_id" value="<?= (int) $user['id'] ?>">

                                <select name="role" class="form-select form-select-sm w-auto">
                                    <option value="customer" <?= $user['role'] === 'customer' ? 'selected' : '' ?>>Cliente</option>
                                    <option value="technician" <?= $user['role'] === 'technician' ? 'selected' : '' ?>>Técnico</option>
                                    <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Administrador</option>
                                </select>

                                <button type="submit" class="btn btn-sm btn-primary">Atualizar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>