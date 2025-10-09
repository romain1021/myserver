<?php
session_start();
$error = '';
$mysqli = new mysqli('localhost', 'root', '', 'forhomeserver');
if ($mysqli->connect_errno) {
    die('Erreur connexion MySQL : ' . $mysqli->connect_error);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifiant = $_POST['identifiant'] ?? '';
    $password = $_POST['password'] ?? '';
    $stmt = $mysqli->prepare('SELECT password, admin, photo FROM users WHERE identifiant = ?');
    $stmt->bind_param('s', $identifiant);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashedPassword, $isAdmin, $photo);
        $stmt->fetch();
        if (password_verify($password, $hashedPassword)) {
            $_SESSION['identifiant'] = $identifiant;
            $_SESSION['admin'] = $isAdmin;
            if ($isAdmin) {
                header('Location: admin_dashboard.php');
            } else {
                header('Location: user_films.php');
            }
            exit;
        } else {
            $error = 'Mot de passe incorrect.';
        }
    } else {
        $error = 'Identifiant inconnu.';
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .container { max-width: 400px; margin: 60px auto; background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .icon { text-align: center; margin-bottom: 20px; }
        .icon svg { width: 60px; height: 60px; fill: #007bff; }
        .error { color: red; text-align: center; margin-bottom: 10px; }
        .forgot { text-align: right; margin-top: 5px; }
        .btn { width: 100%; margin-top: 10px; }
    </style>
</head>
<body>
<div class="container">
    <div class="icon">
        <svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 8-4 8-4s8 0 8 4v2H4v-2z"/></svg>
    </div>
    <h2 style="text-align:center;">Connexion</h2>
    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="post">
    <label>Identifiant :</label>
    <input type="text" name="identifiant" required class="form-control"><br>
        <label>Mot de passe :</label>
        <input type="password" name="password" required class="form-control"><br>
        <button type="submit" class="btn" style="background:#007bff;color:#fff;border:none;">Se connecter</button>
        <div class="forgot">
            <a href="forgot_password.php">Mot de passe oublié ?</a>
        </div>
    </form>
    <div style="text-align:center;margin-top:15px;">
        <a href="register.php">Créer un compte</a><br>
        <a href="home.php" style="color:#7A6BA8;margin-top:8px;display:inline-block;">Retour à l'accueil</a>
    </div>
</div>
</body>
</html>
