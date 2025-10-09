<?php
session_start();
if (!isset($_SESSION['identifiant']) || !$_SESSION['admin']) {
    header('Location: ../views/login.php');
    exit;
}
$mysqli = new mysqli('localhost', 'root', '', 'forhomeserver');
if ($mysqli->connect_errno) {
    die('Erreur connexion MySQL : ' . $mysqli->connect_error);
}
require_once __DIR__ . '/../controllers/AdminFilmsController.php';
$controller = new AdminFilmsController($mysqli);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin - Films</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        .recent-block { background:#F8F6FF; border-radius:18px; padding:1.5rem; margin-bottom:2rem; }
        .film-table { width:100%; border-collapse:collapse; margin-bottom:2rem; }
        .film-table th, .film-table td { padding:0.7rem 1rem; border-bottom:1px solid #E5D9F2; }
        .film-table th { background:#B19CD9; color:#fff; }
        .film-table tr:last-child td { border-bottom:none; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Gestion des films</h2>
        <table class="film-table">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Proposé par</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($controller->films as $film): ?>
                <tr>
                    <td><?= htmlspecialchars($film['titre']) ?></td>
                    <td><?= htmlspecialchars($film['propose_par']) ?></td>
                    <td><?= htmlspecialchars($film['date_ajout']) ?></td>
                    <td>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="film_id" value="<?= $film['id'] ?>">
                            <button type="submit" name="valider" style="background:#7A6BA8;color:#fff;">Valider</button>
                        </form>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="film_id" value="<?= $film['id'] ?>">
                            <button type="submit" name="delete" style="background:#B19CD9;color:#fff;">Supprimer</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
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
