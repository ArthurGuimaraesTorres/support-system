        <footer class="border-top mt-auto py-4 bg-light">
            <div
                class="container-fluid px-5 d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 small text-muted">
                <div>
                    <strong class="text-primary">GTs. | Support</strong>
                    &copy; <?= date('Y') ?> - Sistema de chamados
                </div>

                <div class="d-flex flex-wrap gap-3">
                    <a href="?page=home" class="text-secondary text-decoration-none">
                        <i class="bi bi-house me-1"></i>
                        Início
                    </a>

                    <?php if ($_SESSION['role'] === 'customer'): ?>
                    <span>
                        <i class="bi bi-clock-history me-1"></i>
                        Atendimento
                        <i class="bi bi-arrow-right"></i>
                    </span>

                    <a href="?page=create_ticket" class="text-decoration-none">
                        Abrir chamado
                    </a>

                    <a href="?page=track_tickets" class="text-decoration-none">
                        Acompanhar chamado
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </footer>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
        </script>
        </body>

        </html>