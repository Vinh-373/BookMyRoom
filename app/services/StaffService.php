<?php
require_once __DIR__ . '/../core/Service.php';
class StaffService extends Service {
    public function getHotelStaff($hotelId) {
        $userModel = $this->model('UserModel');
        return $userModel->getStaffByHotel($hotelId);
    }

    public function getStaffPageData($hotelId) {
        $userModel = $this->model('UserModel');
        $staffList = $userModel->getStaffByHotel($hotelId);

        // Khởi tạo các biến đếm
        $total = count($staffList);
        $active = 0;
        $managers = 0;
        $blocked = 0;

        foreach ($staffList as $s) {
            // Đếm nhân viên đang ở ca trực (ACTIVE)
            if ($s['status'] === 'ACTIVE') {
                $active++;
            }
            
            // Đếm nhân viên bị tạm khóa (BLOCKED)
            if ($s['status'] === 'BLOCKED') {
                $blocked++;
            }

            // Đếm cấp quản lý (Partner/Admin)
            if (in_array($s['role'], ['Partner', 'Admin'])) {
                $managers++;
            }
        }

        return [
            'staffList' => $staffList,
            'stats' => [
                'total'    => $total,
                'active'   => $active,
                'managers' => $managers,
                'blocked'  => $blocked
            ]
        ];
    }

    public function addNewStaff($data) {
        $userModel = $this->model('UserModel');
        
        // Logic: Kiểm tra email đã tồn tại chưa
        $existing = $userModel->getOneBy('email', $data['email']);
        if ($existing) return false;

        // Mã hóa mật khẩu trước khi gửi xuống Model
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        
        return $userModel->createStaff($data);
    }

    public function removeStaff($staffId, $hotelId) {
        $userModel = $this->model('UserModel');
        $staff = $userModel->getOneBy('id', $staffId);
        
        // Bảo mật: Chỉ xóa nếu nhân viên đó thuộc khách sạn của Partner này
        if ($staff && $staff['hotel_id'] == $hotelId) {
            return $userModel->softDeleteStaff($staffId);
        }
        return false;
    }

    public function changeStaffRole($staffId, $newRole, $hotelId) {
        $userModel = $this->model('UserModel');
        $staff = $userModel->getOneBy('id', $staffId);
        
        if ($staff && $staff['hotel_id'] == $hotelId) {
            return $userModel->updateRole($staffId, $newRole);
        }
        return false;
    }

    public function resetStaffPassword($staffId, $newPassword, $hotelId) {
        $userModel = $this->model('UserModel');
        $staff = $userModel->getOneBy('id', $staffId);

        // Bảo mật: Đảm bảo nhân viên thuộc khách sạn của Partner
        if ($staff && $staff['hotel_id'] == $hotelId) {
            $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
            return $userModel->updatePassword($staffId, $hashed);
        }
        return false;
    }

    public function toggleStaffStatus($staffId, $hotelId) {
        $userModel = $this->model('UserModel');
        
        // Kiểm tra nhân viên có thuộc khách sạn này không để tránh đổi nhầm hotel khác
        $staff = $userModel->getOneBy('id', $staffId);
        
        if ($staff && $staff['hotel_id'] == $hotelId) {
            // Đảo ngược trạng thái
            $newStatus = ($staff['status'] === 'ACTIVE') ? 'BLOCKED' : 'ACTIVE';
            return $userModel->toggleStatus($staffId, $newStatus);
        }
        
        return false;
    }
}