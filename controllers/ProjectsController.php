<?php
require_once __DIR__ . '/../models/ProjectsModel.php';
class ProjectsController {
    public $entries = [];
    public $error = '';
    public $baseUrl;
    public $relPath;
    public $targetPath;
    public $fullPath;
    public function __construct() {
        $model = new ProjectsModel();
        $this->baseUrl = $model->baseUrl;
        $relPath = isset($_GET['path']) ? $_GET['path'] : '';
        $relPath = ltrim($relPath, '/');
        $this->relPath = $relPath;
        $this->targetPath = $model->localPath . ($relPath ? '/' . $relPath : '');
        if (!$model->isDir($this->targetPath)) {
            $this->error = 'Accès refusé.';
            return;
        }
        $this->fullPath = realpath($this->targetPath);
        if ($this->fullPath === false || strpos($this->fullPath, realpath($model->localPath)) !== 0) {
            $this->error = 'Accès refusé.';
            return;
        }
        $this->entries = $model->getEntries($this->targetPath);
    }
}
