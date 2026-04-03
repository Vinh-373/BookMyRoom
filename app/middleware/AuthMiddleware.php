<?php
namespace Middleware;

use Exception;
use Services\JwtService;   // ← Service bạn đã tạo ở tin nhắn trước
require_once "./app/services/JwtService.php";

class AuthMiddleware {
    
    private $jwtService;

    public function __construct() {
        $this->jwtService = new JwtService();
    }

    /**
     * Kiểm tra token và trả về user data nếu hợp lệ
     * @return array|null User data hoặc null nếu không hợp lệ
     */
    public function check() {
        // Lấy token từ Header (chuẩn Bearer)
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '';

        if (empty($authHeader) || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            $this->jsonResponse([
                'status'  => 'error',
                'message' => 'Token không được cung cấp. Vui lòng đăng nhập lại.'
            ], 401);
            exit; // Dừng ngay lập tức
        }

        $token = $matches[1];

        try {
            // Verify token
            $userData = $this->jwtService->verifyToken($token);


            return $userData;   // Trả về để controller dùng

        } catch (Exception $e) {
            $this->jsonResponse([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 401);
            exit;
        }
    }

    /**
     * Hàm jsonResponse giống như trong Controller của bạn
     * (copy từ base Controller để middleware cũng dùng được)
     */
    private function jsonResponse($data, $statusCode = 200) {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
    }
}