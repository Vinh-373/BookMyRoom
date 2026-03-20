<?php
session_start();
// Header
require_once 'header.php';

// Nội dung chính (từ controller truyền vào)
if (isset($data['viewFile'])) {
    require_once $data['viewFile'];
} else {
    echo "<p>Không tìm thấy nội dung.</p>";
}
// Footer
require_once 'footer.php';
?>
