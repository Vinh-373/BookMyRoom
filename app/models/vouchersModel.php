<?php
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/myModels.php';

class vouchersModel extends myModels {
    protected $table = "vouchers";
    // Các phương thức cụ thể cho bảng vouchers có thể được thêm vào đây nếu cần
}
