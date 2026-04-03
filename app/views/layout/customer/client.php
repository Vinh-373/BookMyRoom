<?php
session_start();
require_once 'header.php';
if (isset($data['viewFile'])) {

    require_once $data['viewFile'];

} else {
    echo "<p>Không tìm thấy nội dung (viewFile chưa được truyền).</p>";
}
require_once 'footer.php';
?>