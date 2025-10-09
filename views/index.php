
<?php
require_once __DIR__ . '/../controllers/IndexController.php';
$controller = new IndexController();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MeowServer - Tableau de bord</title>
    <!-- Google Fonts - Police élégante -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <!-- EN-TÊTE PRINCIPAL -->
        <header class="header">
            <h1 class="main-title">MeowServer</h1>
            <div class="subtitle">Tableau de bord du serveur personnel</div>
            <div class="welcome-icon">😺</div>
        </header>
        <div class="services-grid">
            <a href="login.php" class="service-card">
                <div class="service-logo">🔑</div>
                <div class="service-name">Connexion / Inscription</div>
                <div class="service-description">Accédez à votre espace personnel</div>
            </a>
            <a href="stats.php" class="service-card">
                <div class="service-logo">📊</div>
                <div class="service-name">Statistiques</div>
                <div class="service-description">Voir les accès et les speed tests</div>
            </a>
            <a href="speedtest.php" class="service-card">
                <div class="service-logo">⚡</div>
                <div class="service-name">Speed Test</div>
                <div class="service-description">Testez la vitesse de votre connexion</div>
            </a>
            <a href="projets.php" class="service-card">
                <div class="service-logo">📁</div>
                <div class="service-name">Projets BTS</div>
                <div class="service-description">Accédez aux dossiers BTS</div>
            </a>
            <a href="log.php" class="service-card">
                <div class="service-logo">📝</div>
                <div class="service-name">Logs en direct</div>
                <div class="service-description">Consultez les logs du serveur</div>
            </a>
        </div>
        <footer class="footer">
            <span>MeowServer &copy; 2025</span>
            <span class="footer-heart">❤️</span>
        </footer>
    </div>
    <!-- Bannière cookies -->
    <div id="cookie-banner" style="display:none;position:fixed;bottom:0;left:0;width:100%;background:rgba(177,156,217,0.97);color:#fff;padding:1.2rem 1rem;z-index:1000;text-align:center;font-family:'Poppins',sans-serif;box-shadow:0 -2px 16px rgba(177,156,217,0.15);">
        Ce site utilise des cookies pour améliorer votre expérience. <button id="accept-cookies" style="background:#7A6BA8;color:#fff;border:none;padding:0.5rem 1.2rem;border-radius:12px;font-weight:600;cursor:pointer;">Accepter</button>
    </div>
    <script>
    // Bannière cookies
    document.addEventListener('DOMContentLoaded', function() {
        if (!document.cookie.includes('meowserver_cookie_consent=accept')) {
            document.getElementById('cookie-banner').style.display = 'block';
        }
        document.getElementById('accept-cookies').onclick = function() {
            document.cookie = 'meowserver_cookie_consent=accept;path=/;max-age=' + (60*60*24*365);
            document.getElementById('cookie-banner').style.display = 'none';
            location.reload();
        };
    });
    </script>
</body>
</html>
