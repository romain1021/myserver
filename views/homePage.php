<?php
session_start();
if (!isset($_SESSION['identifiant'])) {
    header('Location: ../views/login.php');
    exit;
}
$identifiant = $_SESSION['identifiant'];
$mysqli = new mysqli('localhost', 'root', '', 'forhomeserver');
if ($mysqli->connect_errno) {
    die('Erreur connexion MySQL : ' . $mysqli->connect_error);
}
require_once __DIR__ . '/../controllers/UserDashboardController.php';
$controller = new UserDashboardController($mysqli, $identifiant);
$user = $controller->user;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<div class="container">
    <?php if ($user['photo'] && file_exists(__DIR__ . '/../' . $user['photo'])): ?>
        <img src="<?= htmlspecialchars($user['photo']) ?>" alt="Photo de profil" style="width:120px;height:120px;border-radius:50%;object-fit:cover;margin-bottom:15px;border:3px solid #B19CD9;box-shadow:0 2px 8px rgba(177,156,217,0.15);">
    <?php else: ?>
        <img src="https://ui-avatars.com/api/?name=<?= urlencode($identifiant) ?>&background=B19CD9&color=fff&size=120" alt="Avatar" style="width:120px;height:120px;border-radius:50%;object-fit:cover;margin-bottom:15px;border:3px solid #B19CD9;box-shadow:0 2px 8px rgba(177,156,217,0.15);">
    <?php endif; ?>
    <h2>Bienvenue, <?= htmlspecialchars($identifiant) ?> !</h2>
    <?php if ($user['admin']): ?>
        <div class="admin">Statut : Administrateur</div>
    <?php else: ?>
        <div>Statut : Utilisateur</div>
    <?php endif; ?>
    <div class="logout">
        <form method="post" action="../logout.php">
            <button type="submit" style="background:#B19CD9;color:#fff;border:none;padding:0.5rem 1.2rem;border-radius:12px;font-weight:600;cursor:pointer;">Se déconnecter</button>
        </form>
    </div>
</div>
</body>
</html>
