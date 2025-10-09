<?php
// ===================================
// PAGE LOGS EN DIRECT - MeowServer
// ===================================
$logFiles = [
    'Accès (index)' => 'log_index.log',
    'Speed Test' => 'log_speedtest.log'
];
$selected = $_GET['log'] ?? 'log_index.log';
$reset = $_POST['reset'] ?? null;
$period = $_POST['period'] ?? null;
$periods = [
    '1h' => 3600,
    '2h' => 7200,
    '1j' => 86400,
    '3j' => 3*86400,
    '7j' => 7*86400,
    '30j' => 30*86400,
    '3mois' => 90*86400
];

// Réinitialisation des logs sur la période choisie
if ($reset && isset($periods[$period]) && file_exists($selected)) {
    $lines = file($selected, FILE_IGNORE_NEW_LINES);
    $now = time();
    $keep = [];
    foreach ($lines as $line) {
        if (preg_match('/^([0-9\- :]+)/', $line, $m)) {
            $t = strtotime($m[1]);
            if ($now - $t > $periods[$period]) $keep[] = $line;
        } else {
            $keep[] = $line;
        }
    }
    file_put_contents($selected, implode("\n", $keep));
    header('Location: log.php?log=' . urlencode($selected));
    exit;
}

// Lecture des logs (en direct)
$logContent = file_exists($selected) ? file($selected, FILE_IGNORE_NEW_LINES) : [];
$logContent = array_reverse($logContent); // Les plus récents en haut
?><!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logs en direct - MeowServer</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .log-block {
            background: var(--card-background);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-light);
            padding: 2rem;
            margin: 2rem auto;
            width: 100%;
            max-width: 900px;
        }
        .log-title {
            font-size: 2.2rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary-purple), var(--dark-purple));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        .log-select { margin-bottom: 1.5rem; text-align:center; }
        .log-list {
            background: #18181b;
            color: #f3f3f3;
            font-family: monospace;
            font-size: 0.98rem;
            border-radius: 10px;
            padding: 1rem;
            max-height: 400px;
            overflow-y: auto;
        }
        .log-list li { border-bottom: 1px solid #3331; padding: 0.2rem 0; }
        .reset-form { margin: 1.5rem 0; text-align: center; }
        .reset-form select, .reset-form button { font-size: 1rem; padding: 0.4rem 1rem; border-radius: 8px; border: 1px solid #ccc; margin-right: 0.5rem; }
        .reset-form button { background: #b91c1c; color: #fff; border: none; cursor: pointer; }
        .reset-form button:hover { background: #7A6BA8; }
        @media (max-width: 768px) {
            .log-block { padding: 1rem; }
            .log-title { font-size: 1.5rem; }
        }
    </style>
    <meta http-equiv="refresh" content="5">
</head>
<body>
    <div class="container" style="display:flex;flex-direction:column;align-items:center;justify-content:center;min-height:100vh;">
        <div class="log-block">
            <div class="log-title">Logs en direct</div>
            <form method="get" class="log-select">
                <label for="log">Fichier : </label>
                <select name="log" id="log" onchange="this.form.submit()">
                    <?php foreach ($logFiles as $label => $file): ?>
                        <option value="<?= htmlspecialchars($file) ?>" <?= $selected === $file ? 'selected' : '' ?>><?= htmlspecialchars($label) ?></option>
                    <?php endforeach; ?>
                </select>
            </form>
            <form method="post" class="reset-form" onsubmit="return confirm('Confirmer la suppression des logs sur la période choisie ?');">
                <input type="hidden" name="reset" value="1">
                <label for="period">Réinitialiser (supprimer) les logs sur :</label>
                <select name="period" id="period">
                    <?php foreach ($periods as $label => $s): ?>
                        <option value="<?= htmlspecialchars($label) ?>"><?= htmlspecialchars($label) ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit">Réinitialiser</button>
            </form>
            <ul class="log-list">
                <?php foreach ($logContent as $line): ?>
                    <li><?= htmlspecialchars($line) ?></li>
                <?php endforeach; ?>
            </ul>
            <p style="text-align:center;color:#6B6B6B;font-size:0.95rem;margin-top:1.5rem;">Actualisation automatique toutes les 5 secondes</p>
        </div>
    </div>
</body>
</html>
