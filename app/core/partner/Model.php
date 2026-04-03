<?php
/**
 * Base Model Class
 * Cung cấp kết nối Database trực tiếp cho các tác vụ CRUD cơ bản
 */
class Model {
    protected $db;

    public function __construct() {
        // Khởi tạo đối tượng Database (đã được nạp ở index.php)
        $this->db = new Database();
    }
}