<?php
// Redirection selon le rôle de l'utilisateur
session_start();
if (!isset($_SESSION['identifiant'])) {
    header('Location: login.php');
    exit;
}
if (isset($_SESSION['admin']) && $_SESSION['admin']) {
    header('Location: admin_dashboard.php');
    exit;
} else {
    header('Location: user_films.php');
    exit;
}
?>
