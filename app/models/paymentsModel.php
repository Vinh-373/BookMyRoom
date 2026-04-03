<?php
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/myModels.php';

class paymentsModel extends myModels {
    protected $table = "payments";
    // Các phương thức cụ thể cho bảng payments có thể được thêm vào đây nếu cần
}
