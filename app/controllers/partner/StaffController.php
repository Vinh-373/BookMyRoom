<?php
require_once '../app/controllers/PartnerController.php';

class StaffController extends PartnerController {
    
    public function __construct() {
        // Kiểm tra quyền đăng nhập/role Partner từ cha
        parent::__construct();
        
        // Kiểm tra nếu chưa chọn khách sạn thì bắt quay về trang Portfolio
        if (!isset($_SESSION['active_hotel_id'])) {
            header('Location: ' . URLROOT . '/partner');
            exit;
        }
    }

    public function index() {
        $hotelId = $this->activeHotelId;
        $staffService = $this->service('StaffService');
        
        // Lấy toàn bộ dữ liệu trang nhân sự
        $data = $staffService->getStaffPageData($hotelId);
        $data['partnerHotels'] = $this->partnerHotels;
        $data['activeHotelId'] = $this->activeHotelId;
        
        $this->viewPartner('staff', $data);
    }

    public function createStaff() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $staffService = $this->service('StaffService');
            
            $formData = [
                'fullName'   => $_POST['fullName'],
                'email'      => $_POST['email'],
                'password'   => $_POST['password'],
                'phone'      => $_POST['phone'],
                'role'       => $_POST['role'] ?? 'Staff',
                'hotel_id'   => $_SESSION['active_hotel_id'],
                'created_by' => $_SESSION['user_id'] ?? 1
            ];

            if ($staffService->addNewStaff($formData)) {
                $_SESSION['flash_message'] = [
                    'type'  => 'success',
                    'title' => 'Thành công!',
                    'text'  => 'Tài khoản nhân viên đã được tạo thành công.'
                ];
            } else {
                $_SESSION['flash_message'] = [
                    'type'  => 'error',
                    'title' => 'Lỗi!',
                    'text'  => 'Email đã tồn tại hoặc dữ liệu không hợp lệ.'
                ];
            }
            header('Location: ' . URLROOT . '/staff');
            exit;
        }
    }

    public function removeStaff($id) {
        $staffService = $this->service('StaffService');
        if ($staffService->removeStaff($id, $_SESSION['active_hotel_id'])) {
            $_SESSION['flash_message'] = [
                'type'  => 'success',
                'title' => 'Đã xóa!',
                'text'  => 'Nhân viên đã được loại bỏ khỏi khách sạn.'
            ];
        } else {
            $_SESSION['flash_message'] = [
                'type'  => 'error',
                'title' => 'Lỗi!',
                'text'  => 'Không thể xóa nhân viên này.'
            ];
        }
        header('Location: ' . URLROOT . '/staff');
        exit;
    }

    public function changeRole() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $staffService = $this->service('StaffService');
            $id = $_POST['staffId'];
            $role = $_POST['newRole'];
            
            if ($staffService->changeStaffRole($id, $role, $_SESSION['active_hotel_id'])) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false]);
            }
        }
    }

    public function resetPassword() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $staffId = $_POST['staffId'];
            $newPass = $_POST['newPassword'];
            $hotelId = $_SESSION['active_hotel_id'];

            $service = $this->service('StaffService');
            if ($service->resetStaffPassword($staffId, $newPass, $hotelId)) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false]);
            }
        }
    }

    public function toggleStatus($id) {
        $hotelId = $_SESSION['active_hotel_id'];
        $staffService = $this->service('StaffService');
        
        if ($staffService->toggleStaffStatus($id, $hotelId)) {
            $_SESSION['flash_message'] = [
                'type'  => 'success',
                'title' => 'Thành công!',
                'text'  => 'Trạng thái hoạt động của nhân viên đã được cập nhật.'
            ];
        } else {
            $_SESSION['flash_message'] = [
                'type'  => 'error',
                'title' => 'Lỗi!',
                'text'  => 'Không thể cập nhật trạng thái lúc này.'
            ];
        }
        
        header('Location: ' . URLROOT . '/staff');
        exit;
    }
}