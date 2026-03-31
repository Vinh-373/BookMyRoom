<?php
require_once '../app/controllers/PartnerController.php';
class VoucherController extends PartnerController {
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
        $hotelId = $this->activeHotelId;
        $filters = [
            'search' => $_GET['search'] ?? '',
            'status' => $_GET['status'] ?? 'all',
            'page'   => $_GET['page'] ?? 1
        ];

        $data = $this->service('VoucherService')->getVoucherPageData($hotelId, $filters);
        $data['activePage'] = 'vouchers';
        
        $this->viewPartner('vouchers', $data);
    }

    public function save() {
        $hotelId = $this->activeHotelId;
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['id'])) {
            $voucherModel = $this->model('VoucherModel');
            $currentVoucher = $voucherModel->getById($_POST['id']);
            
            // Kiểm tra logic trước khi lưu
            $today = strtotime(date('Y-m-d'));
            $start = strtotime($currentVoucher['startDate']);
            
            if ($today >= $start) {
                $_SESSION['flash_message'] = ['type' => 'error', 'text' => 'Không thể sửa voucher đã hoặc đang chạy!'];
                header('Location: ' . URLROOT . '/partner/vouchers');
                exit;
            }
        }
        // Gọi service handleSave nếu vượt qua kiểm tra
        $this->service('VoucherService')->handleSave($_POST, $hotelId);
    }

    public function delete($id) {
        $voucherModel = $this->model('VoucherModel');
        $v = $voucherModel->getById($id);
        $today = strtotime(date('Y-m-d'));

        if (strtotime($v['startDate']) <= $today) {
            $_SESSION['flash_message'] = ['type' => 'error', 'text' => 'Không thể xóa voucher đã hoặc đang chạy!'];
        } else {
            $voucherModel->delete($id);
        }
        header('Location: ' . URLROOT . '/partner/vouchers');
        exit;
    }
}