<?php
require_once __DIR__ . '/../models/PasswordRecoveryModel.php';
class PasswordRecoveryController {
    public $msg = '';
    public function recover($mysqli) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $model = new PasswordRecoveryModel($mysqli);
            $identifiant = $model->findUserByEmail($email);
            if ($identifiant) {
                $this->msg = "Un email de réinitialisation a été envoyé (simulation) à l'utilisateur : " . htmlspecialchars($identifiant);
            } else {
                $this->msg = "Adresse email non trouvée.";
            }
        }
    }
}
