<?php
ob_start();

require_once "./app/server.php";
require_once "./app/helpers/UrlHelper.php";
 //require_once './vendor/autoload.php';       

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
?>

<!--   http://localhost//BookMyRoom   -->


<!--   http://localhost/BookMyRoom/admin   -->
