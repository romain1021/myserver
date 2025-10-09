<?php
class UserFilmsModel {
    private $mysqli;
    public function __construct($mysqli) {
        $this->mysqli = $mysqli;
    }
    public function proposerFilm($titre, $identifiant) {
        $stmt = $this->mysqli->prepare('INSERT INTO films (titre, propose_par) VALUES (?, ?)');
        $stmt->bind_param('ss', $titre, $identifiant);
        $stmt->execute();
        $stmt->close();
    }
    public function getRecentFilms() {
        $result = $this->mysqli->query('SELECT * FROM films WHERE valide = 1 ORDER BY date_ajout DESC LIMIT 10');
        $recent = [];
        while ($row = $result->fetch_assoc()) {
            $recent[] = $row;
        }
        return $recent;
    }
}
