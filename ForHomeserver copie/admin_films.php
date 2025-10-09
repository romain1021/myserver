<?php
// Tableau de bord administrateur - gestion des films
session_start();
if (!isset($_SESSION['identifiant']) || !$_SESSION['admin']) {
    header('Location: login.php');
    exit;
}
$mysqli = new mysqli('localhost', 'root', '', 'forhomeserver');
if ($mysqli->connect_errno) {
    die('Erreur connexion MySQL : ' . $mysqli->connect_error);
}
// Suppression manuelle
if (isset($_POST['delete']) && isset($_POST['film_id'])) {
    $stmt = $mysqli->prepare('DELETE FROM films WHERE id = ?');
    $stmt->bind_param('i', $_POST['film_id']);
    $stmt->execute();
    $stmt->close();
}
// Validation (case cochée)
if (isset($_POST['valider']) && isset($_POST['film_id'])) {
    $stmt = $mysqli->prepare('UPDATE films SET valide = 1, date_ajout = NOW() WHERE id = ?');
    $stmt->bind_param('i', $_POST['film_id']);
    $stmt->execute();
    $stmt->close();
}
// Suppression automatique des films validés depuis > 1 jour
$mysqli->query("DELETE FROM films WHERE valide = 1 AND date_ajout < DATE_SUB(NOW(), INTERVAL 1 DAY)");
// Liste des films non validés
$result = $mysqli->query('SELECT * FROM films WHERE valide = 0 ORDER BY date_ajout DESC');
$films = [];
while ($row = $result->fetch_assoc()) {
    $films[] = $row;
}
// Films validés (pour affichage)
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
    <title>Admin - Films</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .film-table { width:100%; border-collapse:collapse; margin-bottom:2rem; }
        .film-table th, .film-table td { padding:0.7rem 0.4rem; border-bottom:1px solid #eee; }
        .film-table th { background:#E5D9F2; color:#7A6BA8; }
        .recent-block { background:#F8F6FF; border-radius:18px; padding:1.5rem; margin-bottom:2rem; }
    </style>
</head>
<body>
    <nav class="admin-navbar" style="background: #7A6BA8; color: #fff; padding: 1rem 0.5rem; display: flex; justify-content: space-between; align-items: center;">
        <div class="logo" style="font-weight:700;font-size:1.3rem;letter-spacing:1px;">MeowServer Admin</div>
        <div class="menu" style="display:flex;gap:1.5rem;">
            <a href="admin_dashboard.php?tab=users" style="color:#fff;text-decoration:none;font-weight:600;font-size:1.1rem;padding:0.3rem 0.8rem;border-radius:18px;transition:background 0.2s;">Gérer les utilisateurs</a>
            <a href="admin_dashboard.php?tab=articles" style="color:#fff;text-decoration:none;font-weight:600;font-size:1.1rem;padding:0.3rem 0.8rem;border-radius:18px;transition:background 0.2s;">Ajouter un article</a>
            <a href="admin_films.php" style="color:#fff;text-decoration:none;font-weight:600;font-size:1.1rem;padding:0.3rem 0.8rem;border-radius:18px;transition:background 0.2s;background:#B19CD9;">Gestion des films</a>
        </div>
        <form method="post" action="logout.php" style="margin-left:auto;">
            <button type="submit" style="background:#B19CD9;color:#fff;border:none;padding:0.5rem 1.2rem;border-radius:12px;font-weight:600;cursor:pointer;">Se déconnecter</button>
        </form>
    </nav>
    <div class="container">
        <h2>Gestion des films proposés</h2>
        <form method="post" style="margin-bottom:2rem;">
            <input type="text" name="nouveau_titre" placeholder="Ajouter un film..." required style="padding:0.5rem 1rem;">
            <button type="submit" name="ajouter" style="background:#B19CD9;color:#fff;border:none;padding:0.5rem 1.2rem;border-radius:12px;font-weight:600;cursor:pointer;">Ajouter</button>
        </form>
        <table class="film-table">
            <thead>
                <tr><th>Titre</th><th>Proposé par</th><th>Valider</th><th>Supprimer</th></tr>
            </thead>
            <tbody>
                <?php foreach ($films as $film): ?>
                <tr>
                    <td><?= htmlspecialchars($film['titre']) ?></td>
                    <td><?= htmlspecialchars($film['propose_par']) ?></td>
                    <td>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="film_id" value="<?= $film['id'] ?>">
                            <button type="submit" name="valider" style="background:#7A6BA8;color:#fff;border:none;padding:0.3rem 1rem;border-radius:8px;">Valider</button>
                        </form>
                    </td>
                    <td>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="film_id" value="<?= $film['id'] ?>">
                            <button type="submit" name="delete" style="background:#b91c1c;color:#fff;border:none;padding:0.3rem 1rem;border-radius:8px;">Supprimer</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
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
