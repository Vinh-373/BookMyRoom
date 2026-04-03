<?php
require_once APPROOT . '/controllers/PartnerController.php';

class BookingController extends PartnerController {
    
    public function __construct() {
        // Chạy construct của cha để kiểm tra login & khởi tạo dữ liệu $this->partnerHotels, $this->activeHotelId
        parent::__construct();
        
        // Kiểm tra nếu chưa chọn khách sạn thì bắt quay về trang Portfolio
        if (!isset($_SESSION['active_hotel_id'])) {
            header('Location: ' . URLROOT . '/partner');
            exit;
        }
    }

    public function index() {
        $hotelId = $this->activeHotelId;
        $bookingService = $this->service('BookingService');
        if (isset($_GET['export']) && $_GET['export'] == 'true') {
                $filters = [
                    'search' => $_GET['search'] ?? '',
                    'status' => $_GET['tab'] ?? 'all',
                    'roomTypeId' => $_GET['roomTypeId'] ?? '',
                    'startDate' => $_GET['startDate'] ?? '',
                    'endDate' => $_GET['endDate'] ?? ''
                ];
                $bookingService->exportToCSV($hotelId, $filters);
                exit; 
            }

        // --- 1. XỬ LÝ CÁC HÀNH ĐỘNG NHANH (Check-in, Checkout, Cancel, Restore) ---
        if (isset($_GET['action']) && isset($_GET['id'])) {
            $action = $_GET['action'];
            $id = $_GET['id'];
            
            // Map action từ JS với trạng thái trong Database
            $statusMap = [
                'checkin'  => 'STAYING',
                'checkout' => 'COMPLETED',
                'cancel'   => 'CANCELLED',
                'restore'  => 'PENDING'
            ];

            if (array_key_exists($action, $statusMap)) {
                $newStatus = $statusMap[$action];
                
                if ($bookingService->updateStatus($id, $newStatus)) {
                    $_SESSION['flash_message'] = [
                        'type' => 'success',
                        'text' => 'Cập nhật trạng thái đơn hàng thành công!'
                    ];
                } else {
                    $_SESSION['flash_message'] = [
                        'type' => 'error',
                        'text' => 'Không thể cập nhật trạng thái đơn hàng.'
                    ];
                }
                
                header("Location: " . URLROOT . "/bookings");
                exit;
            }
        }

        // --- 2. XỬ LÝ BỘ LỌC VÀ HIỂN THỊ DỮ LIỆU ---
        $dateRangeRaw = $_GET['date_range'] ?? '';
        $startDate = '';
        $endDate = '';
        
        if (!empty($dateRangeRaw) && strpos($dateRangeRaw, ' - ') !== false) {
            list($startDate, $endDate) = explode(' - ', $dateRangeRaw);
        }

        $filters = [
            'date_range_raw' => $dateRangeRaw,
            'search'     => $_GET['search'] ?? '',
            'status'     => $_GET['tab'] ?? 'all',
            'roomTypeId' => $_GET['roomTypeId'] ?? '',
            'startDate'  => $startDate,
            'endDate'    => $endDate,
            'sort'       => $_GET['sort'] ?? 'newest',
            'page'       => $_GET['page'] ?? 1
        ];

        $data = $bookingService->getBookingPageData($hotelId, $filters);
        $data['partnerHotels'] = $this->partnerHotels;
        $data['activeHotelId'] = $this->activeHotelId;

        $this->viewPartner('bookings', $data);
    }    
}