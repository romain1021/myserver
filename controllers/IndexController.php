<?php
require_once __DIR__ . '/../models/LogModel.php';
class IndexController {
    public $isNewUser = false;
    public function __construct() {
        $cookieConsent = $_COOKIE['meowserver_cookie_consent'] ?? null;
        if ($cookieConsent === 'accept') {
            if (!isset($_COOKIE['meowserver_visited'])) {
                $this->isNewUser = true;
                setcookie('meowserver_visited', '1', time() + 60*60*24*365, "/");
            }
            $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
            $ua = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
            $logModel = new LogModel(__DIR__ . '/../log_index.log');
            $logModel->logVisit($ip, $ua, $this->isNewUser);
        }
    }
}
