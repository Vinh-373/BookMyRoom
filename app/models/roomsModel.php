<?php
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/myModels.php';

class roomsModel extends myModels {
    protected $table = "rooms";
    // Các phương thức cụ thể cho bảng rooms có thể được thêm vào đây nếu cần
}
