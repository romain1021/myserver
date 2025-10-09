<?php
require_once __DIR__ . '/../models/LogModel.php';
class LogController {
    public $logContent = [];
    public function __construct() {
        $logFiles = [
            'Accès (index)' => 'log_index.log',
            'Speed Test' => 'log_speedtest.log'
        ];
        $selected = $_GET['log'] ?? 'log_index.log';
        $reset = $_POST['reset'] ?? null;
        $period = $_POST['period'] ?? null;
        $periods = [
            '1h' => 3600,
            '2h' => 7200,
            '1j' => 86400,
            '3j' => 3*86400,
            '7j' => 7*86400,
            '30j' => 30*86400,
            '3mois' => 90*86400
        ];
        $model = new LogModel();
        if ($reset && isset($periods[$period])) {
            $model->resetLogPeriod($selected, $periods[$period]);
            header('Location: log.php?log=' . urlencode($selected));
            exit;
        }
        $this->logContent = array_reverse($model->getLog($selected));
    }
}
