<?php
require_once __DIR__ . '/../models/SpeedtestModel.php';
class SpeedtestController {
    public function handle() {
        $sizeMB = isset($_GET['size']) ? max(1, min(1024, intval($_GET['size']))) : 10;
        $action = $_GET['action'] ?? '';
        $model = new SpeedtestModel();
        if ($action === 'log' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $size = isset($_POST['size']) ? intval($_POST['size']) : 0;
            $speed = isset($_POST['speed']) ? floatval($_POST['speed']) : 0;
            $model->logSpeedtest($size, $speed);
            http_response_code(204);
            exit;
        }
        if ($action === 'download') {
            $model->generateFile($sizeMB);
        }
    }
}
