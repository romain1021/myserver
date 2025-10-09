<?php
require_once __DIR__ . '/../models/UserDashboardModel.php';
class UserDashboardController {
    public $user = [];
    public function __construct($mysqli, $identifiant) {
        $model = new UserDashboardModel($mysqli);
        $this->user = $model->getUser($identifiant);
    }
}
