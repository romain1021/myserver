<?php
class PasswordRecoveryModel {
    private $mysqli;
    public function __construct($mysqli) {
        $this->mysqli = $mysqli;
    }
    public function findUserByEmail($email) {
        $stmt = $this->mysqli->prepare('SELECT identifiant FROM users WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($identifiant);
            $stmt->fetch();
            $stmt->close();
            return $identifiant;
        }
        $stmt->close();
        return false;
    }
}
