<?php
require_once __DIR__ . '/../models/UserModel.php';
class AdminDashboardController {
    public $users = [];
    public $isAdmin = false;
    public function __construct($mysqli, $identifiant) {
        $userModel = new UserModel($mysqli);
        $this->isAdmin = $userModel->isAdmin($identifiant);
        if ($this->isAdmin) {
            $this->users = $userModel->getAllUsers();
        }
    }
}
