<?php
class Controller{
    public function view($name, $data = []) {
        extract($data);
        
        require_once './app/views/' . $name . '.php';
    }
    // Hàm bổ trợ trả về JSON (Nên đưa vào Base Controller nếu dùng nhiều)
    public function jsonResponse($data, $code = 200) {
        header_remove();
        http_response_code($code);
        header("Content-Type: application/json");
        echo json_encode($data);
        exit();
    }
}
