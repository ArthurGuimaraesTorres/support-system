<?php

    require_once __DIR__ . '/../models/User.php';

    class ProfileController
    {
        private User $profileModel;

        public function __construct(PDO $pdo)
        {
            $this->profileModel = new User($pdo);
        }

        public function showProfile(): void
        {
            $userId = (int) $_SESSION['user_id'];
            $user = $this->profileModel->findById($userId);

            if (!$user) {
                session_destroy();
                header('Location: ?page=login');
                exit;
            }

            require __DIR__ . '/../views/profile.php';
        }

        public function updateProfile(): void
        {
            $userId = (int) $_SESSION['user_id'];

            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $password = $_POST['password'] ?? '';

            if ($name === '' || !filter_var($email , FILTER_VALIDATE_EMAIL)) {
                $_SESSION['profile_error'] = 'Informe um nome e um e-mail válidos.';
                header('Location: ?page=profile');
                exit;
            }

            $updated = $this->profileModel->update(
                $userId,
                $name,
                $email,
                $phone
            );

            if ($updated && $password !== '') {
                $this->profileModel->updatePassword($userId, $password);
            }

            if ($updated) {
                $_SESSION['name'] = $name;
                $_SESSION['profile_success'] = 'Perfil atualizado com sucesso.';
            } else {
                $_SESSION['profile_error'] = 'Não foi possível atualizar o perfil.';
            }

            header('Location: ?page=profile');
            exit;
        }
    }