<?php
require_once __DIR__ . '/../controllers/StatsController.php';
$controller = new StatsController();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques - MeowServer</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container" style="display:flex;flex-direction:column;align-items:center;justify-content:center;min-height:100vh;">
        <h2>Statistiques d'accès</h2>
        <div>Total connexions : <?= $controller->totalConnexions ?></div>
        <div>Nouveaux utilisateurs : <?= $controller->nouveaux ?></div>
        <div>IPs suspectes : <?= implode(', ', $controller->suspectes) ?></div>
        <h2 style="margin-top:2rem;">Speed Tests</h2>
        <canvas id="speedChart" width="600" height="300"></canvas>
    </div>
    <script>
    const speedData = <?= json_encode($controller->speedData) ?>;
    const ctx = document.getElementById('speedChart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: speedData.map(d => d.date),
            datasets: [{
                label: 'Vitesse (Mo/s)',
                data: speedData.map(d => d.vitesse),
                borderColor: '#B19CD9',
                backgroundColor: 'rgba(177,156,217,0.15)',
                fill: true
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: { display: true, title: { display: true, text: 'Date' } },
                y: { display: true, title: { display: true, text: 'Vitesse (Mo/s)' } }
            }
        }
    });
    </script>
</body>
</html>
