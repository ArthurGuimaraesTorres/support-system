<?php
    $hideNavbar = true;
    require __DIR__ . '/../layouts/header.php';
?>

<div class="container mt-4 d-flex flex-column align-items-center">
    <h1>Login</h1>
    <form action="?page=login_submit" method="POST" class="w-50">
        <div class="mb-3">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Senha</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
        <a href="?page=register" class="btn btn-link">Não tem uma conta? Registre-se</a>
    </form>