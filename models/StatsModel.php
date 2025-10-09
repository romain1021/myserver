<?php
class StatsModel {
    public function getLogIndex() {
        return file_exists(__DIR__ . '/../log_index.log') ? file(__DIR__ . '/../log_index.log', FILE_IGNORE_NEW_LINES) : [];
    }
    public function getLogSpeed() {
        return file_exists(__DIR__ . '/../log_speedtest.log') ? file(__DIR__ . '/../log_speedtest.log', FILE_IGNORE_NEW_LINES) : [];
    }
}
