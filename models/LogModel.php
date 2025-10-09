<?php
class LogModel {
    public function getLog($file) {
        return file_exists(__DIR__ . '/../' . $file) ? file(__DIR__ . '/../' . $file, FILE_IGNORE_NEW_LINES) : [];
    }
    public function resetLogPeriod($file, $periodSeconds) {
        $lines = $this->getLog($file);
        $now = time();
        $keep = [];
        foreach ($lines as $line) {
            if (preg_match('/^([0-9\- :]+)/', $line, $m)) {
                $date = strtotime($m[1]);
                if ($now - $date <= $periodSeconds) {
                    $keep[] = $line;
                }
            }
        }
        file_put_contents(__DIR__ . '/../' . $file, implode("\n", $keep));
    }
    public function logVisit($ip, $ua, $isNewUser) {
        $date = date('Y-m-d H:i:s');
        $logLine = $date . ' | IP: ' . $ip . ' | UA: ' . $ua . ' | New: ' . ($isNewUser ? '1' : '0') . "\n";
        file_put_contents(__DIR__ . '/../log_index.log', $logLine, FILE_APPEND | LOCK_EX);
    }
}
