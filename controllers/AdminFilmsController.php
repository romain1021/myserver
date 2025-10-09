<?php
require_once __DIR__ . '/../models/AdminFilmsModel.php';
class AdminFilmsController {
    public $films = [];
    public $recent = [];
    public function __construct($mysqli) {
        $model = new AdminFilmsModel($mysqli);
        if (isset($_POST['delete']) && isset($_POST['film_id'])) {
            $model->deleteFilm($_POST['film_id']);
        }
        if (isset($_POST['valider']) && isset($_POST['film_id'])) {
            $model->validateFilm($_POST['film_id']);
        }
        $model->cleanupOldFilms();
        $this->films = $model->getNonValidatedFilms();
        $this->recent = $model->getRecentValidatedFilms();
    }
}
