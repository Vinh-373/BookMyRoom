<?php



namespace Controllers\admin;

use Controller;
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
class Auth extends Controller
{
    public function index()
    {
        // Nếu đã đăng nhập rồi thì redirect thẳng vào dashboard
        if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
            header('Location: /BookMyRoom/admin/dashboard');
            exit();
        }

        $this->view('layout/admin/admin', [
            'viewFile' => './app/views/admin/auth.php',
            'title'    => 'Đăng nhập hệ thống'
        ]);
    }

    public function login()
    {
        // Luôn trả về JSON cho Fetch API
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if (empty($username) || empty($password)) {
                echo json_encode(['success' => false, 'message' => 'Vui lòng nhập đầy đủ thông tin.']);
                exit;
            }

            // Gọi Model
            require_once './app/models/customersModel.php';
            $customersModel = new \customersModel();

            // Truy vấn tìm Admin theo email (hoặc username)
            // Giả sử select_array trả về một mảng các bản ghi, ta lấy bản ghi đầu tiên [0]
            $adminData = $customersModel->select_array('*', [
                'role'  => 'ADMIN', 
                'email' => $username
            ]);

            if ($adminData && count($adminData) > 0) {
                $admin = $adminData[0]; // Lấy admin đầu tiên tìm được

                // Kiểm tra mật khẩu (Dùng password_verify nếu bạn đã hash mật khẩu)
                // Nếu bạn vẫn đang lưu text thuần (không khuyến khích), hãy đổi thành: $password === $admin['password']
                if (password_verify($password, $admin['password'])) {
                    
                    // Lưu thông tin vào Session
                    $_SESSION['admin_logged_in'] = true;
                    $_SESSION['admin_id']        = $admin['id'];
                    $_SESSION['admin_name']      = $admin['fullname'] ?? $admin['email'];
                    $_SESSION['admin_email']     = $admin['email'];

                    echo json_encode([
                        'success'  => true,
                        'redirect' => '/BookMyRoom/admin/dashboard'
                    ]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Mật khẩu không chính xác.']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Tài khoản quản trị không tồn tại.']);
            }
            exit;
        }
    }

    public function logout()
    {
        // Xóa sạch session liên quan đến admin
        unset($_SESSION['admin_logged_in']);
        unset($_SESSION['admin_id']);
        unset($_SESSION['admin_name']);
        unset($_SESSION['admin_email']);
        
        // Không nhất thiết phải destroy toàn bộ nếu bạn có session khách hàng song song
        // session_destroy(); 

        header('Location: /BookMyRoom/admin/auth');
        exit();
    }
}