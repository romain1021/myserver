<?php
require_once __DIR__ . '/../controllers/LogController.php';
$controller = new LogController();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logs en direct - MeowServer</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
    <meta http-equiv="refresh" content="5">
</head>
<body>
    <div class="container" style="display:flex;flex-direction:column;align-items:center;justify-content:center;min-height:100vh;">
        <h2>Logs en direct</h2>
        <form method="post" style="margin-bottom:1rem;">
            <label>Réinitialiser sur la période :</label>
            <select name="period">
                <option value="1h">1 heure</option>
                <option value="2h">2 heures</option>
                <option value="1j">1 jour</option>
                <option value="3j">3 jours</option>
                <option value="7j">7 jours</option>
                <option value="30j">30 jours</option>
                <option value="3mois">3 mois</option>
            </select>
            <button type="submit" name="reset" style="background:#B19CD9;color:#fff;">Réinitialiser</button>
        </form>
        <div style="max-height:400px;overflow:auto;width:100%;background:#F8F6FF;border-radius:18px;padding:1rem;">
            <?php foreach ($controller->logContent as $line): ?>
                <div><?= htmlspecialchars($line) ?></div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
