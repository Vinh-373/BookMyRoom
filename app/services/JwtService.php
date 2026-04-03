<?php
namespace Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;
require_once __DIR__ . '/../../vendor/autoload.php';


class JwtService {
    private $key;
    private $algorithm = 'HS256';

    public function __construct() {
        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();
        $this->key = $_ENV['JWT_SECRET'] ?? 'fallback_key_chi_dung_de_test';
    }

    public function generateToken(array $userData, $remember = false) {
        $payload = [
            'iss'  => $_ENV['APP_URL'] ?? 'http://localhost',
            'iat'  => time(),
            'exp'  => time() + ($remember ? 7 * 24 * 60 * 60 : 60 * 60), // 7 ngày nếu nhớ, ngược lại 1 giờ
            'jti'  => bin2hex(random_bytes(16)),
            'data' => $userData
        ];
        return JWT::encode($payload, $this->key, $this->algorithm);
    }

    public function verifyToken($token) {
        try {
            $decoded = JWT::decode($token, new Key($this->key, $this->algorithm));
            return (array)$decoded->data;
        } catch (Exception $e) {
            throw new Exception("Token không hợp lệ hoặc đã hết hạn");
        }
    }
}