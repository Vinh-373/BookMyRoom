<?php
require_once APPROOT . '/controllers/PartnerController.php';
class FinanceController extends PartnerController {
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
        $financeService = $this->service('FinanceService');

        // Lấy khoảng thời gian từ bộ lọc (mặc định là tháng này)
        $period = $_GET['period'] ?? 'this_month';

        // Lấy dữ liệu thống kê
        $data['stats'] = $financeService->getFinanceStats($hotelId, $period);
        
        // Dữ liệu cho biểu đồ
        $data['chartData'] = $financeService->getRevenueByRoomType($hotelId, $period);
        
        // Lịch sử giao dịch
        $data['payouts'] = $financeService->getRecentPayouts($hotelId, 10);
        
        $data['activePeriod'] = $period;

        // Tính max_revenue để vẽ tỷ lệ chiều cao biểu đồ cột
        $amounts = array_column($data['chartData'] ?: [], 'amount');
        $data['stats']['max_revenue'] = (!empty($amounts) && max($amounts) > 0) ? max($amounts) : 1;
        $data['partnerHotels'] = $this->partnerHotels;
        $data['activeHotelId'] = $this->activeHotelId;
        $this->viewPartner('reports', $data);
    }

    public function exportFinanceCSV() {
        $hotelId = $this->activeHotelId;
        $period = $_GET['period'] ?? 'this_month';
        
        $financeService = $this->service('FinanceService');
        $financeService->exportCSV($hotelId, $period);
        exit;
    }

    public function transactions() {
        $hotelId = $this->activeHotelId;
        $service = $this->service('FinanceService');

        $filters = [
            'status' => $_GET['status'] ?? 'all',
            'method' => $_GET['method'] ?? 'all',
            'page'   => $_GET['page'] ?? 1
        ];

        $data = $service->getTransactionPageData($hotelId, $filters);
        $data['partnerHotels'] = $this->partnerHotels;
        $data['activeHotelId'] = $this->activeHotelId;
        $this->viewPartner('transactions', $data);
    }
}