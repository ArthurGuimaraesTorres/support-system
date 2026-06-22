<?php 
    $hideNavbar = true;
    require __DIR__ . '/../layouts/header.php'; 
?>

<div class="container mt-4 d-flex flex-column align-items-center">
    <h1>Registrar</h1>
    <form action="?page=register_submit" method="POST" class="w-50">
        <div class="mb-3">
            <label for="name" class="form-label">Nome*</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">E-mail*</label>
            <input type="email" class="form-control" id="email" name="email" required>
        <div class="mb-3">
            <label for="phone" class="form-label">Telefone</label>
            <input type="text" class="form-control" id="phone" name="phone">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Senha*</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Registrar</button>
        <a href="?page=login" class="btn btn-link">Já tem uma conta? Faça login</a>
    </form>