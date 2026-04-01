<?php
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/myModels.php';

class reviewsModel extends myModels {
    protected $table = "reviews";
    // Các phương thức cụ thể cho bảng reviews có thể được thêm vào đây nếu cần
}
