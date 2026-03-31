<?php
ob_start();
session_start();

// 1. Nạp cấu hình hằng số
require_once '../app/config/config.php';

// 2. Nạp các lớp Core (Database, Controller, App)
require_once '../app/core/Database.php';
require_once '../app/core/Controller.php';
require_once '../app/core/Service.php';
require_once '../app/core/Model.php';
require_once '../app/core/App.php';

// 3. Khởi tạo ứng dụng
// Khi dòng này chạy, file App.php sẽ tự động phân tích URL 
// và gọi Controller/Action tương ứng mà bạn không cần viết IF/ELSE nữa.
$app = new App();
