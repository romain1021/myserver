<?php
/*
 * ===================================
 * MEOWSERVER - TABLEAU DE BORD
 * ===================================
 * Page d'accueil pour home server
 * Style : Violet pastel moderne
 * Auteur : Votre configuration
 */

$logFile = __DIR__ . '/log_index.log';
$isNewUser = false;
$cookieConsent = $_COOKIE['meowserver_cookie_consent'] ?? null;
if ($cookieConsent === 'accept') {
    if (!isset($_COOKIE['meowserver_visited'])) {
        $isNewUser = true;
        setcookie('meowserver_visited', '1', time() + 60*60*24*365, "/");
    }
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $ua = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
    $date = date('Y-m-d H:i:s');
    $logLine = $date . ' | IP: ' . $ip . ' | UA: ' . $ua . ' | New: ' . ($isNewUser ? '1' : '0') . "\n";
    file_put_contents($logFile, $logLine, FILE_APPEND | LOCK_EX);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MeowServer - Tableau de bord</title>
    
    <!-- Google Fonts - Police élégante -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <!-- EN-TÊTE PRINCIPAL -->
        <header class="header">
            <span class="welcome-icon"></span>
            <h1 class="main-title">Bienvenue sur MeowServer</h1>
            <p class="subtitle">Accédez rapidement à vos services préférés</p>
        </header>
        <div style="text-align:right;margin-bottom:1.5rem;">
            <a href="connexion.php" class="btn" style="background:#B19CD9;color:#fff;padding:0.7rem 1.5rem;border-radius:25px;text-decoration:none;font-weight:600;box-shadow:0 2px 8px rgba(177,156,217,0.15);transition:background 0.2s;">Connexion / Inscription</a>
        </div>

        <!-- GRILLE DES SERVICES -->
        <main class="services-grid">

            <!-- CONVERT -->
            <a href="https://convert.meowserverlom.app" target="_blank" rel="noopener noreferrer" class="service-card">
                <svg class="service-logo" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <!-- Logo Convert simplifié -->
                    <circle cx="50" cy="50" r="45" fill="#8B5CF6" opacity="0.1"/>
                    <rect x="30" y="40" width="40" height="20" rx="6" fill="#8B5CF6"/>
                    <polygon points="50,30 60,50 40,50" fill="#C4B5FD"/>
                </svg>
                <h3 class="service-name">Convert</h3>
                <p class="service-description">Conversion de fichiers</p>
            </a>

            <!-- MUSIC -->
            <a href="https://music.meowserverlom.app" target="_blank" rel="noopener noreferrer" class="service-card">
                <svg class="service-logo" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <!-- Logo Music simplifié -->
                    <circle cx="50" cy="50" r="45" fill="#F472B6" opacity="0.1"/>
                    <rect x="40" y="30" width="20" height="40" rx="5" fill="#F472B6"/>
                    <circle cx="50" cy="70" r="8" fill="#F472B6"/>
                </svg>
                <h3 class="service-name">Music</h3>
                <p class="service-description">Streaming de musique</p>
            </a>

            <!-- PROJET -->
            <a href="#" target="_blank" rel="noopener noreferrer" class="service-card">
                <svg class="service-logo" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <!-- Logo Projet simplifié -->
                    <circle cx="50" cy="50" r="45" fill="#34D399" opacity="0.1"/>
                    <rect x="30" y="35" width="40" height="30" rx="6" fill="#34D399"/>
                    <rect x="40" y="45" width="20" height="10" rx="2" fill="white"/>
                </svg>
                <h3 class="service-name">Projet</h3>
                <p class="service-description">Contient mes projets de BTS</p>
            </a>

            <!-- JELLYFIN -->
            <a href="https://jelly.meowserverlom.app" target="_blank" rel="noopener noreferrer" class="service-card">
                <svg class="service-logo" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <!-- Logo Jellyfin simplifié -->
                    <circle cx="50" cy="50" r="45" fill="#00A4DC" opacity="0.1"/>
                    <circle cx="35" cy="40" r="12" fill="#00A4DC"/>
                    <circle cx="65" cy="40" r="12" fill="#00A4DC"/>
                    <path d="M35 55 Q50 70 65 55" stroke="#00A4DC" stroke-width="8" fill="none" stroke-linecap="round"/>
                    <circle cx="50" cy="25" r="6" fill="#00A4DC"/>
                </svg>
                <h3 class="service-name">Jellyfin</h3>
                <p class="service-description">Serveur multimédia personnel</p>
            </a>

            <!-- NEXTCLOUD -->
            <a href="https://drive.meowserverlom.app" target="_blank" rel="noopener noreferrer" class="service-card">
                <svg class="service-logo" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <!-- Logo Nextcloud simplifié -->
                    <circle cx="50" cy="50" r="45" fill="#0082C9" opacity="0.1"/>
                    <path d="M30 45 Q50 25 70 45 Q50 65 30 45" fill="#0082C9"/>
                    <circle cx="40" cy="35" r="8" fill="white"/>
                    <circle cx="60" cy="35" r="8" fill="white"/>
                    <circle cx="50" cy="55" r="12" fill="white"/>
                </svg>
                <h3 class="service-name">Nextcloud</h3>
                <p class="service-description">Cloud personnel et partage</p>
            </a>

            <!-- IMMICH -->
            <a href="https://photos.meowserverlom.app" target="_blank" rel="noopener noreferrer" class="service-card">
                <svg class="service-logo" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <!-- Logo Immich simplifié -->
                    <circle cx="50" cy="50" r="45" fill="#4285F4" opacity="0.1"/>
                    <rect x="20" y="30" width="60" height="40" rx="8" fill="#4285F4"/>
                    <circle cx="50" cy="50" r="12" fill="white"/>
                    <circle cx="35" cy="38" r="4" fill="white"/>
                    <polygon points="25,62 40,50 55,58 75,45 75,62" fill="white" opacity="0.7"/>
                </svg>
                <h3 class="service-name">Immich</h3>
                <p class="service-description">Gestionnaire de photos</p>
            </a>

            <a href="speedtest.php" target="_blank" rel="noopener noreferrer" class="service-card">
                <svg class="service-logo" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <!-- Logo Speed Test simplifié -->
                    <circle cx="50" cy="50" r="45" fill="#F59E42" opacity="0.1"/>
                    <rect x="30" y="40" width="40" height="20" rx="6" fill="#F59E42"/>
                    <polygon points="50,30 60,50 40,50" fill="#FCD34D"/>
                </svg>
                <h3 class="service-name">Speed test</h3>
                <p class="service-description">Tester la vitesse de transfert entre le serveur et votre appareil</p>
            </a>

        </main>
        <!-- ARTICLES PUBLICS -->
        <section style="max-width:900px;margin:2rem auto 0 auto;background:var(--card-background);border-radius:var(--border-radius);box-shadow:var(--shadow-light);padding:2rem;">
            <h2 style="color:#7A6BA8;text-align:center;margin-bottom:1.5rem;">Articles publics</h2>
            <?php
            $mysqli = @new mysqli('localhost', 'root', '', 'forhomeserver');
            if (!$mysqli->connect_errno) {
                $result = $mysqli->query('SELECT titre, contenu, date_publication, auteur FROM articles ORDER BY date_publication DESC');
                    while ($row = $result->fetch_assoc()) {
                        $auteur = $row['auteur'];
                        $stmt = $mysqli->prepare('SELECT identifiant FROM users WHERE email = ? OR identifiant = ?');
                        $stmt->bind_param('ss', $auteur, $auteur);
                        $stmt->execute();
                        $stmt->bind_result($identifiantAuteur);
                        if ($stmt->fetch() && $identifiantAuteur) {
                            $auteurAffiche = $identifiantAuteur;
                        } else {
                            $auteurAffiche = $auteur;
                        }
                        $stmt->close();
                        echo '<div style="margin-bottom:2rem;padding-bottom:1rem;border-bottom:1px solid #eee;">';
                        echo '<div style="font-size:1.3rem;font-weight:600;color:#7A6BA8;">' . htmlspecialchars($row['titre']) . '</div>';
                        echo '<div style="color:#6B6B6B;font-size:0.95rem;margin-bottom:0.5rem;">Publié le ' . htmlspecialchars($row['date_publication']) . ' par <span style="color:#B19CD9;font-weight:600;">' . htmlspecialchars($auteurAffiche) . '</span></div>';
                        echo '<div>' . nl2br(htmlspecialchars($row['contenu'])) . '</div>';
                        echo '</div>';
                }
            }
            ?>
        </section>

        <!-- FOOTER -->
        <footer class="footer">
            <p>Fait avec <span class="footer-heart">♥</span> pour MeowServer • <?php echo date('Y'); ?></p>
        </footer>
    </div>

    <script>
        // ===================================
        // ANIMATIONS ET INTERACTIVITÉ
        // ===================================
        
        // Animation d'apparition progressive des cartes
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.service-card');
            
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';
                card.style.animation = `fadeInUp 0.6s ease-out ${index * 0.1 + 0.3}s forwards`;
            });
            
            // Effet de particules au clic (optionnel)
            cards.forEach(card => {
                card.addEventListener('click', function(e) {
                    // Petit effet visuel au clic
                    this.style.transform = 'translateY(-8px) scale(0.98)';
                    setTimeout(() => {
                        this.style.transform = 'translateY(-8px) scale(1.02)';
                    }, 100);
                });
            });
        });
        
        // Gestion du focus pour l'accessibilité
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Tab') {
                document.body.classList.add('using-keyboard');
            }
        });
        
        document.addEventListener('mousedown', function() {
            document.body.classList.remove('using-keyboard');
        });
        
        // Console easter egg
        console.log(`
        🐾 MeowServer Dashboard
        =====================
        Bienvenue dans votre serveur personnel !
        
        Services disponibles :
        • CasaOS : Gestion système
        • Jellyfin : Streaming média
        • Nextcloud : Cloud personnel  
        • Immich : Photos
        
        Miaou ! 🐱
        `);
    </script>
    <!-- Bannière cookies -->
    <div id="cookie-banner" style="display:none;position:fixed;bottom:0;left:0;width:100%;background:rgba(177,156,217,0.97);color:#fff;padding:1.2rem 1rem;z-index:1000;text-align:center;font-family:'Poppins',sans-serif;box-shadow:0 -2px 16px rgba(177,156,217,0.15);">
        Ce site utilise des cookies pour compter les nouveaux visiteurs. Ils ne seront pas utilisés à des fins commerciales. <button id="cookie-accept" style="margin-left:1rem;background:#7A6BA8;color:#fff;border:none;border-radius:8px;padding:0.5rem 1.2rem;font-size:1rem;cursor:pointer;">Accepter</button> <button id="cookie-refuse" style="margin-left:0.5rem;background:#fff;color:#7A6BA8;border:none;border-radius:8px;padding:0.5rem 1.2rem;font-size:1rem;cursor:pointer;">Refuser</button>
    </div>
    <script>
    // Bannière cookies
    function setCookie(name, value, days) {
        let expires = "";
        if (days) {
            const date = new Date();
            date.setTime(date.getTime() + (days*24*60*60*1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (value || "")  + expires + "; path=/";
    }
    function getCookie(name) {
        let match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
        return match ? match[2] : null;
    }
    window.addEventListener('DOMContentLoaded', function() {
        if (!getCookie('meowserver_cookie_consent')) {
            document.getElementById('cookie-banner').style.display = 'block';
        }
        document.getElementById('cookie-accept').onclick = function() {
            setCookie('meowserver_cookie_consent', 'accept', 365);
            document.getElementById('cookie-banner').style.display = 'none';
            location.reload();
        };
        document.getElementById('cookie-refuse').onclick = function() {
            setCookie('meowserver_cookie_consent', 'refuse', 365);
            document.getElementById('cookie-banner').style.display = 'none';
        };
    });
    </script>
</body>
</html>