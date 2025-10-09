<?php
// Routeur centralisé pour version MVC
$routes = [
    '' => 'views/index.php',
    'login' => 'views/login.php',
    'register' => 'views/register.php',
    'forgot_password' => 'views/forgot_password.php',
    'home' => 'views/homePage.php',
    'admin_dashboard' => 'views/admin_dashboard.php',
    'user_films' => 'views/user_films.php',
    'admin_films' => 'views/admin_films.php',
    'stats' => 'views/stats.php',
    'log' => 'views/log.php',
    'projets' => 'views/projets.php',
    'speedtest' => 'views/speedtest.php',
    'articles' => 'views/articles.php'
];
$page = $_GET['page'] ?? '';
if (isset($routes[$page])) {
    require __DIR__ . '/' . $routes[$page];
} else {
    http_response_code(404);
    echo '<h2 style="text-align:center;color:#B19CD9;margin-top:3rem;">Page non trouvée</h2>';
}
