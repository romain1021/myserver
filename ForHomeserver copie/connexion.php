<?php
session_start();
$error = '';
$mysqli = new mysqli('localhost', 'root', '', 'forhomeserver');
if ($mysqli->connect_errno) {
    die('Erreur connexion MySQL : ' . $mysqli->connect_error);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $isLogin = isset($_POST['login']);
    $isRegister = isset($_POST['register']);
    $isAdmin = isset($_POST['admin']) ? 1 : 0;
    if ($isRegister) {
        $stmt = $mysqli->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $error = 'Email déjà utilisé.';
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $photoPath = null;
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg','jpeg','png','gif'];
                if (in_array($ext, $allowed)) {
                    $photoName = uniqid('user_') . '.' . $ext;
                    $photoPath = 'photos/' . $photoName;
                    move_uploaded_file($_FILES['photo']['tmp_name'], __DIR__ . '/' . $photoPath);
                }
            }
            $stmt = $mysqli->prepare('INSERT INTO users (email, password, admin, photo) VALUES (?, ?, ?, ?)');
            $stmt->bind_param('ssis', $email, $hashedPassword, $isAdmin, $photoPath);
            $stmt->execute();
            $_SESSION['email'] = $email;
            $_SESSION['admin'] = $isAdmin;
            $_SESSION['photo'] = $photoPath;
            header('Location: homePage.php');
            exit;
        }
        $stmt->close();
    } elseif ($isLogin) {
        $stmt = $mysqli->prepare('SELECT password, admin, photo FROM users WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($dbPassword, $dbAdmin, $dbPhoto);
            $stmt->fetch();
            if (password_verify($password, $dbPassword)) {
                $_SESSION['email'] = $email;
                $_SESSION['admin'] = $dbAdmin;
                $_SESSION['photo'] = $dbPhoto;
                if ($dbAdmin) {
                    header('Location: admin_dashboard.php');
                } else {
                    header('Location: homePage.php');
                }
                exit;
            }
        }
        $error = 'Email ou mot de passe incorrect.';
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion / Inscription</title>
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
        <!-- Icône utilisateur SVG -->
        <svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 8-4 8-4s8 0 8 4v2H4v-2z"/></svg>
    </div>
    <h2 style="text-align:center;">Connexion / Inscription</h2>
    <div style="display:flex;flex-direction:column;gap:1.5rem;align-items:center;">
        <a href="login.php" class="btn" style="background:#007bff;color:#fff;border:none;padding:0.7rem 1.5rem;border-radius:12px;text-decoration:none;width:100%;text-align:center;">Se connecter</a>
        <a href="register.php" class="btn" style="background:#B19CD9;color:#fff;border:none;padding:0.7rem 1.5rem;border-radius:12px;text-decoration:none;width:100%;text-align:center;">Créer un compte</a>
        <a href="home.php" class="btn" style="background:#E5D9F2;color:#7A6BA8;border:none;padding:0.7rem 1.5rem;border-radius:12px;text-decoration:none;width:100%;text-align:center;margin-top:0.5rem;">Retour à l'accueil</a>
    </div>
</div>
</body>
</html>
