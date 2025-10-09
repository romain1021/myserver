<?php
require_once __DIR__ . '/../controllers/ProjectsController.php';
$controller = new ProjectsController();
if ($controller->error) {
    http_response_code(403);
    die($controller->error);
}
$model = new ProjectsModel();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projets BTS - MeowServer</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <h2>Projets BTS</h2>
        <ul style="list-style:none;padding:0;">
            <?php foreach ($controller->entries as $entry): ?>
                <?php $entryPath = $controller->relPath ? $controller->relPath . '/' . $entry : $entry; ?>
                <li style="margin-bottom:0.7rem;">
                    <?php if ($model->isDir($controller->targetPath . '/' . $entry)): ?>
                        <span><?= $model->getIcon($entry) ?></span>
                        <a href="?path=<?= urlencode($entryPath) ?>" style="color:#7A6BA8;font-weight:600;"> <?= htmlspecialchars($entry) ?> </a>
                    <?php else: ?>
                        <span><?= $model->getIcon($entry) ?></span>
                        <a href="<?= $controller->baseUrl . $entryPath ?>" target="_blank" style="color:#4A4A4A;"> <?= htmlspecialchars($entry) ?> </a>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>
