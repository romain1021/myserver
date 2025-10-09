<?php
class ArticleModel {
    private $mysqli;
    public function __construct($mysqli) {
        $this->mysqli = $mysqli;
    }
    public function addArticle($titre, $contenu, $auteur) {
        $stmt = $this->mysqli->prepare('INSERT INTO articles (titre, contenu, auteur) VALUES (?, ?, ?)');
        $stmt->bind_param('sss', $titre, $contenu, $auteur);
        $stmt->execute();
        $stmt->close();
    }
    public function getArticles() {
        $result = $this->mysqli->query('SELECT * FROM articles ORDER BY date_publication DESC');
        $articles = [];
        while ($row = $result->fetch_assoc()) {
            $articles[] = $row;
        }
        return $articles;
    }
}
