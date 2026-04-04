<?php
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/myModels.php';

class SettingsModel extends myModels {
    protected $table = "settings";
    // Các phương thức cụ thể cho bảng settings có thể được thêm vào đây nếu cần
}