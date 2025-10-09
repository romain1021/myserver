<?php
session_start();
$error = '';
$mysqli = new mysqli('localhost', 'root', '', 'forhomeserver');
if ($mysqli->connect_errno) {
    die('Erreur connexion MySQL : ' . $mysqli->connect_error);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifiant = $_POST['identifiant'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $photoPath = null;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','gif'];
        if (in_array($ext, $allowed)) {
            $photoPath = 'photos/' . uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['photo']['tmp_name'], __DIR__ . '/' . $photoPath);
        }
    }
    $stmt = $mysqli->prepare('SELECT id FROM users WHERE identifiant = ? OR email = ?');
    $stmt->bind_param('ss', $identifiant, $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $error = 'Identifiant ou email déjà utilisé.';
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt->close();
    $stmt = $mysqli->prepare('INSERT INTO users (identifiant, email, password, photo) VALUES (?, ?, ?, ?)');
    $stmt->bind_param('ssss', $identifiant, $email, $hashedPassword, $photoPath);
    $stmt->execute();
    $stmt->close();
    // Optionnel : démarrer la session et stocker l'identifiant
    // session_start();
    // $_SESSION['identifiant'] = $identifiant;
    header('Location: login.php');
    exit;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .container { max-width: 400px; margin: 60px auto; background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .icon { text-align: center; margin-bottom: 20px; }
        .icon svg { width: 60px; height: 60px; fill: #007bff; }
        .error { color: red; text-align: center; margin-bottom: 10px; }
        .btn { width: 100%; margin-top: 10px; }
    </style>
</head>
<body>
<div class="container">
    <div class="icon">
        <svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 8-4 8-4s8 0 8 4v2H4v-2z"/></svg>
    </div>
    <h2 style="text-align:center;">Inscription</h2>
    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="post" enctype="multipart/form-data">
    <label>Identifiant :</label>
    <input type="text" name="identifiant" required class="form-control"><br>
    <label>Email :</label>
    <input type="email" name="email" required class="form-control"><br>
    <label>Mot de passe :</label>
    <input type="password" name="password" required class="form-control"><br>
    <label>Photo de profil :</label>
    <input type="file" name="photo" accept="image/*" style="margin-bottom:10px;"><br>
    <button type="submit" class="btn">S'inscrire</button>
    </form>
    <div style="text-align:center;margin-top:15px;">
        <a href="login.php">Déjà inscrit ? Se connecter</a>
            <br>
            <a href="home.php" style="color:#7A6BA8;margin-top:8px;display:inline-block;">Retour à l'accueil</a>
    </div>
</div>
</body>
</html>
