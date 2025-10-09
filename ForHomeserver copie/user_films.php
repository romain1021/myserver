<?php
// Tableau de bord utilisateur - proposition et affichage des films validés
session_start();
if (!isset($_SESSION['identifiant'])) {
    header('Location: login.php');
    exit;
}
$mysqli = new mysqli('localhost', 'root', '', 'forhomeserver');
if ($mysqli->connect_errno) {
    die('Erreur connexion MySQL : ' . $mysqli->connect_error);
}
// Proposition de film
if (isset($_POST['proposer']) && !empty($_POST['titre'])) {
    $titre = trim($_POST['titre']);
    $identifiant = $_SESSION['identifiant'];
    $stmt = $mysqli->prepare('INSERT INTO films (titre, propose_par) VALUES (?, ?)');
    $stmt->bind_param('ss', $titre, $identifiant);
    $stmt->execute();
    $stmt->close();
}
// Films validés
$result = $mysqli->query('SELECT * FROM films WHERE valide = 1 ORDER BY date_ajout DESC LIMIT 10');
$recent = [];
while ($row = $result->fetch_assoc()) {
    $recent[] = $row;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Films - Utilisateur</title>
    <link rel="stylesheet" href="style.css">
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
            <button type="submit" name="proposer" style="background:#B19CD9;color:#fff;border:none;padding:0.5rem 1.2rem;border-radius:12px;font-weight:600;cursor:pointer;">Proposer</button>
        </form>
        <div class="recent-block">
            <h3>Films récemment ajoutés (validés)</h3>
            <ul>
                <?php foreach ($recent as $film): ?>
                <li><?= htmlspecialchars($film['titre']) ?> (proposé par <?= htmlspecialchars($film['propose_par']) ?>)</li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</body>
</html>
