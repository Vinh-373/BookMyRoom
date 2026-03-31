<?php
require_once '../app/controllers/PartnerController.php';
class StaffController extends PartnerController {
    public function __construct() {
        // Chạy construct của cha để kiểm tra quyền đăng nhập/role Partner
        parent::__construct();
        
        // Kiểm tra nếu chưa chọn khách sạn thì bắt quay về trang Portfolio
        if (!isset($_SESSION['active_hotel_id'])) {
            header('Location: ' . URLROOT . '/partner');
            exit;
        }
    }
    public function index() {
        $hotelId= $this->activeHotelId;

        $staffService = $this->service('StaffService');
        
        // 2. Lấy toàn bộ dữ liệu trang nhân sự (bao gồm List và Stats)
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
                'created_by' => 1//$_SESSION['user_id']
            ];

            if ($staffService->addNewStaff($formData)) {
                header('Location: ' . URLROOT . '/partner/staff?success=1');
            } else {
                header('Location: ' . URLROOT . '/partner/staff?error=email_exists');
            }
        }
    }

    public function removeStaff($id) {
        $staffService = $this->service('StaffService');
        if ($staffService->removeStaff($id, $_SESSION['active_hotel_id'])) {
            header('Location: ' . URLROOT . '/partner/staff?msg=removed');
        }
    }

    // Xử lý đổi vai trò (Sử dụng AJAX hoặc POST thông thường)
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
        // 1. Lấy Hotel ID từ session để đảm bảo an toàn dữ liệu
        $hotelId = $_SESSION['active_hotel_id'];
        
        // 2. Gọi Service xử lý (Service này sẽ gọi Model updateStatus đã có)
        $staffService = $this->service('StaffService');
        
        if ($staffService->toggleStaffStatus($id, $hotelId)) {
            $_SESSION['flash_message'] = [
                'type' => 'success',
                'title' => 'Thành công!',
                'text' => 'Trạng thái nhân viên đã được cập nhật.'
            ];
        } else {
            $_SESSION['flash_message'] = [
                'type' => 'error',
                'title' => 'Lỗi!',
                'text' => 'Không thể cập nhật trạng thái lúc này.'
            ];
        }
        
        // 3. Quay lại trang cũ
        header('Location: ' . URLROOT . '/partner/staff');
        exit;
    }
}