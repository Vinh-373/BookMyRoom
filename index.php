<?php
ob_start();
session_start();

// 1. Nạp cấu hình hằng số (Bỏ ../)
require_once 'app/config/config.php';

// 2. Nạp các lớp Core (Bỏ ../)
require_once 'app/core/partner/Database.php';
require_once 'app/core/partner/Controller.php';
require_once 'app/core/partner/Service.php';
require_once 'app/core/partner/Model.php';
require_once 'app/core/App.php';

// 3. Khởi tạo ứng dụng
$app = new App();