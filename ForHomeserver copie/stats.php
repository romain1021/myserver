<?php
// ===================================
// PAGE DE STATISTIQUES - MeowServer
// ===================================
// Statistiques sur les accès et les speed tests

// --- Lecture des logs ---
$logIndex = file_exists('log_index.log') ? file('log_index.log', FILE_IGNORE_NEW_LINES) : [];
$logSpeed = file_exists('log_speedtest.log') ? file('log_speedtest.log', FILE_IGNORE_NEW_LINES) : [];

// --- Statistiques utilisateurs ---
$totalConnexions = count($logIndex);
$nouveaux = 0;
$ips = [];
$suspectes = [];
$ipTimes = [];
foreach ($logIndex as $line) {
    if (preg_match('/IP: ([^ ]+)/', $line, $m)) {
        $ip = $m[1];
        $ips[$ip] = ($ips[$ip] ?? 0) + 1;
        if (preg_match('/New: 1/', $line)) $nouveaux++;
        if (preg_match('/^([0-9\- :]+)/', $line, $d)) {
            $time = strtotime($d[1]);
            $ipTimes[$ip][] = $time;
        }
    }
}
// Détection requêtes suspectes (plus de 5 requêtes en 30s)
foreach ($ipTimes as $ip => $times) {
    sort($times);
    for ($i = 0; $i < count($times) - 5; $i++) {
        if ($times[$i+5] - $times[$i] <= 30) {
            $suspectes[] = [
                'ip' => $ip,
                'start' => date('Y-m-d H:i:s', $times[$i]),
                'end' => date('Y-m-d H:i:s', $times[$i+5]),
                'count' => 6
            ];
            break;
        }
    }
}

// --- Statistiques speedtest ---
$speedData = [];
foreach ($logSpeed as $line) {
    if (preg_match('/^([0-9\- :]+).*Taille: ([0-9]+)MB \| Vitesse: ([0-9.]+)/', $line, $m)) {
        $speedData[] = [
            'date' => $m[1],
            'size' => (int)$m[2],
            'speed' => (float)$m[3]
        ];
    }
}

?><!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques - MeowServer</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .stats-block {
            background: var(--card-background);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-light);
            padding: 2rem;
            margin-bottom: 2.5rem;
            width: 100%;
            max-width: 900px;
        }
        .stats-title {
            font-size: 2.2rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary-purple), var(--dark-purple));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        .stats-section-title {
            color: var(--dark-purple);
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        .stats-label {
            color: var(--text-secondary);
            font-size: 1.05rem;
        }
        .stats-value {
            font-weight: 600;
            color: var(--primary-purple);
        }
        .suspect-list {
            color: #b91c1c;
            background: #fef2f2;
            border-radius: 10px;
            padding: 1rem;
        }
        .suspect-list li {
            margin-bottom: 0.5rem;
        }
        .chart-controls {
            text-align: center;
            margin-bottom: 1rem;
        }
        .chart-controls select {
            font-size: 1rem;
            padding: 0.3rem 1rem;
            border-radius: 8px;
            border: 1px solid #ccc;
        }
        @media (max-width: 768px) {
            .stats-block { padding: 1rem; }
            .stats-title { font-size: 1.5rem; }
        }
    </style>
</head>
<body>
    <div class="container" style="display:flex;flex-direction:column;align-items:center;justify-content:center;min-height:100vh;">
        <div class="stats-block">
            <div class="stats-title">Statistiques MeowServer</div>
            <div class="stats-section-title">Utilisateurs</div>
            <div class="stats-label">Total de connexions : <span class="stats-value"><?= $totalConnexions ?></span></div>
            <div class="stats-label">Nouveaux utilisateurs : <span class="stats-value"><?= $nouveaux ?></span></div>
        </div>
        <div class="stats-block">
            <div class="stats-section-title">Requêtes suspectes</div>
            <?php if (count($suspectes) === 0): ?>
                <div class="stats-label">Aucune requête suspecte détectée.</div>
            <?php else: ?>
                <ul class="suspect-list">
                    <?php foreach ($suspectes as $s): ?>
                        <li><?= htmlspecialchars($s['ip']) ?> : <?= $s['count'] ?> requêtes entre <?= $s['start'] ?> et <?= $s['end'] ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
        <div class="stats-block">
            <div class="stats-section-title">Résultats Speed Test</div>
            <div class="chart-controls">
                <label for="period">Afficher : </label>
                <select id="period">
                    <option value="1h">1 heure</option>
                    <option value="24h" selected>24 heures</option>
                    <option value="7d">7 jours</option>
                    <option value="all">Tout</option>
                </select>
            </div>
            <canvas id="speedChart" height="80"></canvas>
        </div>
    </div>
    <script>
    const rawData = <?php echo json_encode($speedData); ?>;
    function filterData(period) {
        const now = new Date();
        let minDate;
        if (period === '1h') minDate = new Date(now.getTime() - 3600*1000);
        else if (period === '24h') minDate = new Date(now.getTime() - 24*3600*1000);
        else if (period === '7d') minDate = new Date(now.getTime() - 7*24*3600*1000);
        else minDate = new Date(0);
        return rawData.filter(d => new Date(d.date.replace(' ', 'T')) >= minDate);
    }
    function renderChart(period) {
        const data = filterData(period);
        const ctx = document.getElementById('speedChart').getContext('2d');
        if (window.speedChartObj) window.speedChartObj.destroy();
        window.speedChartObj = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.map(d => d.date),
                datasets: [{
                    label: 'Vitesse (Mo/s)',
                    data: data.map(d => d.speed),
                    borderColor: '#7A6BA8',
                    backgroundColor: 'rgba(177,156,217,0.2)',
                    tension: 0.2,
                    pointRadius: 2
                }]
            },
            options: {
                scales: {
                    x: { display: true, title: { display: true, text: 'Date' }, ticks: { maxTicksLimit: 10 } },
                    y: { display: true, title: { display: true, text: 'Mo/s' } }
                },
                plugins: { legend: { display: false } }
            }
        });
    }
    document.getElementById('period').addEventListener('change', function() {
        renderChart(this.value);
    });
    renderChart(document.getElementById('period').value);
    </script>
</body>
</html>
