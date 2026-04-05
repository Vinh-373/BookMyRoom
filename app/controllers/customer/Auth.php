<?php

namespace Controllers\customer;

use Controller;
use Exception;
use Services\AuthService;
use Services\JwtService;
use Middleware\AuthMiddleware;
use Google\Client;
use Services\UserService;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

require_once "./app/services/AuthService.php";
require_once "./app/services/JwtService.php";
require_once "./app/middleware/AuthMiddleware.php";
require 'vendor/autoload.php';
require_once "./app/services/userService.php";
// define('URLROOT', 'http://localhost/BookMyRoom');
require_once __DIR__ . '/../../../vendor/autoload.php';

class Auth extends Controller
{
    private $authService;
    private $googleClient;

    public function __construct()
    {
        $this->authService = new AuthService();
        $this->initializeGoogleClient();
        
    }

    private function initializeGoogleClient()
    {
        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../');
        $dotenv->load();
        $this->googleClient = new Client();
        $this->googleClient->setClientId($_ENV['CLIENT_ID']);
        $this->googleClient->setClientSecret($_ENV['CLIENT_SECRET']);
        $this->googleClient->setRedirectUri('/BookMyRoom/auth/googleLogin');
    }

    private function verifyGoogleToken($token)
    {
        try {
            $payload = $this->googleClient->verifyIdToken($token);
            if ($payload) return $payload;
        } catch (\Exception $e) {
            error_log("Google Token Verification Error: " . $e->getMessage());
            return null;
        }
        return null;
    }

    public function index()
    {

        $this->view('layout/customer/client', [
            'viewFile' => './app/views/customer/auth/authPage.php'
        ]);
    }

    public function me()
    {
        $auth = new AuthMiddleware();
        $user = $auth->check();
        $this->jsonResponse(['status' => 'success', 'user' => $user]);
    }

    public function handleRegister()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->jsonResponse(['status' => 'error', 'message' => 'Phương thức không hợp lệ'], 405);
        }

        try {
            $data = [
                'fullName' => $_POST['fullname'] ?? '',
                'email'    => $_POST['email'] ?? '',
                'password' => $_POST['password'] ?? '',
                'role'     => 'CUSTOMER'
            ];

            if (empty($data['fullName']) || empty($data['email']) || empty($data['password'])) {
                throw new Exception("Vui lòng điền đầy đủ thông tin.");
            }

            $this->authService->register($data);

            $this->jsonResponse(['status' => 'success', 'message' => 'Đăng ký thành công!']);
        } catch (Exception $e) {
            $this->jsonResponse(['status' => 'error', 'message' => $e->getMessage()], 400);
        }
    }

    public function handleLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->jsonResponse(['status' => 'error', 'message' => 'Phương thức không hợp lệ'], 405);
        }

        try {
            $email    = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $remember = isset($_POST['remember']);
            $redirect = $_POST['redirect'] ?? null;

            $user = $this->authService->login($email, $password);
            $jwtService = new JwtService();
            $token = $jwtService->generateToken([
                'id'       => $user['id'],
                'email'    => $user['email'],
                'role'     => $user['role'],
                'fullName' => $user['fullName']
            ], $remember);

            $this->jsonResponse([
                'status'   => 'success',
                'token'    => $token,
                'redirect' => $redirect,
                'user'     => $user
            ]);
        if (session_status() === PHP_SESSION_NONE) session_start();

            $_SESSION['user'] = $user; // Lưu thông tin user vào session sau khi đăng nhập thành công
            $_SESSION['role'] = $user['role']; // Lưu role vào session để kiểm tra phân quyền sau này
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['active_hotel_id'] = $user['hotelId'] ?? null;
            echo "User logged in: " . $_SESSION['role']; // Debug: Kiểm tra xem session đã lưu đúng chưa
            die(); // Dừng chương trình để xem kết quả debug

        } catch (Exception $e) {
            $this->jsonResponse(['status' => 'error', 'message' => $e->getMessage()], 400);
        }
    }

    public function googleLogin()
{
    try {
        $idToken  = $_POST['google_token'] ?? '';
        $remember = isset($_POST['remember']);
        $payload = $this->verifyGoogleToken($idToken);

        if (!$payload) throw new Exception("Token Google không hợp lệ.");

        $user = $this->authService->findOrCreateGoogleUser([
            'googleId' => $payload['sub'],
            'email'    => $payload['email'],
            'fullName' => $payload['name'],
            'avatar'   => $payload['picture'] ?? null
        ]);

        // --- BỔ SUNG DÒNG NÀY ---
        if (session_status() === PHP_SESSION_NONE) session_start();
        $_SESSION['user'] = $user; 
        // -----------------------

        $jwtService = new JwtService();
        $token = $jwtService->generateToken($user, $remember);

        $this->jsonResponse(['status' => 'success', 'token' => $token, 'user' => $user]);

    } catch (Exception $e) {
        $this->jsonResponse(['status' => 'error', 'message' => $e->getMessage()], 400);
    }
}

    public function logout()
    {

        $this->authService->logout();
        $this->jsonResponse(['status' => 'success', 'message' => 'Đăng xuất thành công']);
        

    }

    public function forgot()
    {
        $this->view('layout/customer/client', [
            'viewFile' => './app/views/customer/auth/forgotPage.php'
        ]);
    }

    /**
     * Xử lý kiểm tra Email và gửi OTP
     */
    public function checkEmail()
    {
        // Bật buffer để chặn các echo không mong muốn
        ob_start();

        try {
            $inputRaw = file_get_contents('php://input');
            $input = json_decode($inputRaw, true);
            $email = $input['email'] ?? '';

            if (empty($email)) {
                throw new Exception('Email không được để trống');
            }

            $userService = new UserService();
            $user = $userService->getUserByEmail($email);

            if (!$user) {
                ob_clean();
                return $this->jsonResponse(['status' => 'error', 'exists' => false, 'message' => 'Email không tồn tại'], 404);
            }

            // Gửi OTP
            $sent = $this->sendOtpEmail($email);

            if (!$sent) {
                throw new Exception('Không thể gửi mã OTP. Vui lòng thử lại sau.');
            }

            // Xóa sạch buffer trước khi trả về JSON
            ob_clean();
            $this->jsonResponse([
                'status' => 'success',
                'exists' => true,
                'message' => 'Mã xác thực đã được gửi về email của bạn'
            ]);
        } catch (Exception $e) {
            ob_clean();
            $this->jsonResponse(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Gửi OTP qua Email
     */
    private function sendOtpEmail($email)
    {
        $otp = rand(100000, 999999);

        if (session_status() === PHP_SESSION_NONE) session_start();
        $_SESSION['reset_otp'] = $otp;
        $_SESSION['reset_email'] = $email;
        $_SESSION['reset_otp_expire'] = time() + 300;

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->SMTPDebug = 0; // TẮT DEBUG ĐỂ KHÔNG LÀM HỎNG JSON
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'embimatdep@gmail.com';
            $mail->Password   = 'jzyl bvyk pkkz kcpx';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            $mail->CharSet    = 'UTF-8';

            $mail->setFrom('embimatdep@gmail.com', 'BookMyRoom');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Mã xác nhận khôi phục mật khẩu - BookMyRoom';
            $mail->Body    = "
                <div style='font-family: Arial, sans-serif; padding: 20px;'>
                    <h3>Khôi phục mật khẩu</h3>
                    <p>Mã OTP của bạn là: <b style='font-size: 20px; color: #0066da;'>{$otp}</b></p>
                    <p>Mã này có hiệu lực trong 5 phút. Vui lòng không chia sẻ mã này với bất kỳ ai.</p>
                </div>
            ";

            return $mail->send();
        } catch (Exception $e) {
            error_log("Mail Error: " . $mail->ErrorInfo);
            return false;
        }
    }
    public function verifyOtp()
{
    // Không dùng ob_start() lúc debug để nội dung echo hiện ra ngay
    if (session_status() === PHP_SESSION_NONE) session_start();

    try {
        $inputRaw = file_get_contents('php://input');
        $input = json_decode($inputRaw, true);

        $userOtp = isset($input['otp']) ? (string)$input['otp'] : 'MISSING';
        $sessionOtp = isset($_SESSION['reset_otp']) ? (string)$_SESSION['reset_otp'] : 'EMPTY_SESSION';
        $expireTime = $_SESSION['reset_otp_expire'] ?? 0;
        $currentTime = time();

        // --- ĐOẠN DEBUG QUAN TRỌNG ---
        // Dùng die() để dừng chương trình và xem dữ liệu ngay lập tức
        /* die(json_encode([
            'debug_info' => 'Checking Values',
            'user_input_otp' => $userOtp,
            'session_stored_otp' => $sessionOtp,
            'is_match' => ($userOtp === $sessionOtp),
            'expire_time' => $expireTime,
            'current_time' => $currentTime,
            'is_expired' => ($currentTime > $expireTime),
            'full_session' => $_SESSION // Xem toàn bộ session có gì
        ])); 
        */
        // -----------------------------

        if (empty($userOtp) || $userOtp !== $sessionOtp) {
            return $this->jsonResponse([
                'status' => 'error', 
                'message' => 'Mã không khớp',
                'debug' => "User: $userOtp, Session: $sessionOtp" // Hiện lỗi trực tiếp
            ], 400);
        }

        if ($currentTime > $expireTime) {
            return $this->jsonResponse([
                'status' => 'error', 
                'message' => 'Mã hết hạn',
                'debug' => "Now: $currentTime, Expire: $expireTime"
            ], 400);
        }

        $_SESSION['otp_verified'] = true;
        return $this->jsonResponse(['status' => 'success', 'message' => 'Xác thực thành công']);

    } catch (Exception $e) {
        return $this->jsonResponse(['status' => 'error', 'message' => $e->getMessage()], 500);
    }
}
    public function updatePassword()
    {
        ob_start();
        try {
           

            // 1. Kiểm tra bảo mật: User phải xác thực OTP thành công mới được vào đây
            if (!isset($_SESSION['otp_verified']) || $_SESSION['otp_verified'] !== true) {
                ob_clean();
                return $this->jsonResponse(['status' => 'error', 'message' => 'Hành động không hợp lệ.'], 403);
            }

            $inputRaw = file_get_contents('php://input');
            $input = json_decode($inputRaw, true);
            $newPassword = $input['password'] ?? '';
            $email = $_SESSION['reset_email'] ?? '';

            if (empty($newPassword) || empty($email)) {
                throw new Exception("Dữ liệu không hợp lệ.");
            }

            // 2. Mã hóa mật khẩu mới (Bcrypt)
            $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

            // 3. Gọi UserService để cập nhật vào Database
            $userService = new UserService();
            $updateSuccess = $userService->updatePasswordByEmail($email, $hashedPassword);

            if ($updateSuccess) {
                // Xóa session sau khi đổi thành công để bảo mật
                unset($_SESSION['reset_otp']);
                unset($_SESSION['reset_email']);
                unset($_SESSION['otp_verified']);
                unset($_SESSION['reset_otp_expire']);

                ob_clean();
                return $this->jsonResponse(['status' => 'success', 'message' => 'Cập nhật mật khẩu thành công.']);
            } else {
                throw new Exception("Không thể cập nhật mật khẩu vào database.");
            }
        } catch (Exception $e) {
            ob_clean();
            $this->jsonResponse(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
