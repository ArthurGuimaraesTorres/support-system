<?php

    require_once __DIR__ . '/../models/User.php';

    class AuthController
    {
        private User $userModel;

        public function __construct(PDO $pdo)
        {
            $this->userModel = new User($pdo);
        }

        public function showLogin()
        {
            require __DIR__ . '/../views/auth/login.php';
        }

        public function showRegister()
        {
            require __DIR__ . '/../views/auth/register.php';
        }

        public function register(): void
        {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $password = $_POST['password'] ?? '';

            if ($this->userModel->create($name, $email, $phone, $password)) {
                header('Location: ?page=login');
                exit;
            } else {
                echo "Erro ao registrar usuário.";
            }
        }

        public function login(): void
        {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $user = $this->userModel->findByEmail($email);
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name'] = $user['name'];

                header('Location: ?page=home');
                exit;
            } else {
                echo "E-mail ou senha inválidos.";
            }
        }

        public function logout(): void
        {
            session_destroy();
            header('Location: ?page=login');
            exit;
        }
    }