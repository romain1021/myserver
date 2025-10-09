<?php
require_once __DIR__ . '/../models/AuthModel.php';
class AuthController {
    public $error = '';
    public function login($mysqli) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $identifiant = $_POST['identifiant'] ?? '';
            $password = $_POST['password'] ?? '';
            $authModel = new AuthModel($mysqli);
            $user = $authModel->login($identifiant, $password);
            if ($user) {
                session_start();
                $_SESSION['identifiant'] = $user['identifiant'];
                $_SESSION['admin'] = $user['admin'];
                $_SESSION['photo'] = $user['photo'];
                // Redirection automatique selon le rôle
                if ($user['admin']) {
                    header('Location: ../router.php?page=admin_dashboard');
                } else {
                    header('Location: ../router.php?page=user_films');
                }
                exit;
            } else {
                $this->error = 'Identifiant ou mot de passe incorrect.';
            }
        }
    }
    public function register($mysqli) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $identifiant = $_POST['identifiant'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $photoPath = null;
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                $photoName = uniqid('photo_') . '_' . basename($_FILES['photo']['name']);
                $photoPath = 'photos/' . $photoName;
                move_uploaded_file($_FILES['photo']['tmp_name'], __DIR__ . '/../' . $photoPath);
            }
            $authModel = new AuthModel($mysqli);
            $result = $authModel->register($identifiant, $email, $password, $photoPath);
            if ($result === true) {
                // Connexion automatique après inscription
                session_start();
                $_SESSION['identifiant'] = $identifiant;
                $_SESSION['admin'] = 0;
                $_SESSION['photo'] = $photoPath;
                header('Location: ../router.php?page=user_films');
                exit;
            } else {
                $this->error = $result;
            }
        }
    }
}
