<?php
$mysqli = new mysqli('localhost', 'root', '', 'forhomeserver');
if ($mysqli->connect_errno) {
    die('Erreur connexion MySQL : ' . $mysqli->connect_error);
}
require_once __DIR__ . '/../controllers/PasswordRecoveryController.php';
$controller = new PasswordRecoveryController();
$controller->recover($mysqli);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mot de passe oublié</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<div class="container">
    <h2 style="text-align:center;">Mot de passe oublié</h2>
    <?php if (!empty($controller->msg)): ?>
        <div class="msg" style="text-align:center;color:#7A6BA8;"><?= htmlspecialchars($controller->msg) ?></div>
    <?php endif; ?>
    <form method="post">
        <label>Email :</label>
        <input type="email" name="email" required class="form-control"><br>
        <button type="submit" class="btn" style="background:#B19CD9;color:#fff;">Réinitialiser</button>
    </form>
    <div style="text-align:center;margin-top:15px;">
        <a href="login.php" style="color:#7A6BA8;">Retour à la connexion</a>
    </div>
</div>
</body>
</html>
