<?php
session_start();
if (!isset($_SESSION['identifiant'])) {
    header('Location: ../views/login.php');
    exit;
}
$mysqli = new mysqli('localhost', 'root', '', 'forhomeserver');
if ($mysqli->connect_errno) {
    die('Erreur connexion MySQL : ' . $mysqli->connect_error);
}
require_once __DIR__ . '/../controllers/UserFilmsController.php';
$controller = new UserFilmsController($mysqli, $_SESSION['identifiant']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Films - Utilisateur</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        .recent-block { background:#F8F6FF; border-radius:18px; padding:1.5rem; margin-bottom:2rem; }
        .propose-form { margin-bottom:2rem; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Proposer un film</h2>
        <form method="post" class="propose-form">
            <input type="text" name="titre" placeholder="Titre du film..." required style="padding:0.5rem 1rem;">
            <button type="submit" name="proposer" style="background:#B19CD9;color:#fff;">Proposer</button>
        </form>
        <div class="recent-block">
            <h3>Films validés récemment</h3>
            <ul>
                <?php foreach ($controller->recent as $film): ?>
                    <li><?= htmlspecialchars($film['titre']) ?> (proposé par <?= htmlspecialchars($film['propose_par']) ?>)</li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</body>
</html>
