<?php
// ===================================
// PROJETS BTS - NAVIGATEUR DE DOSSIERS
// ===================================
// Affiche le contenu du dossier BTS en lecture seule

$baseUrl = 'https://home.meowserverlom.app/BTS/';
$localPath = '/var/www/html/BTS'; // À adapter selon l'emplacement réel du dossier sur le serveur

// Sécurisation du chemin demandé

$relPath = isset($_GET['path']) ? $_GET['path'] : '';
$relPath = ltrim($relPath, '/');
$targetPath = $localPath . ($relPath ? '/' . $relPath : '');
if (!is_dir($targetPath)) {
    http_response_code(403);
    die('Accès refusé.');
}
$fullPath = realpath($targetPath);
// Vérification que le chemin reste dans le dossier BTS
if ($fullPath === false || strpos($fullPath, realpath($localPath)) !== 0) {
    http_response_code(403);
    die('Accès refusé.');
}

function isDir($path) {
    return is_dir($path);
}

function isFile($path) {
    return is_file($path);
}

function getEntries($dir) {
    $entries = array_diff(scandir($dir), ['.', '..']);
    natcasesort($entries);
    return $entries;
}

function getIcon($file) {
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    $icons = [
        'pdf' => '📄', 'doc' => '📄', 'docx' => '📄', 'txt' => '📄',
        'jpg' => '🖼️', 'jpeg' => '🖼️', 'png' => '🖼️', 'gif' => '🖼️',
        'zip' => '🗜️', 'rar' => '🗜️', '7z' => '🗜️',
        'php' => '💻', 'html' => '🌐', 'js' => '📜', 'css' => '🎨',
        'mp3' => '🎵', 'wav' => '🎵', 'mp4' => '🎬', 'mkv' => '🎬',
    ];
    return $icons[$ext] ?? '📁';
}

?><!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projets BTS - MeowServer</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .bts-block {
            background: var(--card-background);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-light);
            padding: 2rem;
            margin: 2rem auto;
            width: 100%;
            max-width: 900px;
        }
        .bts-title {
            font-size: 2.2rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary-purple), var(--dark-purple));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        .breadcrumb {
            margin-bottom: 1.5rem;
            font-size: 1rem;
        }
        .breadcrumb a {
            color: var(--accent-purple);
            text-decoration: none;
        }
        .breadcrumb span {
            color: var(--text-primary);
        }
        .readonly {
            color: var(--primary-purple);
            font-size: 0.95rem;
            margin-top: 2rem;
            text-align: center;
        }
        @media (max-width: 768px) {
            .bts-block { padding: 1rem; }
            .bts-title { font-size: 1.5rem; }
        }
    </style>
</head>
<body>
    <div class="container" style="display:flex;flex-direction:column;align-items:center;justify-content:center;min-height:100vh;">
        <div class="bts-block">
            <div class="bts-title">Projets BTS</div>
            <nav class="breadcrumb">
                <a href="?">Racine</a>
                <?php
                $parts = $relPath ? explode('/', $relPath) : [];
                $accum = '';
                foreach ($parts as $i => $part) {
                    $accum .= ($i > 0 ? '/' : '') . $part;
                    echo ' &gt; <a href="?path=' . urlencode($accum) . '">' . htmlspecialchars($part) . '</a>';
                }
                ?>
            </nav>
            <ul class="file-list">
                <?php
                foreach (getEntries($fullPath) as $entry) {
                    $entryPath = $relPath ? $relPath . '/' . $entry : $entry;
                    $absEntryPath = $fullPath . '/' . $entry;
                    if (isDir($absEntryPath)) {
                        echo '<li><span class="file-icon">📁</span><a class="file-link" href="?path=' . urlencode($entryPath) . '">' . htmlspecialchars($entry) . '</a></li>';
                    } elseif (isFile($absEntryPath)) {
                        $icon = getIcon($entry);
                        $scheme = (isset($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] : (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http'));
                        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
                        $fileUrl = $scheme . '://' . $host . '/BTS/' . ($relPath ? $relPath . '/' : '') . rawurlencode($entry);
                        echo '<li><span class="file-icon">' . $icon . '</span><a class="file-link" href="' . htmlspecialchars($fileUrl) . '" target="_blank">' . htmlspecialchars($entry) . '</a></li>';
                    }
                }
                ?>
            </ul>
            <div class="readonly">Navigation en lecture seule. Impossible de modifier ou supprimer les fichiers.</div>
        </div>
    </div>
</body>
</html>
