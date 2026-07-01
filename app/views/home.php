<?php require __DIR__ . '/layouts/header.php'; ?>

<main class="container py-4">
    <section class="text-center py-5">
        <h1 class="fw-bold mb-3">Central de Suporte GTs.</h1>

        <p class="lead text-muted mx-auto" style="max-width: 720px;">
            Abra chamados, acompanhe seus atendimentos e mantenha a comunicação com a equipe técnica em um só lugar.
        </p>

        <div class="d-flex justify-content-center gap-2 mt-4 flex-wrap">
            <?php if ($_SESSION['role'] === 'customer'): ?>
            <a href="?page=create_ticket" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>
                Criar Chamado
            </a>

            <a href="?page=track_tickets" class="btn btn-outline-primary">
                <i class="bi bi-search me-2"></i>
                Acompanhar Chamados
            </a>
            <?php endif; ?>
        </div>
    </section>

    <div class="row g-4 mt-2 justify-content-center text-center">
        <div class="col-md-4">
            <div class="card h-100 shadow-sm home-feature-card">
                <div class="card-body">
                    <i class="bi bi-ticket-perforated fs-2 text-primary"></i>
                    <h5 class="mt-3">Abrir chamados</h5>
                    <p class="text-muted">
                        Registre problemas, dúvidas ou solicitações para a equipe de suporte.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 shadow-sm home-feature-card">
                <div class="card-body">
                    <i class="bi bi-search fs-2 text-primary"></i>
                    <h5 class="mt-3">Acompanhar Chamados</h5>
                    <p class="text-muted">
                        Veja o andamento dos seus chamados e as respostas recebidas.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 shadow-sm home-feature-card">
                <div class="card-body">
                    <i class="bi bi-chat-dots fs-2 text-primary"></i>
                    <h5 class="mt-3">Conversar com o suporte</h5>
                    <p class="text-muted">
                        Continue o atendimento diretamente pelo histórico do chamado, sem precisar de e-mails ou
                        ligações.
                    </p>
                </div>
            </div>
        </div>
    </div>
</main>

    <?php require __DIR__ . '/layouts/footer.php'; ?>