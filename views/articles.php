<?php
session_start();
if (!isset($_SESSION['identifiant'])) {
    header('Location: ../views/login.php');
    exit;
}
$mysqli = new mysqli('localhost', 'root', '', 'forhomeserver');
if ($mysqli->connect_errno) {
    die('Erreur connexion MySQL : ' . $mysqli->connect_error);
}
require_once __DIR__ . '/../controllers/ArticleController.php';
$controller = new ArticleController($mysqli, $_SESSION['identifiant']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Articles</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        .article-block { background:#F8F6FF; border-radius:18px; padding:1.5rem; margin-bottom:2rem; }
        .article-title { color:#7A6BA8; font-weight:600; font-size:1.2rem; }
        .article-meta { color:#6B6B6B; font-size:0.95rem; margin-bottom:0.5rem; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Ajouter un article</h2>
        <form method="post" style="margin-bottom:2rem;">
            <input type="text" name="titre" placeholder="Titre de l'article..." required style="padding:0.5rem 1rem;width:100%;margin-bottom:0.7rem;">
            <textarea name="contenu" placeholder="Contenu..." required style="padding:0.5rem 1rem;width:100%;height:120px;margin-bottom:0.7rem;"></textarea>
            <button type="submit" name="ajouter" style="background:#B19CD9;color:#fff;">Ajouter</button>
        </form>
        <h3>Articles publiés</h3>
        <?php foreach ($controller->articles as $article): ?>
            <div class="article-block">
                <div class="article-title"><?= htmlspecialchars($article['titre']) ?></div>
                <div class="article-meta">Par <?= htmlspecialchars($article['auteur']) ?> le <?= htmlspecialchars($article['date_publication']) ?></div>
                <div><?= nl2br(htmlspecialchars($article['contenu'])) ?></div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
