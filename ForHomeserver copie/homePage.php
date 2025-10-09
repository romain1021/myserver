<?php
// Page d'accueil après connexion
session_start();
if (!isset($_SESSION['identifiant'])) {
    header('Location: connexion.php');
    exit;
}
$identifiant = $_SESSION['identifiant'];
$mysqli = new mysqli('localhost', 'root', '', 'forhomeserver');
if ($mysqli->connect_errno) {
    die('Erreur connexion MySQL : ' . $mysqli->connect_error);
}
$stmt = $mysqli->prepare('SELECT admin, photo FROM users WHERE identifiant = ?');
$stmt->bind_param('s', $identifiant);
$stmt->execute();
$stmt->bind_result($isAdmin, $photo);
$stmt->fetch();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .container { max-width: 500px; margin: 60px auto; background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); text-align:center; }
        .admin { color: #007bff; font-weight: bold; }
        .logout { margin-top: 20px; }
    </style>
</head>
<body>
<div class="container">
    <?php if ($photo && file_exists(__DIR__ . '/' . $photo)): ?>
        <img src="<?= htmlspecialchars($photo) ?>" alt="Photo de profil" style="width:120px;height:120px;border-radius:50%;object-fit:cover;margin-bottom:15px;border:3px solid #B19CD9;box-shadow:0 2px 8px rgba(177,156,217,0.15);">
    <?php else: ?>
        <img src="https://ui-avatars.com/api/?name=<?= urlencode($identifiant) ?>&background=B19CD9&color=fff&size=120" alt="Avatar" style="width:120px;height:120px;border-radius:50%;object-fit:cover;margin-bottom:15px;border:3px solid #B19CD9;box-shadow:0 2px 8px rgba(177,156,217,0.15);">
    <?php endif; ?>
    <h2>Bienvenue, <?= htmlspecialchars($identifiant) ?> !</h2>
    <?php if ($isAdmin): ?>
        <div class="admin">Statut : Administrateur</div>
    <?php else: ?>
        <div>Statut : Utilisateur</div>
    <?php endif; ?>
    <div class="logout">
        <form method="post" action="logout.php">
            <button type="submit">Déconnexion</button>
        </form>
    </div>
</div>
</body>
</html>
