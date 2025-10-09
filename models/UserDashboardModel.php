<?php
class UserDashboardModel {
    private $mysqli;
    public function __construct($mysqli) {
        $this->mysqli = $mysqli;
    }
    public function getUser($identifiant) {
        $stmt = $this->mysqli->prepare('SELECT admin, photo FROM users WHERE identifiant = ?');
        $stmt->bind_param('s', $identifiant);
        $stmt->execute();
        $stmt->bind_result($isAdmin, $photo);
        $stmt->fetch();
        $stmt->close();
        return ['admin' => $isAdmin, 'photo' => $photo];
    }
}
