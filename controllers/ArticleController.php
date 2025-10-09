<?php
require_once __DIR__ . '/../models/ArticleModel.php';
class ArticleController {
    public $articles = [];
    public $error = '';
    public function __construct($mysqli, $auteur) {
        $model = new ArticleModel($mysqli);
        if (isset($_POST['ajouter']) && !empty($_POST['titre']) && !empty($_POST['contenu'])) {
            $model->addArticle(trim($_POST['titre']), trim($_POST['contenu']), $auteur);
        }
        $this->articles = $model->getArticles();
    }
}
