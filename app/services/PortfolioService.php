<?php
require_once __DIR__ . '/../core/Service.php';

class PortfolioService extends Service {
    private $hotelModel;

    public function __construct() {
        parent::__construct();
        // Nạp Model thông qua hàm model() của Base Service (nếu bạn đã viết) 
        // hoặc require thủ công như bạn đang làm.
        require_once '../app/models/HotelModel.php';
        $this->hotelModel = new HotelModel();
    }

    /**
     * Lấy dữ liệu tổng hợp cho trang Global Portfolio Dashboard
     */
    public function getDashboardData($partnerId) {
        // 1. Lấy danh sách khách sạn kèm số lượng phòng (Sử dụng model đã update)
        $hotels = $this->hotelModel->getHotelsByPartner($partnerId);

        // 2. Lấy thông số tổng quát cho toàn chuỗi (MTD - Month to Date)
        $rawRevenue = $this->hotelModel->getChainTotalRevenue($partnerId);
        $totalBookings = $this->hotelModel->getChainTotalBookings($partnerId);

        // 3. Tính toán Portfolio Health (Sức khỏe danh mục)
        // Thay vì để 82% tĩnh, chúng ta tính dựa trên rating trung bình của tất cả hotel
        $portfolioHealth = $this->calculatePortfolioHealth($hotels);

        return [
            'hotels'           => $hotels,
            'chain_revenue'    => number_format($rawRevenue), // VD: 1,482,900
            'total_bookings'   => number_format($totalBookings),
            'portfolio_health' => $portfolioHealth
        ];
    }

    /**
     * Logic tính toán sức khỏe danh mục dựa trên Rating hoặc Occupancy
     */
    private function calculatePortfolioHealth($hotels) {
        if (empty($hotels)) return 0;

        $totalRating = 0;
        $count = count($hotels);

        foreach ($hotels as $hotel) {
            $totalRating += $hotel['rating'] ?? 0;
        }

        // Quy đổi rating (thang 5) sang tỷ lệ phần trăm (thang 100)
        // Ví dụ: average 4.1 star -> 82%
        $averageRating = $totalRating / $count;
        return round(($averageRating / 5) * 100);
    }

    public function getHotelsByPartner($partnerId){
        return $this->hotelModel->getHotelsByPartner($partnerId);
    } 
}