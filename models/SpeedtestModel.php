<?php
class SpeedtestModel {
    public function logSpeedtest($size, $speed) {
        $logFile = __DIR__ . '/../log_speedtest.log';
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $ua = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
        $date = date('Y-m-d H:i:s');
        $logLine = $date . ' | IP: ' . $ip . ' | UA: ' . $ua . ' | Taille: ' . $size . 'MB | Vitesse: ' . $speed . " Mo/s\n";
        file_put_contents($logFile, $logLine, FILE_APPEND | LOCK_EX);
    }
    public function generateFile($sizeMB) {
        $filename = 'speedtest_' . $sizeMB . 'MB_' . time() . '.bin';
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . ($sizeMB * 1024 * 1024));
        $chunk = str_repeat("0", 1024 * 1024);
        for ($i = 0; $i < $sizeMB; $i++) {
            echo $chunk;
        }
        exit;
    }
}
