<?php
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/myModels.php';

class CustomerModel extends myModels {
    protected $table = "customers";
    // Các phương thức cụ thể cho bảng customers có thể được thêm vào đây nếu cần
}
