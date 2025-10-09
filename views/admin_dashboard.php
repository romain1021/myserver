<?php
session_start();
if (!isset($_SESSION['identifiant'])) {
    header('Location: ../login.php');
    exit;
}
$mysqli = new mysqli('localhost', 'root', '', 'forhomeserver');
if ($mysqli->connect_errno) {
    die('Erreur connexion MySQL : ' . $mysqli->connect_error);
}
require_once __DIR__ . '/../controllers/AdminDashboardController.php';
$controller = new AdminDashboardController($mysqli, $_SESSION['identifiant']);
if (!$controller->isAdmin) {
    header('Location: ../user_films.php');
    exit;
}
$users = $controller->users;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Tableau de bord</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <nav class="admin-navbar">
        <div class="logo">MeowServer Admin</div>
        <div class="menu">
            <a href="?tab=users" class="<?= (($_GET['tab'] ?? 'users') === 'users') ? 'active' : '' ?>">Gérer les utilisateurs</a>
            <a href="?tab=articles" class="<?= (($_GET['tab'] ?? '') === 'articles') ? 'active' : '' ?>">Ajouter un article</a>
            <a href="../admin_films.php" style="color:#fff;text-decoration:none;font-weight:600;font-size:1.1rem;padding:0.3rem 0.8rem;border-radius:18px;transition:background 0.2s;">Admin Films</a>
        </div>
        <form method="post" action="../logout.php" style="margin-left:auto;">
            <button type="submit" style="background:#B19CD9;color:#fff;border:none;padding:0.5rem 1.2rem;border-radius:12px;font-weight:600;cursor:pointer;">Se déconnecter</button>
        </form>
    </nav>
    <div class="container">
        <h2 style="text-align:center;color:#7A6BA8;margin-bottom:1.5rem;">Gestion des utilisateurs</h2>
        <table class="user-table">
            <thead>
                <tr>
                    <th>Photo</th>
                    <th>Identifiant</th>
                    <th>Email</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td>
                        <?php if ($user['photo'] && file_exists(__DIR__ . '/../' . $user['photo'])): ?>
                            <img src="<?= htmlspecialchars($user['photo']) ?>" alt="Photo">
                        <?php else: ?>
                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($user['identifiant']) ?>&background=B19CD9&color=fff&size=40" alt="Avatar">
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($user['identifiant']) ?></td>
                    <td><?= htmlspecialchars($user['identifiant']) ?></td>
                    <td>
                        <?php if ($user['admin']): ?>
                            <span class="admin-badge">Admin</span>
                        <?php else: ?>
                            Utilisateur
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
