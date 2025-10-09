<?php
require_once __DIR__ . '/../models/StatsModel.php';
class StatsController {
    public $totalConnexions = 0;
    public $nouveaux = 0;
    public $ips = [];
    public $suspectes = [];
    public $speedData = [];
    public function __construct() {
        $model = new StatsModel();
        $logIndex = $model->getLogIndex();
        $logSpeed = $model->getLogSpeed();
        $ipTimes = [];
        foreach ($logIndex as $line) {
            if (preg_match('/IP: ([^ ]+)/', $line, $m)) {
                $ip = $m[1];
                $this->ips[$ip] = ($this->ips[$ip] ?? 0) + 1;
                if (preg_match('/New: 1/', $line)) $this->nouveaux++;
                if (!isset($ipTimes[$ip])) $ipTimes[$ip] = [];
                if (preg_match('/^([0-9\- :]+)/', $line, $dm)) $ipTimes[$ip][] = strtotime($dm[1]);
            }
        }
        foreach ($ipTimes as $ip => $times) {
            sort($times);
            for ($i = 0; $i < count($times) - 5; $i++) {
                if ($times[$i+5] - $times[$i] < 30) {
                    $this->suspectes[] = $ip;
                    break;
                }
            }
        }
        $this->totalConnexions = count($logIndex);
        foreach ($logSpeed as $line) {
            if (preg_match('/^([0-9\- :]+).*Taille: ([0-9]+)MB \| Vitesse: ([0-9.]+)/', $line, $m)) {
                $this->speedData[] = [
                    'date' => $m[1],
                    'taille' => $m[2],
                    'vitesse' => $m[3]
                ];
            }
        }
    }
}
