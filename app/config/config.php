<?php

/**
 * CẤU HÌNH DATABASE
 */
define('DB_HOST', 'localhost');
define('DB_USER', 'root');      // Mặc định của XAMPP thường là root
define('DB_PASS', '');          // Mặc định của XAMPP là trống
define('DB_NAME', 'bookmyroom'); // Tên database bạn đã tạo

/**
 * CẤU HÌNH ĐƯỜNG DẪN (PATH)
 */
// Đường dẫn gốc của ứng dụng (App Root)
// Ví dụ: C:\xampp\htdocs\bookroom\app
define('APPROOT', dirname(dirname(__FILE__)));

// URL Gốc (URL Root) - Thay đổi cho phù hợp với domain của bạn
// Giúp bạn gọi các file CSS/JS/Images một cách chính xác
define('URLROOT', 'http://localhost:81/bookroom');
define('URLIMAGE', 'http://localhost:81/bookroom/public/image');

// Tên website
define('SITENAME', 'BookMyRoom - Hotel Management');

/**
 * CẤU HÌNH PHIÊN BẢN (VERSION)
 */
define('APPVERSION', '1.0.0');