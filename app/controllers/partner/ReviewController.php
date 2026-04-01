<?php
require_once '../app/controllers/PartnerController.php';
class ReviewController extends PartnerController {
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
            'status' => $_GET['tab'] ?? 'all'
        ];
        
        $reviewService = $this->service('ReviewService');
        $data = $reviewService->getReviewPageData($hotelId, $filters);
        $data['partnerHotels'] = $this->partnerHotels;
        $data['activeHotelId'] = $this->activeHotelId;
        $data['title'] = "Quản lý đánh giá";
        
        $this->viewPartner('reviews', $data);
    }

    public function replyToReview() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $reviewId = $_POST['reviewId'];
            $reply = $_POST['reply'];
            
            $service = $this->service('ReviewService');
            // Giả sử hàm updateReply này lưu vào cột replyContent và replyDate trong bảng reviews
            if ($service->updateReviewReply($reviewId, $reply)) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false]);
            }
        }
    }

    public function exportReviewsCSV() {
        $hotelId = $_SESSION['active_hotel_id'];
        $reviewService = $this->service('ReviewService');
        $reviews = $reviewService->getReviewsByHotel($hotelId, 'all');

        $filename = "Reviews_Hotel_" . $hotelId . "_" . date('Ymd') . ".csv";
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);
        
        $output = fopen('php://output', 'w');
        // Xuất UTF-8 BOM để Excel đọc được tiếng Việt
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Header file CSV
        fputcsv($output, ['ID', 'Khách hàng', 'Số sao', 'Nội dung', 'Ngày gửi', 'Phản hồi']);

        foreach ($reviews as $r) {
            fputcsv($output, [
                $r['id'],
                $r['fullName'],
                $r['rating'],
                $r['content'],
                $r['createdAt'],
                $r['replyContent'] ?? 'Chưa phản hồi'
            ]);
        }
        fclose($output);
        exit;
    }
}