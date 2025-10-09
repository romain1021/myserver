<?php
class UserModel {
    private $mysqli;
    public function __construct($mysqli) {
        $this->mysqli = $mysqli;
    }
    public function getAllUsers() {
        $result = $this->mysqli->query('SELECT id, identifiant, admin, photo FROM users');
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        return $users;
    }
    public function isAdmin($identifiant) {
        $stmt = $this->mysqli->prepare('SELECT admin FROM users WHERE identifiant = ?');
        $stmt->bind_param('s', $identifiant);
        $stmt->execute();
        $stmt->bind_result($isAdmin);
        $stmt->fetch();
        $stmt->close();
        return $isAdmin;
    }
}
