<?php
namespace Controllers\admin;
use Controller;

class Rooms extends Controller {
    public function index() {
if (empty($_SESSION["admin_id"]) || empty($_SESSION["admin_name"])) {
    // Chuyển hướng về trang auth (đăng nhập)
    header("Location: /BookMyRoom/admin/auth");
    exit(); // Luôn phải có exit để dừng thực thi code phía dưới
}
        $this->view('layout/admin/admin', [
            'viewFile' => './app/views/admin/rooms.php',
        ]);
    }
}