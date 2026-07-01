<?php require __DIR__ . '/layouts/header.php'; ?>

<div class="container mt-4 d-flex flex-column align-items-center">
    <h1>Bem-vindo ao GTs. | Support</h1>
    <p>Este é o sistema de suporte da GTs. Aqui você pode criar tickets de suporte, acompanhar o status dos seus chamados e entrar em contato com nossa equipe de atendimento.</p>

    <?php if ($_SESSION['role'] === 'customer'): ?>
        <button 
            class="btn btn-primary" 
            onclick="window.location.href='?page=create_ticket'">
            <i class="bi bi-plus-circle me-2"></i>
                Criar Chamado
        </button>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/layouts/footer.php'; ?>