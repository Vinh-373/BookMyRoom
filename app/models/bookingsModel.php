<?php
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/myModels.php';

class BookingsModel extends myModels {
    protected $table = "bookings";
    // Các phương thức cụ thể cho bảng bookings có thể được thêm vào đây nếu cần
}