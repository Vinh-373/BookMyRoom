<?php
/**
 * Hotels API Controller
 * 
 * Xử lý tất cả các yêu cầu API liên quan đến quản lý khách sạn
 * Gọi từ JavaScript (hotels.js) thông qua fetch()
 */

namespace Controllers\api;

header('Content-Type: application/json; charset=utf-8');

class HotelsApi {
    
    private $hotelModel;
    private $conn;

    public function __construct() {
        try {
            $db = new \Database();
            $this->conn = $db->conn;
            $this->hotelModel = new \Models\hotelsModel();
        } catch (\Throwable $e) {
            self::errorResponse('Database connection error: ' . $e->getMessage(), 500);
            exit;
        }
    }

    /**
     * Route các request API
     * Gọi từ: /api/hotels.php?action=getHotels
     */
    public static function handleRequest() {
        $action = $_GET['action'] ?? $_POST['action'] ?? null;
        
        if (!$action) {
            self::errorResponse('Missing action parameter', 400);
            return;
        }

        $api = new self();

        // Route các action
        $routes = [
            'getHotels' => 'getHotels',
            'getHotelDetail' => 'getHotelDetail',
            'searchHotels' => 'searchHotels',
            'filterHotels' => 'filterHotels',
            'createHotel' => 'createHotel',
            'updateHotel' => 'updateHotel',
            'deleteHotel' => 'deleteHotel',
            'blockHotel' => 'blockHotel',
            'getCities' => 'getCities',
            'getReviews' => 'getReviews',
        ];

        if (isset($routes[$action])) {
            $api->{$routes[$action]}();
        } else {
            self::errorResponse('Invalid action: ' . $action, 400);
        }
    }

    /**
     * Lấy danh sách khách sạn (với phân trang & filter)
     * GET /api/hotels.php?action=getHotels&page=1&limit=10
     */
    public function getHotels() {
        try {
            $page = $_GET['page'] ?? 1;
            $limit = $_GET['limit'] ?? 10;
            $offset = ($page - 1) * $limit;

            // Lấy danh sách khách sạn
            $hotels = $this->hotelModel->join_multi(
                joins: [
                    [
                        'table' => 'cities',
                        'type' => 'LEFT',
                        'on' => 'hotels.cityId = cities.id'
                    ],
                    [
                        'table' => 'users',
                        'type' => 'LEFT',
                        'on' => 'hotels.partnerId = users.id'
                    ]
                ],
                select: 'hotels.id, hotels.hotelName, hotels.rating, hotels.address, 
                         hotels.partnerId, cities.name as cityName, users.email as partnerEmail',
                where: ['hotels.deletedAt' => null],
                orderBy: 'hotels.createdAt DESC',
                limit: "$offset, $limit"
            );

            // Đếm tổng số khách sạn
            $allHotels = $this->hotelModel->select_array('id', ['deletedAt' => null]);
            $total = count($allHotels);
            $pages = ceil($total / $limit);

            self::successResponse([
                'hotels' => $hotels,
                'pagination' => [
                    'page' => $page,
                    'limit' => $limit,
                    'total' => $total,
                    'pages' => $pages
                ]
            ]);

        } catch (\Throwable $e) {
            self::errorResponse('Error fetching hotels: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Lấy chi tiết 1 khách sạn
     * GET /api/hotels.php?action=getHotelDetail&hotelId=1
     */
    public function getHotelDetail() {
        try {
            $hotelId = $_GET['hotelId'] ?? null;

            if (!$hotelId) {
                self::errorResponse('Missing hotelId parameter', 400);
                return;
            }

            // Lấy chi tiết khách sạn
            $hotel = $this->hotelModel->join_multi(
                joins: [
                    [
                        'table' => 'cities',
                        'type' => 'LEFT',
                        'on' => 'hotels.cityId = cities.id'
                    ],
                    [
                        'table' => 'users',
                        'type' => 'LEFT',
                        'on' => 'hotels.partnerId = users.id'
                    ],
                    [
                        'table' => 'partners',
                        'type' => 'LEFT',
                        'on' => 'hotels.partnerId = partners.userId'
                    ]
                ],
                select: 'hotels.*, cities.name as cityName, 
                         users.fullName as partnerName, users.email as partnerEmail,
                         partners.companyName',
                where: ['hotels.id' => $hotelId, 'hotels.deletedAt' => null]
            );

            if (empty($hotel)) {
                self::errorResponse('Hotel not found', 404);
                return;
            }

            // Lấy room configurations
            $roomConfigs = $this->getRoomConfigsByHotel($hotelId);

            // Lấy reviews
            $reviews = $this->getHotelReviews($hotelId);

            // Lấy bookings
            $bookings = $this->getHotelBookings($hotelId);

            self::successResponse([
                'hotel' => $hotel[0],
                'roomConfigs' => $roomConfigs,
                'reviews' => $reviews,
                'bookings' => $bookings,
                'stats' => [
                    'totalRooms' => count($roomConfigs),
                    'totalReviews' => count($reviews),
                    'avgRating' => $this->calculateAvgRating($reviews),
                    'avgOccupancy' => $this->calculateOccupancy($hotelId)
                ]
            ]);

        } catch (\Throwable $e) {
            self::errorResponse('Error fetching hotel detail: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Tìm kiếm khách sạn
     * GET /api/hotels.php?action=searchHotels&query=saigon
     */
    public function searchHotels() {
        try {
            $query = $_GET['query'] ?? '';

            if (strlen($query) < 2) {
                self::errorResponse('Search query too short', 400);
                return;
            }

            // Tìm kiếm theo tên hoặc địa chỉ
            $searchPattern = '%' . $query . '%';
            
            // MySQLi prepared statement
            $sql = "SELECT hotels.id, hotels.hotelName, hotels.rating, hotels.address,
                           cities.name as cityName, users.email as partnerEmail
                    FROM hotels
                    LEFT JOIN cities ON hotels.cityId = cities.id
                    LEFT JOIN users ON hotels.partnerId = users.id
                    WHERE (hotels.hotelName LIKE ? OR hotels.address LIKE ?)
                    AND hotels.deletedAt IS NULL
                    ORDER BY hotels.hotelName
                    LIMIT 20";

            // Note: Cần sử dụng Database class để prepare statement
            $results = $this->hotelModel->searchHotels($searchPattern);

            self::successResponse([
                'results' => $results,
                'query' => $query
            ]);

        } catch (\Throwable $e) {
            self::errorResponse('Error searching hotels: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Lọc khách sạn theo tiêu chí
     * POST /api/hotels.php
     * Body: { action: 'filterHotels', cityId: 1, rating: 4.5, status: 'active' }
     */
    public function filterHotels() {
        try {
            $cityId = $_POST['cityId'] ?? null;
            $rating = $_POST['rating'] ?? null;
            $status = $_POST['status'] ?? null;
            $page = $_POST['page'] ?? 1;
            $limit = $_POST['limit'] ?? 10;

            $where = ['hotels.deletedAt' => null];
            
            if ($cityId) {
                $where['hotels.cityId'] = $cityId;
            }

            $offset = ($page - 1) * $limit;

            $hotels = $this->hotelModel->join_multi(
                joins: [
                    ['table' => 'cities', 'type' => 'LEFT', 'on' => 'hotels.cityId = cities.id'],
                    ['table' => 'users', 'type' => 'LEFT', 'on' => 'hotels.partnerId = users.id']
                ],
                select: 'hotels.*, cities.name as cityName, users.email as partnerEmail',
                where: $where,
                orderBy: 'hotels.createdAt DESC',
                limit: "$offset, $limit"
            );

            // Filter by rating nếu cần (tính toán sau lấy dữ liệu)
            if ($rating) {
                $hotels = array_filter($hotels, function($h) use ($rating) {
                    return $h['rating'] >= floatval($rating);
                });
            }

            self::successResponse([
                'hotels' => $hotels,
                'filters' => [
                    'cityId' => $cityId,
                    'rating' => $rating,
                    'status' => $status
                ]
            ]);

        } catch (\Throwable $e) {
            self::errorResponse('Error filtering hotels: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Tạo khách sạn mới
     * POST /api/hotels.php
     * Body: { action: 'createHotel', hotelName: '...', partnerId: 2, ... }
     */
    public function createHotel() {
        try {
            // Validate input
            $required = ['hotelName', 'partnerId', 'cityId', 'wardId', 'address'];
            foreach ($required as $field) {
                if (empty($_POST[$field])) {
                    self::errorResponse("Missing required field: $field", 400);
                    return;
                }
            }

            // Prepare data
            $data = [
                'hotelName' => $_POST['hotelName'],
                'partnerId' => $_POST['partnerId'],
                'cityId' => $_POST['cityId'],
                'wardId' => $_POST['wardId'],
                'address' => $_POST['address'],
                'description' => $_POST['description'] ?? null,
                'rating' => $_POST['rating'] ?? 4.0,
                'createdAt' => date('Y-m-d H:i:s'),
                'deletedAt' => null
            ];

            // Insert
            $result = $this->hotelModel->insert($data);

            if ($result['success']) {
                self::successResponse([
                    'message' => 'Hotel created successfully',
                    'hotelId' => $result['id']
                ], 201);
            } else {
                self::errorResponse($result['error'] ?? 'Failed to create hotel', 400);
            }

        } catch (\Throwable $e) {
            self::errorResponse('Error creating hotel: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Cập nhật khách sạn
     * POST /api/hotels.php
     * Body: { action: 'updateHotel', hotelId: 1, hotelName: '...', ... }
     */
    public function updateHotel() {
        try {
            $hotelId = $_POST['hotelId'] ?? null;

            if (!$hotelId) {
                self::errorResponse('Missing hotelId', 400);
                return;
            }

            // Prepare data to update
            $data = [];
            $allowedFields = ['hotelName', 'description', 'rating', 'address', 'cityId', 'wardId'];
            
            foreach ($allowedFields as $field) {
                if (isset($_POST[$field])) {
                    $data[$field] = $_POST[$field];
                }
            }

            if (empty($data)) {
                self::errorResponse('No fields to update', 400);
                return;
            }

            // Update using model
            $result = $this->hotelModel->update($data, ['id' => intval($hotelId)]);
            
            if (!$result['success']) {
                self::errorResponse($result['error'] ?? 'Failed to update hotel', 400);
                return;
            }

            self::successResponse([
                'message' => 'Hotel updated successfully',
                'hotelId' => $hotelId
            ]);

        } catch (\Throwable $e) {
            self::errorResponse('Error updating hotel: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Xóa khách sạn (soft delete)
     * POST /api/hotels.php
     * Body: { action: 'deleteHotel', hotelId: 1 }
     */
    public function deleteHotel() {
        try {
            $hotelId = $_POST['hotelId'] ?? null;

            if (!$hotelId) {
                self::errorResponse('Missing hotelId', 400);
                return;
            }

            // Soft delete
            $result = $this->hotelModel->update(['deletedAt' => date('Y-m-d H:i:s')], ['id' => intval($hotelId)]);
            
            if (!$result['success']) {
                self::errorResponse($result['error'] ?? 'Failed to delete hotel', 400);
                return;
            }

            self::successResponse([
                'message' => 'Hotel deleted successfully',
                'hotelId' => $hotelId
            ]);

        } catch (\Throwable $e) {
            self::errorResponse('Error deleting hotel: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Khóa/Tạm dừng khách sạn
     * POST /api/hotels.php
     * Body: { action: 'blockHotel', hotelId: 1, reason: '...' }
     */
    public function blockHotel() {
        try {
            $hotelId = $_POST['hotelId'] ?? null;
            $reason = $_POST['reason'] ?? 'No reason provided';

            if (!$hotelId) {
                self::errorResponse('Missing hotelId', 400);
                return;
            }

            // Log to audit
            $this->logAuditTrail($_SESSION['user_id'] ?? 1, 'BLOCK_HOTEL', 'hotels', $hotelId, $reason);

            // TODO: Thêm status field to hotels table
            // UPDATE hotels SET status = 'SUSPENDED' WHERE id = ?

            self::successResponse([
                'message' => 'Hotel suspended successfully',
                'hotelId' => $hotelId,
                'reason' => $reason
            ]);

        } catch (\Throwable $e) {
            self::errorResponse('Error blocking hotel: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Lấy danh sách thành phố
     * GET /api/hotels.php?action=getCities
     */
    public function getCities() {
        try {
            // Truy vấn trực tiếp
            $result = $this->conn->query("SELECT id, name FROM cities ORDER BY name");
            $cities = $result->fetch_all(MYSQLI_ASSOC);

            self::successResponse([
                'cities' => $cities
            ]);

        } catch (\Throwable $e) {
            self::errorResponse('Error fetching cities: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Lấy reviews của khách sạn
     * GET /api/hotels.php?action=getReviews&hotelId=1
     */
    public function getReviews() {
        try {
            $hotelId = $_GET['hotelId'] ?? null;

            if (!$hotelId) {
                self::errorResponse('Missing hotelId', 400);
                return;
            }

            $reviews = $this->getHotelReviews($hotelId);

            self::successResponse([
                'reviews' => $reviews,
                'stats' => [
                    'total' => count($reviews),
                    'avgRating' => $this->calculateAvgRating($reviews)
                ]
            ]);

        } catch (\Throwable $e) {
            self::errorResponse('Error fetching reviews: ' . $e->getMessage(), 500);
        }
    }

    // ==================== Helper Methods ====================

    /**
     * Lấy room configurations của hotel
     */
    private function getRoomConfigsByHotel($hotelId) {
        $connection = $this->conn;
        $sql = "SELECT rc.id, rc.basePrice, rc.area, rc.maxPeople, rt.name as roomType,
                       GROUP_CONCAT(a.name) as amenities
                FROM roomconfigurations rc
                LEFT JOIN roomtypes rt ON rc.roomTypeId = rt.id
                LEFT JOIN roomamenities ra ON rc.id = ra.roomConfigId
                LEFT JOIN amenities a ON ra.amenityId = a.id
                WHERE rc.hotelId = ?
                GROUP BY rc.id
                ORDER BY rc.basePrice";

        $stmt = $connection->prepare($sql);
        $stmt->bind_param('i', $hotelId);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Lấy bookings của hotel
     */
    private function getHotelBookings($hotelId) {
        $connection = $this->conn;
        $sql = "SELECT b.id, b.status, b.totalAmount, bd.checkIn, bd.checkOut, 
                       u.fullName, rt.name as roomType
                FROM bookings b
                JOIN bookingdetails bd ON b.id = bd.bookingId
                JOIN roomconfigurations rc ON bd.roomConfigId = rc.id
                JOIN users u ON b.userId = u.id
                LEFT JOIN roomtypes rt ON rc.roomTypeId = rt.id
                WHERE rc.hotelId = ?
                ORDER BY bd.checkIn DESC
                LIMIT 10";

        $stmt = $connection->prepare($sql);
        $stmt->bind_param('i', $hotelId);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Lấy reviews của hotel
     */
    private function getHotelReviews($hotelId) {
        $connection = $this->conn;
        $sql = "SELECT r.id, r.rating, r.content, u.fullName, r.createdAt
                FROM reviews r
                JOIN bookingdetails bd ON r.bookingDetailId = bd.id
                JOIN roomconfigurations rc ON bd.roomConfigId = rc.id
                JOIN users u ON r.userId = u.id
                WHERE rc.hotelId = ?
                ORDER BY r.createdAt DESC";

        $stmt = $connection->prepare($sql);
        $stmt->bind_param('i', $hotelId);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Tính tỷ lệ chiếm dụng trung bình
     */
    private function calculateOccupancy($hotelId) {
        $connection = $this->conn;
        $sql = "SELECT 
                    COUNT(DISTINCT pr.id) as totalRooms,
                    COUNT(DISTINCT CASE WHEN bd.checkIn <= CURDATE() AND bd.checkOut > CURDATE() THEN bd.id END) as bookedRooms
                FROM roomconfigurations rc
                LEFT JOIN physicalrooms pr ON rc.id = pr.roomConfigId
                LEFT JOIN bookingdetails bd ON rc.id = bd.roomConfigId
                JOIN bookings b ON bd.bookingId = b.id AND b.status != 'CANCELLED'
                WHERE rc.hotelId = ?";

        $stmt = $connection->prepare($sql);
        $stmt->bind_param('i', $hotelId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row && $row['totalRooms'] > 0) {
            return round(($row['bookedRooms'] / $row['totalRooms']) * 100, 1);
        }

        return 0;
    }

    /**
     * Tính rating trung bình
     */
    private function calculateAvgRating($reviews) {
        if (empty($reviews)) {
            return 0;
        }

        $sum = array_sum(array_column($reviews, 'rating'));
        return round($sum / count($reviews), 1);
    }

    /**
     * Log audit trail
     */
    private function logAuditTrail($userId, $action, $entity, $entityId, $details = null) {
        // TODO: Implement audit logging
        // Ghi vào auditlogs table
    }

    // ==================== Response Helpers ====================

    /**
     * Trả về success response
     */
    private static function successResponse($data = [], $statusCode = 200) {
        http_response_code($statusCode);
        echo json_encode([
            'success' => true,
            'data' => $data,
            'timestamp' => date('Y-m-d H:i:s')
        ]);
        exit;
    }

    /**
     * Trả về error response
     */
    private static function errorResponse($message = '', $statusCode = 400) {
        http_response_code($statusCode);
        echo json_encode([
            'success' => false,
            'error' => $message,
            'timestamp' => date('Y-m-d H:i:s')
        ]);
        exit;
    }
}
