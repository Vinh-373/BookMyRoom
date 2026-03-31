<?php
/**
 * Base Service Class
 * Nơi xử lý Logic nghiệp vụ (Business Logic)
 */
class Service {
    protected $db;

    public function __construct() {
        // Service cũng cần Database để thực hiện các câu lệnh SQL tổng hợp (JOIN phức tạp)
        $this->db = new Database();
    }

    /**
     * Hỗ trợ khởi tạo nhanh một Model bên trong Service
     * Giúp Service có thể gọi nhiều Model cùng lúc
     */
    public function model($modelName) {
        $modelPath = '../app/models/' . $modelName . '.php';
        if (file_exists($modelPath)) {
            require_once $modelPath;
            return new $modelName();
        }
        return null;
    }
}