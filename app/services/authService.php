<?php

namespace Services;

use Exception;

require_once './app/models/userModel.php';

use Models\UserModel;


class AuthService
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Đăng ký
     */
    public function register($data)
    {
        // 1. Kiểm tra email tồn tại
        $user = $this->userModel->findByEmail($data['email']);
        if ($user) {
            throw new Exception("Email đã tồn tại");
        }

        // 2. Hash password
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);

        // 3. Lưu DB
        return $this->userModel->insert($data);
    }

    /**
     * Đăng nhập
     */
    public function login($email, $password)
    {
        $user = $this->userModel->findByEmail($email);

        if (!$user) {
            throw new Exception("Email không tồn tại");
        }

        if (!password_verify($password, $user['password'])) {
            throw new Exception("Sai mật khẩu");
        }

        // login success → lưu session
        $_SESSION['user'] = $user;

        return $user;
    }

    /**
     * Đăng xuất
     */
    public function logout()
    {
        unset($_SESSION['user']);
        session_destroy();
    }

    /**
     * Tìm hoặc tạo user từ Google Login (Phiên bản tối ưu)
     */
    public function findOrCreateGoogleUser(array $googleData): array
    {
        $googleId  = $googleData['googleId'] ?? null;
        $email     = $googleData['email'] ?? null;
        $fullName  = $googleData['fullName'] ?? 'Google User';
        $avatarUrl = $googleData['avatar'] ?? null;

        if (empty($googleId) || empty($email)) {
            throw new Exception("Thiếu thông tin từ Google.");
        }

        // 1. Tìm theo google_id
        $usersByGoogle = $this->userModel->select_array('*', ['google_id' => $googleId]);
        if (!empty($usersByGoogle)) {
            $user = $usersByGoogle[0];

            // Cập nhật thông tin mới nhất
            $this->userModel->update([
                'fullName'  => $fullName,
                'avatarUrl' => $avatarUrl
            ], ['id' => $user['id']]);

            return $user;
        }

        // 2. Tìm theo email (đã đăng ký bằng form thường)
        $usersByEmail = $this->userModel->select_array('*', ['email' => $email]);
        if (!empty($usersByEmail)) {
            $user = $usersByEmail[0];

            // Liên kết Google vào tài khoản cũ
            $this->userModel->update([
                'google_id' => $googleId,
                'avatarUrl' => $avatarUrl,
                'fullName'  => $fullName
            ], ['id' => $user['id']]);

            return $user;
        }

        // 3. Tạo user mới từ Google
        $newUserData = [
            'fullName'  => $fullName,
            'email'     => $email,
            'password'  => '123123',
            'google_id' => $googleId,
            'avatarUrl' => $avatarUrl,
            'role'      => 'CUSTOMER',
            'status'    => 'ACTIVE'
        ];

        $result = $this->userModel->insert($newUserData);
        $resultData = json_decode($result, true);

        if ($resultData['type'] !== 'success') {
            throw new Exception("Không thể tạo tài khoản mới từ Google.");
        }

        $newUserId = $resultData['data'] ?? 0;

        // Lấy thông tin user vừa tạo
        $newUsers = $this->userModel->select_array('*', ['id' => $newUserId]);

        if (empty($newUsers)) {
            throw new Exception("Lỗi khi lấy thông tin user mới.");
        }

        return $newUsers[0];
    }
}
