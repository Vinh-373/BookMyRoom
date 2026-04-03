<?php
/**
 * Base Service Class
 * Nơi xử lý Logic nghiệp vụ (Business Logic)
 */
class Service {
    protected $db;

    public function __construct() {
        // Khởi tạo Database core để xử lý các tác vụ nếu cần
        $this->db = new Database();
    }

    /**
     * Hỗ trợ khởi tạo nhanh một Model bên trong Service
     * Giúp một Service có thể phối hợp dữ liệu từ nhiều Model khác nhau
     */
    public function model($modelName) {
        $modelPath = APPROOT . '/models/partner/' . $modelName . '.php';
        
        if (file_exists($modelPath)) {
            require_once $modelPath;
            
            // Kiểm tra xem class có tồn tại sau khi require không
            if (class_exists($modelName)) {
                return new $modelName();
            }
        }
        
        // Thay vì return null, die để báo lỗi cụ thể giúp debug nhanh hơn
        die("Lỗi hệ thống: Model <b>{$modelName}</b> không tìm thấy tại đường dẫn: {$modelPath}");
    }
}