<?php
/**
 * User API - Handle user profile operations
 */

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../app/core/Database.php';

class UserApi {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->conn;
    }

    /**
     * Handle API requests
     */
    public static function handle() {
        try {
            $api = new self();
            $action = $_POST['action'] ?? $_GET['action'] ?? '';

            switch ($action) {
                case 'updateProfile':
                    $api->updateProfile();
                    break;

                case 'getProfile':
                    $api->getProfile();
                    break;

                default:
                    self::response(false, 'Invalid action: ' . $action);
            }
        } catch (Exception $e) {
            error_log("API Error: " . $e->getMessage());
            self::response(false, 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Update user profile
     */
    private function updateProfile() {
        $fullName = $_POST['fullName'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';

        if (empty($fullName) || empty($email)) {
            self::response(false, 'Họ tên và Email không được rỗng');
            return;
        }

        // For demo: Store in session (in production, store in database)
        $_SESSION['user_fullName'] = $fullName;
        $_SESSION['user_email'] = $email;
        $_SESSION['user_phone'] = $phone;

        // You can also store in database if needed:
        // UPDATE users SET fullName = ?, email = ?, phone = ? WHERE id = ?

        self::response(true, 'Cập nhật thành công', [
            'fullName' => $fullName,
            'email' => $email,
            'phone' => $phone
        ]);
    }

    /**
     * Get user profile
     */
    private function getProfile() {
        $profile = [
            'fullName' => $_SESSION['user_fullName'] ?? 'Admin User',
            'email' => $_SESSION['user_email'] ?? 'admin@hotel.com',
            'phone' => $_SESSION['user_phone'] ?? ''
        ];

        self::response(true, 'Profile retrieved', $profile);
    }

    /**
     * Send JSON response
     */
    private static function response($success, $message = '', $data = null) {
        $response = [
            'success' => $success,
            'message' => $message
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }
}

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Handle the request
UserApi::handle();
?>
