<?php
class AdminFilmsModel {
    private $mysqli;
    public function __construct($mysqli) {
        $this->mysqli = $mysqli;
    }
    public function deleteFilm($film_id) {
        $stmt = $this->mysqli->prepare('DELETE FROM films WHERE id = ?');
        $stmt->bind_param('i', $film_id);
        $stmt->execute();
        $stmt->close();
    }
    public function validateFilm($film_id) {
        $stmt = $this->mysqli->prepare('UPDATE films SET valide = 1, date_ajout = NOW() WHERE id = ?');
        $stmt->bind_param('i', $film_id);
        $stmt->execute();
        $stmt->close();
    }
    public function cleanupOldFilms() {
        $this->mysqli->query("DELETE FROM films WHERE valide = 1 AND date_ajout < DATE_SUB(NOW(), INTERVAL 1 DAY)");
    }
    public function getNonValidatedFilms() {
        $result = $this->mysqli->query('SELECT * FROM films WHERE valide = 0 ORDER BY date_ajout DESC');
        $films = [];
        while ($row = $result->fetch_assoc()) {
            $films[] = $row;
        }
        return $films;
    }
    public function getRecentValidatedFilms() {
        $result = $this->mysqli->query('SELECT * FROM films WHERE valide = 1 ORDER BY date_ajout DESC LIMIT 10');
        $recent = [];
        while ($row = $result->fetch_assoc()) {
            $recent[] = $row;
        }
        return $recent;
    }
}
