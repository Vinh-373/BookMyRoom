<?php
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/myModels.php';

class staffsModel extends myModels {
    protected $table = "users";
    // Các phương thức cụ thể cho bảng users có thể được thêm vào đây nếu cần
}
