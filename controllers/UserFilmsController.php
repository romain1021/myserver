<?php
require_once __DIR__ . '/../models/UserFilmsModel.php';
class UserFilmsController {
    public $recent = [];
    public function __construct($mysqli, $identifiant) {
        $model = new UserFilmsModel($mysqli);
        if (isset($_POST['proposer']) && !empty($_POST['titre'])) {
            $model->proposerFilm(trim($_POST['titre']), $identifiant);
        }
        $this->recent = $model->getRecentFilms();
    }
}
