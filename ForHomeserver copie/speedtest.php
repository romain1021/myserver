<?php
// ===================================
// PAGE SPEED TEST - MEOWSERVER
// ===================================

// Génère le fichier temporaire à télécharger pour tester la vitesse
$sizeMB = isset($_GET['size']) ? max(1, min(1024, intval($_GET['size']))) : 10; // Taille du fichier en Mo (1 à 1024)
$action = $_GET['action'] ?? '';

// Enregistrement du résultat du test (AJAX POST)
if ($action === 'log' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $logFile = __DIR__ . '/log_speedtest.log';
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $ua = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
    $date = date('Y-m-d H:i:s');
    $size = isset($_POST['size']) ? intval($_POST['size']) : 0;
    $speed = isset($_POST['speed']) ? floatval($_POST['speed']) : 0;
    $logLine = $date . ' | IP: ' . $ip . ' | UA: ' . $ua . ' | Taille: ' . $size . 'MB | Vitesse: ' . $speed . " Mo/s\n";
    file_put_contents($logFile, $logLine, FILE_APPEND | LOCK_EX);
    http_response_code(204);
    exit;
}

if ($action === 'download') {
    $filename = 'speedtest_' . $sizeMB . 'MB_' . time() . '.bin';
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Content-Length: ' . ($sizeMB * 1024 * 1024));
    // Génère le fichier à la volée (rempli de zéros)
    $chunk = str_repeat("0", 1024 * 1024); // 1 Mo
    for ($i = 0; $i < $sizeMB; $i++) {
        echo $chunk;
        flush();
    }
    exit;
}

// Réception de fichier (upload)
if ($action === 'upload' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $logFile = __DIR__ . '/log_speedtest.log';
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $ua = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
    $date = date('Y-m-d H:i:s');
    $size = isset($_FILES['uploadfile']) ? $_FILES['uploadfile']['size'] : 0;
    $speed = isset($_POST['uploadtime']) ? floatval($_POST['uploadtime']) : 0;
    $logLine = $date . ' | IP: ' . $ip . ' | UA: ' . $ua . ' | UPLOAD: ' . $size . ' octets | Vitesse: ' . $speed . " Mo/s\n";
    file_put_contents($logFile, $logLine, FILE_APPEND | LOCK_EX);
    echo '<div style="color:var(--primary-purple);font-family:Poppins,sans-serif;text-align:center;margin-top:2rem;">Fichier reçu (' . round($size/1024/1024,2) . ' Mo) à ' . $speed . ' Mo/s</div>';
    exit;
}
?><!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Speed Test - MeowServer</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .speedtest-block {
            background: var(--card-background);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-light);
            padding: 2rem;
            margin: 2rem auto;
            width: 100%;
            max-width: 600px;
            text-align: center;
        }
        .speedtest-title {
            font-size: 2.2rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary-purple), var(--dark-purple));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        .speedtest-result {
            margin-top: 2rem;
            font-size: 1.2rem;
            color: var(--dark-purple);
        }
        @media (max-width: 768px) {
            .speedtest-block { padding: 1rem; }
            .speedtest-title { font-size: 1.5rem; }
        }
    </style>
</head>
<body>
    <div class="container" style="display:flex;flex-direction:column;align-items:center;justify-content:center;min-height:100vh;">
        <div class="speedtest-block">
            <div class="speedtest-title">Speed Test</div>
            <p>Testez la vitesse de téléchargement <b>et d'envoi</b> entre le serveur et votre appareil.<br>
            Un fichier temporaire sera généré et supprimé automatiquement.</p>
            <form id="speedtest-form">
                <label for="size">Taille du fichier :</label>
                <select id="size" name="size" class="speedtest-select">
                    <option value="1">1 Mo</option>
                    <option value="5">5 Mo</option>
                    <option value="10" selected>10 Mo</option>
                    <option value="20">20 Mo</option>
                    <option value="50">50 Mo</option>
                    <option value="100">100 Mo</option>
                    <option value="300">300 Mo</option>
                    <option value="400">400 Mo</option>
                    <option value="500">500 Mo</option>
                    <option value="1024">1 Go</option>
                </select>
                <button type="submit" class="speedtest-btn">Lancer le test</button>
            </form>
            <div class="speedtest-result" id="result"></div>
            <hr style="margin:2.5rem 0 1.5rem 0;border:0;border-top:1px solid var(--primary-purple);opacity:0.2;">
            <form id="upload-form" enctype="multipart/form-data" method="post" action="?action=upload" style="text-align:center;">
                <label for="uploadfile">Test d'envoi (upload) :</label>
                <input type="file" id="uploadfile" name="uploadfile" required style="margin:0 1rem;">
                <input type="hidden" name="uploadtime" id="uploadtime" value="0">
                <button type="submit" class="speedtest-btn">Envoyer</button>
            </form>
            <div class="speedtest-result" id="upload-result"></div>
        </div>
    </div>
    <script>
    // Test de téléchargement
    const form = document.getElementById('speedtest-form');
    const result = document.getElementById('result');
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const size = document.getElementById('size').value;
        result.textContent = 'Téléchargement en cours...';
        const start = Date.now();
        const link = document.createElement('a');
        link.href = '?action=download&size=' + size;
        link.download = '';
        document.body.appendChild(link);
        link.click();
        // Mesure du temps de téléchargement
        let timer = setInterval(() => {
            if (document.hasFocus()) {
                clearInterval(timer);
                const duration = (Date.now() - start) / 1000;
                const mb = parseInt(size, 10);
                const speed = (mb / duration).toFixed(2);
                result.textContent = `Vitesse estimée : ${speed} Mo/s (${(speed * 8).toFixed(2)} Mbit/s)`;
                // Envoi du résultat au serveur pour log
                fetch('?action=log', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `size=${encodeURIComponent(mb)}&speed=${encodeURIComponent(speed)}`
                });
            }
        }, 500);
    });

    // Test d'upload
    const uploadForm = document.getElementById('upload-form');
    const uploadResult = document.getElementById('upload-result');
    let uploadStart = 0;
    uploadForm.addEventListener('submit', function(e) {
        const fileInput = document.getElementById('uploadfile');
        if (!fileInput.files.length) return;
        uploadStart = Date.now();
        // On laisse le submit se faire, mais on va mesurer le temps
        setTimeout(() => {
            // On attend la fin de l'upload (pas parfait mais simple)
            const interval = setInterval(() => {
                if (!fileInput.files.length) {
                    clearInterval(interval);
                }
            }, 100);
        }, 10);
        // On mesure le temps d'upload côté client
        uploadForm.onsubmit = function() {
            const end = Date.now();
            const duration = (end - uploadStart) / 1000;
            const size = fileInput.files[0].size / 1024 / 1024;
            const speed = (size / duration).toFixed(2);
            document.getElementById('uploadtime').value = speed;
        };
    });
    </script>
</body>
</html>
