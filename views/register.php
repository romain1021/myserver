<?php
$mysqli = new mysqli('localhost', 'root', '', 'forhomeserver');
if ($mysqli->connect_errno) {
    die('Erreur connexion MySQL : ' . $mysqli->connect_error);
}
require_once __DIR__ . '/../controllers/AuthController.php';
$controller = new AuthController();
$controller->register($mysqli);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<div class="container">
    <h2 style="text-align:center;">Inscription</h2>
    <?php if ($controller->error): ?>
        <div class="error" style="color:#B19CD9;text-align:center;"><?= htmlspecialchars($controller->error) ?></div>
    <?php endif; ?>
    <form method="post" enctype="multipart/form-data">
        <label>Identifiant :</label>
        <input type="text" name="identifiant" required class="form-control"><br>
        <label>Email :</label>
        <input type="email" name="email" required class="form-control"><br>
        <label>Mot de passe :</label>
        <input type="password" name="password" required class="form-control"><br>
        <label>Photo de profil :</label>
        <input type="file" name="photo" accept="image/*" class="form-control"><br>
        <button type="submit" class="btn" style="background:#B19CD9;color:#fff;">S'inscrire</button>
    </form>
    <div style="text-align:center;margin-top:15px;">
        <a href="login.php" style="color:#7A6BA8;">Déjà un compte ? Se connecter</a>
    </div>
</div>
</body>
</html>
