<?php
// require_once __DIR__ . '/../../core/Service.php';

class PortfolioService extends Service {
    private $hotelModel;

    public function __construct() {
        parent::__construct();
        // Nạp Model thông qua hàm model() của Base Service (nếu bạn đã viết) 
        // hoặc require thủ công như bạn đang làm.
        require_once APPROOT . '/models/partner/HotelModel.php';
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
        $cities = $this->hotelModel->getCities();

        return [
            'hotels'           => $hotels,
            'chain_revenue'    => number_format($rawRevenue), // VD: 1,482,900
            'total_bookings'   => number_format($totalBookings),
            'portfolio_health' => $portfolioHealth,
            'cities'           => $cities
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

    public function createNewProperty($formData, $partnerId, $imageData = []) {
        $hotelModel = $this->model('HotelModel');

        // 1. Làm sạch và chuẩn bị dữ liệu văn bản
        $data = [
            'partnerId'   => $partnerId,
            'hotelName'   => htmlspecialchars(trim($formData['hotelName'])),
            'description' => htmlspecialchars(trim($formData['description'] ?? '')),
            'cityId'      => (int)$formData['cityId'],
            'wardId'      => (int)$formData['wardId'],
            'address'     => htmlspecialchars(trim($formData['address'])),
            'status'      => 'ACTIVE', // Mặc định khách sạn mới sẽ ở trạng thái hoạt động
            'createdAt'   => date('Y-m-d H:i:s')
        ];

        // 2. Chèn thông tin khách sạn và lấy ID vừa tạo
        $hotelId = $hotelModel->insert($data);

        // 3. Nếu chèn khách sạn thành công và có dữ liệu ảnh
        if ($hotelId && !empty($imageData)) {
            foreach ($imageData as $img) {
                // Đảm bảo URL không trống trước khi lưu
                if (!empty($img['url'])) {
                    $hotelModel->addHotelImage(
                        $hotelId, 
                        trim($img['url']), 
                        (int)$img['isPrimary']
                    );
                }
            }
        }

        return $hotelId; // Trả về ID khách sạn để Controller biết đã thành công
    }

    public function getLocations() {
        return $this->model('HotelModel')->getCities();
    }

    public function requestToStop($hotelId) {
        $hotelModel = $this->model('HotelModel');

        // 1. Kiểm tra đơn hàng tồn đọng
        if ($hotelModel->hasActiveBookings($hotelId)) {
            return [
                'success' => false,
                'message' => 'Không thể dừng! Bạn còn đơn hàng chưa hoàn tất.'
            ];
        }

        // 2. Chuyển trạng thái sang Chờ xét duyệt
        $result = $hotelModel->updateStatus($hotelId, 'PENDING_STOP');
        
        return [
            'success' => $result,
            'message' => $result ? 'Yêu cầu đã được gửi, vui lòng chờ Admin duyệt.' : 'Lỗi hệ thống.'
        ];
    }

    public function getHotelForEdit($id, $partnerId) {
        $hotel = $this->model('HotelModel')->getById($id);
        
        // Kiểm tra khách sạn có tồn tại và thuộc về Partner này không
        if (!$hotel || $hotel['partnerId'] != $partnerId) {
            return null;
        }
        return $hotel;
    }

    public function updateHotelInfo($id, $formData, $imageData = []) {
        // 1. Chuẩn hóa dữ liệu văn bản
        $cleanData = [
            'hotelName'   => htmlspecialchars(trim($formData['hotelName'])),
            'description' => htmlspecialchars(trim($formData['description'] ?? '')),
            'cityId'      => (int)$formData['cityId'],
            'wardId'      => (int)$formData['wardId'],
            'address'     => htmlspecialchars(trim($formData['address']))
        ];

        $hotelModel = $this->model('HotelModel');

        // 2. Cập nhật thông tin cơ bản của khách sạn
        $isUpdated = $hotelModel->update($id, $cleanData);

        // 3. Xử lý cập nhật hình ảnh (Ảnh mạng)
        // Chiến thuật: Xóa hết ảnh cũ của khách sạn này và chèn bộ ảnh mới từ Modal
        if (!empty($imageData)) {
            $hotelModel->deleteImagesByHotel($id); // Hàm xóa ảnh cũ trong model
            foreach ($imageData as $img) {
                $hotelModel->addHotelImage($id, $img['url'], $img['isPrimary']);
            }
            $isUpdated = true; // Đánh dấu có thay đổi nếu chỉ thay đổi ảnh
        }

        return $isUpdated;
    }

    public function updatePartnerProfile($userId, $data, $fileName) {
        $userModel = $this->model('UserModel');
        
        $phone = htmlspecialchars(trim($data['phone']));
        $fullName = htmlspecialchars(trim($data['fullName']));

        if (empty($fullName) || empty($phone)) {
            return ['success' => false, 'message' => 'Họ tên và số điện thoại không được để trống.'];
        }

        if (!preg_match('/^0\d{9}$/', $phone)) {
            return ['success' => false, 'message' => 'Định dạng số điện thoại không hợp lệ.'];
        }

        if ($userModel->checkPhoneExists($phone, $userId)) {
            return ['success' => false, 'message' => 'Số điện thoại này đã được sử dụng.'];
        }

        if ($userModel->checkPhoneExists($phone, $userId)) {
            return ['success' => false, 'message' => 'Số điện thoại này đã được sử dụng bởi tài khoản khác.'];
        }

        $updateData = [
            'fullName' => $fullName,
            'phone'    => $phone,
            'avatar'   => $fileName
        ];

        $isUpdated = $userModel->saveUserChanges($userId, $updateData);

        if ($isUpdated) {
            $_SESSION['user_name'] = $updateData['fullName'];
            $_SESSION['user_phone'] = $updateData['phone'];
            if ($fileName) $_SESSION['user_avatar'] = $fileName;
            return ['success' => true];
        }

        return ['success' => false, 'message' => 'Lỗi hệ thống khi cập nhật dữ liệu.'];
    }
}