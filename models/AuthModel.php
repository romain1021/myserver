<?php
class AuthModel {
    private $mysqli;
    public function __construct($mysqli) {
        $this->mysqli = $mysqli;
    }
    public function login($identifiant, $password) {
        $stmt = $this->mysqli->prepare('SELECT password, admin, photo FROM users WHERE identifiant = ?');
        $stmt->bind_param('s', $identifiant);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($hashedPassword, $isAdmin, $photo);
            $stmt->fetch();
            if (password_verify($password, $hashedPassword)) {
                return ['identifiant' => $identifiant, 'admin' => $isAdmin, 'photo' => $photo];
            }
        }
        $stmt->close();
        return false;
    }
    public function register($identifiant, $email, $password, $photoPath = null) {
        $stmt = $this->mysqli->prepare('SELECT id FROM users WHERE identifiant = ? OR email = ?');
        $stmt->bind_param('ss', $identifiant, $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->close();
            return 'Identifiant ou email déjà utilisé.';
        }
        $stmt->close();
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->mysqli->prepare('INSERT INTO users (identifiant, email, password, photo) VALUES (?, ?, ?, ?)');
        $stmt->bind_param('ssss', $identifiant, $email, $hashedPassword, $photoPath);
        $stmt->execute();
        $stmt->close();
        return true;
    }
}
