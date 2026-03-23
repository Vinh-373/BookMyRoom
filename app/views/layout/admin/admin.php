<?php
session_start();
// Sidebar
require_once 'sidebar.php';
// Header
require_once 'header.php';
?>

<div class="main-content">
    <?php
    // Nội dung chính (từ controller truyền vào)
    if (isset($data['viewFile'])) {
        require_once $data['viewFile'];
    } else {
        echo "<p>Không tìm thấy nội dung.</p>";
    }
    ?>
</div>