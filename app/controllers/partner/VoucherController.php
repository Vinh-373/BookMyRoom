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
        $data['partnerHotels'] = $this->partnerHotels;
        $data['activeHotelId'] = $this->activeHotelId;
        $data['title'] = "Quản lý Voucher";

        $this->viewPartner('vouchers', $data);
    }

    public function save() {
        $hotelId = $this->activeHotelId;
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Trường hợp chỉnh sửa (có ID)
            if (!empty($_POST['id'])) {
                $voucherModel = $this->model('VoucherModel');
                $currentVoucher = $voucherModel->getById($_POST['id']);
                
                // Kiểm tra logic thời gian
                $today = strtotime(date('Y-m-d'));
                $start = strtotime($currentVoucher['startDate']);
                
                if ($today >= $start) {
                    $_SESSION['flash_message'] = [
                        'type' => 'error', 
                        'title' => 'Lỗi!', 
                        'text' => 'Không thể sửa voucher đã hoặc đang chạy!'
                    ];
                    header('Location: ' . URLROOT . '/vouchers');
                    exit;
                }
            }

            // Gọi service xử lý lưu dữ liệu
            $result = $this->service('VoucherService')->handleSave($_POST, $hotelId);
            
            if ($result) {
                $_SESSION['flash_message'] = [
                    'type' => 'success', 
                    'title' => 'Thành công!', 
                    'text' => 'Dữ liệu Voucher đã được lưu.'
                ];
            } else {
                $_SESSION['flash_message'] = [
                    'type' => 'error', 
                    'title' => 'Thất bại!', 
                    'text' => 'Có lỗi xảy ra trong quá trình lưu dữ liệu.'
                ];
            }
            
            header('Location: ' . URLROOT . '/vouchers');
            exit;
        }
    }

    public function delete($id) {
        $voucherModel = $this->model('VoucherModel');
        $v = $voucherModel->getById($id);
        
        if (!$v) {
            $_SESSION['flash_message'] = [
                'type' => 'error', 
                'title' => 'Lỗi!', 
                'text' => 'Voucher không tồn tại.'
            ];
            header('Location: ' . URLROOT . '/vouchers');
            exit;
        }

        $today = strtotime(date('Y-m-d'));
        $start = strtotime($v['startDate']);

        if ($start <= $today) {
            $_SESSION['flash_message'] = [
                'type' => 'error', 
                'title' => 'Từ chối xóa!', 
                'text' => 'Không thể xóa voucher đã hoặc đang chạy!'
            ];
        } else {
            if ($voucherModel->delete($id)) {
                $_SESSION['flash_message'] = [
                    'type' => 'success', 
                    'title' => 'Đã xóa!', 
                    'text' => 'Voucher đã được gỡ bỏ thành công.'
                ];
            } else {
                $_SESSION['flash_message'] = [
                    'type' => 'error', 
                    'title' => 'Lỗi!', 
                    'text' => 'Lỗi hệ thống khi xóa voucher.'
                ];
            }
        }
        
        header('Location: ' . URLROOT . '/vouchers');
        exit;
    }
}