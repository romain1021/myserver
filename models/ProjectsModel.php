<?php
class ProjectsModel {
    public $baseUrl = 'https://home.meowserverlom.app/BTS/';
    public $localPath = '/var/www/html/BTS';
    public function getEntries($dir) {
        $entries = array_diff(scandir($dir), ['.', '..']);
        natcasesort($entries);
        return $entries;
    }
    public function isDir($path) {
        return is_dir($path);
    }
    public function isFile($path) {
        return is_file($path);
    }
    public function getIcon($file) {
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        $icons = [
            'pdf' => '📄', 'doc' => '📄', 'docx' => '📄', 'xls' => '📊', 'xlsx' => '📊', 'ppt' => '📊', 'pptx' => '📊',
            'jpg' => '🖼️', 'jpeg' => '🖼️', 'png' => '🖼️', 'gif' => '🖼️', 'zip' => '🗜️', 'rar' => '🗜️', 'txt' => '📄', 'csv' => '📄'
        ];
        return $icons[$ext] ?? '📁';
    }
}
