<?php
// Tableau de bord administrateur
session_start();
if (!isset($_SESSION['identifiant']) || !isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}
$mysqli = new mysqli('localhost', 'root', '', 'forhomeserver');
if ($mysqli->connect_errno) {
    die('Erreur connexion MySQL : ' . $mysqli->connect_error);
}
if (!$_SESSION['admin']) {
    header('Location: user_films.php');
    exit;
}
// Récupérer tous les utilisateurs
$users = [];
$result = $mysqli->query('SELECT id, identifiant, admin, photo FROM users');
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Tableau de bord</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { font-family: 'Poppins', Arial, sans-serif; background: #f4f4f4; }
        .admin-navbar { background: #7A6BA8; color: #fff; padding: 1rem 0.5rem; display: flex; justify-content: space-between; align-items: center; }
        .admin-navbar .menu { display: flex; gap: 1.5rem; }
        .admin-navbar .menu a { color: #fff; text-decoration: none; font-weight: 600; font-size: 1.1rem; padding: 0.3rem 0.8rem; border-radius: 18px; transition: background 0.2s; }
        .admin-navbar .menu a.active, .admin-navbar .menu a:hover { background: #B19CD9; }
        .admin-navbar .logo { font-weight: 700; font-size: 1.3rem; letter-spacing: 1px; }
        .container { max-width: 900px; margin: 2rem auto; background: #fff; padding: 2rem 1rem; border-radius: 18px; box-shadow: 0 2px 8px rgba(177,156,217,0.12); }
        .user-table { width: 100%; border-collapse: collapse; margin-top: 1.5rem; }
        .user-table th, .user-table td { padding: 0.7rem 0.4rem; text-align: left; border-bottom: 1px solid #eee; }
        .user-table th { background: #E5D9F2; color: #7A6BA8; font-weight: 600; }
        .user-table td img { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid #B19CD9; }
        .user-table td .admin-badge { background: #B19CD9; color: #fff; border-radius: 12px; padding: 0.2rem 0.7rem; font-size: 0.95rem; font-weight: 600; margin-left: 0.5rem; }
        @media (max-width: 600px) {
            .container { padding: 1rem 0.2rem; }
            .user-table th, .user-table td { font-size: 0.95rem; padding: 0.5rem 0.2rem; }
            .admin-navbar { flex-direction: column; align-items: flex-start; }
            .admin-navbar .menu { flex-direction: column; gap: 0.7rem; margin-top: 0.7rem; }
        }
    </style>
</head>
<body>
    <nav class="admin-navbar">
        <div class="logo">MeowServer Admin</div>
        <div class="menu">
            <a href="?tab=users" class="<?= (($_GET['tab'] ?? 'users') === 'users') ? 'active' : '' ?>">Gérer les utilisateurs</a>
            <a href="?tab=articles" class="<?= (($_GET['tab'] ?? '') === 'articles') ? 'active' : '' ?>">Ajouter un article</a>
            <a href="admin_films.php" style="color:#fff;text-decoration:none;font-weight:600;font-size:1.1rem;padding:0.3rem 0.8rem;border-radius:18px;transition:background 0.2s;">Admin Films</a>
        </div>
            <form method="post" action="logout.php" style="margin-left:auto;">
                <button type="submit" style="background:#B19CD9;color:#fff;border:none;padding:0.5rem 1.2rem;border-radius:12px;font-weight:600;cursor:pointer;">Se déconnecter</button>
            </form>
    </nav>
    <div class="container">
        <?php if (($_GET['tab'] ?? 'users') === 'users'): ?>
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
                            <?php if ($user['photo'] && file_exists(__DIR__ . '/' . $user['photo'])): ?>
                                <img src="<?= htmlspecialchars($user['photo']) ?>" alt="Photo">
                            <?php else: ?>
                                <img src="https://ui-avatars.com/api/?name=<?= urlencode($user['email']) ?>&background=B19CD9&color=fff&size=40" alt="Avatar">
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
        <?php elseif (($_GET['tab'] ?? '') === 'articles'): ?>
            <?php
            // Gestion des articles (CRUD)
            // Ajout
            $articleMsg = '';
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_article'])) {
                $titre = $_POST['titre'] ?? '';
                $contenu = $_POST['contenu'] ?? '';
                if ($titre && $contenu) {
                    $stmt = $mysqli->prepare('INSERT INTO articles (titre, contenu, auteur) VALUES (?, ?, ?)');
                    $stmt->bind_param('sss', $titre, $contenu, $_SESSION['email']);
                    $stmt->execute();
                    $stmt->close();
                    $articleMsg = 'Article ajouté !';
                } else {
                    $articleMsg = 'Titre et contenu requis.';
                }
            }
            // Suppression
            if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
                $id = intval($_GET['delete']);
                $stmt = $mysqli->prepare('DELETE FROM articles WHERE id = ?');
                $stmt->bind_param('i', $id);
                $stmt->execute();
                $stmt->close();
                $articleMsg = 'Article supprimé.';
            }
            // Modification
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_article'])) {
                $id = intval($_POST['id']);
                $titre = $_POST['titre'] ?? '';
                $contenu = $_POST['contenu'] ?? '';
                if ($titre && $contenu) {
                    $stmt = $mysqli->prepare('UPDATE articles SET titre = ?, contenu = ? WHERE id = ?');
                    $stmt->bind_param('ssi', $titre, $contenu, $id);
                    $stmt->execute();
                    $stmt->close();
                    $articleMsg = 'Article modifié.';
                } else {
                    $articleMsg = 'Titre et contenu requis.';
                }
            }
            // Liste des articles
            $articles = [];
            $result = $mysqli->query('SELECT * FROM articles ORDER BY date_publication DESC');
            while ($row = $result->fetch_assoc()) {
                    // Remplacer l'email par l'identifiant si possible
                    $auteur = $row['auteur'];
                    $stmt = $mysqli->prepare('SELECT identifiant FROM users WHERE email = ? OR identifiant = ?');
                    $stmt->bind_param('ss', $auteur, $auteur);
                    $stmt->execute();
                    $stmt->bind_result($identifiantAuteur);
                    if ($stmt->fetch() && $identifiantAuteur) {
                        $row['auteur'] = $identifiantAuteur;
                    }
                    $stmt->close();
                    $articles[] = $row;
            }
            ?>
            <h2 style="text-align:center;color:#7A6BA8;margin-bottom:1.5rem;">Gestion des articles</h2>
            <?php if ($articleMsg): ?><div style="color:#7A6BA8;text-align:center;margin-bottom:1rem;"><?= htmlspecialchars($articleMsg) ?></div><?php endif; ?>
            <form method="post" style="margin-bottom:2rem;">
                <input type="text" name="titre" placeholder="Titre de l'article" required style="width:100%;margin-bottom:0.7rem;padding:0.5rem;">
                <textarea name="contenu" placeholder="Contenu" required style="width:100%;height:100px;margin-bottom:0.7rem;padding:0.5rem;"></textarea>
                <button type="submit" name="add_article" style="background:#7A6BA8;color:#fff;border:none;padding:0.7rem 1.5rem;border-radius:12px;">Ajouter</button>
            </form>
            <table class="user-table">
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Auteur</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($articles as $article): ?>
                    <tr>
                        <td><?= htmlspecialchars($article['titre']) ?></td>
                        <td><?= htmlspecialchars($article['auteur']) ?></td>
                        <td><?= htmlspecialchars($article['date_publication']) ?></td>
                        <td>
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $article['id'] ?>">
                                <input type="text" name="titre" value="<?= htmlspecialchars($article['titre']) ?>" style="width:120px;">
                                <input type="text" name="contenu" value="<?= htmlspecialchars($article['contenu']) ?>" style="width:180px;">
                                <button type="submit" name="edit_article" style="background:#B19CD9;color:#fff;border:none;padding:0.3rem 0.8rem;border-radius:8px;">Modifier</button>
                            </form>
                            <a href="?tab=articles&delete=<?= $article['id'] ?>" onclick="return confirm('Supprimer cet article ?')" style="color:#b91c1c;margin-left:0.7rem;">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <h2 style="text-align:center;color:#7A6BA8;margin-bottom:1.5rem;">Sélectionnez un onglet dans le menu</h2>
        <?php endif; ?>
    </div>
</body>
</html>
