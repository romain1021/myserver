<?php
// Page de récupération de mot de passe
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $mysqli = new mysqli('localhost', 'root', '', 'forhomeserver');
    if ($mysqli->connect_errno) {
        die('Erreur connexion MySQL : ' . $mysqli->connect_error);
    }
    $stmt = $mysqli->prepare('SELECT identifiant FROM users WHERE email = ?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($identifiant);
        $stmt->fetch();
        $msg = "Un email de réinitialisation a été envoyé (simulation) à l'utilisateur : " . htmlspecialchars($identifiant);
    } else {
        $msg = "Adresse email non trouvée.";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mot de passe oublié</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .container { max-width: 400px; margin: 60px auto; background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .btn { width: 100%; margin-top: 10px; }
        .msg { text-align: center; color: #007bff; margin-bottom: 10px; }
    </style>
</head>
<body>
<div class="container">
    <h2 style="text-align:center;">Mot de passe oublié</h2>
    <?php if (!empty($msg)): ?>
        <div class="msg"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>
    <form method="post">
        <label>Email :</label>
        <input type="email" name="email" required class="form-control"><br>
        <button type="submit" class="btn">Envoyer</button>
    </form>
    <div style="text-align:center;margin-top:15px;">
        <a href="connexion.php">Retour à la connexion</a>
    </div>
</div>
</body>
</html>
