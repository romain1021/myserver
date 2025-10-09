<?php
require_once __DIR__ . '/../controllers/SpeedtestController.php';
$controller = new SpeedtestController();
$controller->handle();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Speed Test - MeowServer</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <h2>Speed Test</h2>
        <form id="speedtest-form" method="get">
            <label>Taille du fichier à télécharger (Mo) :</label>
            <input type="number" name="size" min="1" max="1024" value="10" required>
            <button type="submit" style="background:#B19CD9;color:#fff;">Lancer le test</button>
        </form>
        <div id="result" style="margin-top:2rem;"></div>
    </div>
    <script>
    document.getElementById('speedtest-form').onsubmit = function(e) {
        e.preventDefault();
        const size = parseInt(this.size.value);
        const start = Date.now();
        const link = document.createElement('a');
        link.href = '?action=download&size=' + size;
        link.download = 'speedtest_' + size + 'MB.bin';
        document.body.appendChild(link);
        link.click();
        setTimeout(() => document.body.removeChild(link), 1000);
        const end = Date.now();
        const speed = (size / ((end - start) / 1000)).toFixed(2);
        document.getElementById('result').innerHTML = 'Vitesse estimée : ' + speed + ' Mo/s';
        // Log le résultat
        fetch('?action=log', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'size=' + size + '&speed=' + speed
        });
    };
    </script>
</body>
</html>
