<?php
namespace Controllers\api;

// Set JSON header
header('Content-Type: application/json; charset=utf-8');

// Load models if not already loaded
if (!class_exists('Models\bookingsModel')) {
    require_once __DIR__ . '/../../models/bookingsModel.php';
}

class BookingsApi {
    private $bookingsModel;

    public function __construct() {
        $this->bookingsModel = new \Models\bookingsModel();
    }

    /**
     * Handle API requests
     */
    public static function handleRequest() {
        header('Content-Type: application/json');
        
        try {
            $api = new self();
            $action = $_REQUEST['action'] ?? '';

            switch ($action) {
                case 'getBookings':
                    $api->getBookings();
                    break;

                case 'getBookingStats':
                    $api->getBookingStats();
                    break;

                case 'searchBookings':
                    $api->searchBookings();
                    break;

                case 'filterBookings':
                    $api->filterBookings();
                    break;

                case 'getBookingDetail':
                    $api->getBookingDetail();
                    break;

                case 'updateBookingStatus':
                    $api->updateBookingStatus();
                    break;

                default:
                    self::errorResponse('Invalid action: ' . $action);
            }
        } catch (\Throwable $e) {
            error_log("API Error: " . $e->getMessage());
            self::errorResponse($e->getMessage());
        }
    }

    /**
     * Get paginated bookings
     */
    private function getBookings() {
        $page = intval($_GET['page'] ?? 1);
        $limit = intval($_GET['limit'] ?? 10);

        $data = $this->bookingsModel->getBookings($page, $limit);
        
        // Transform field names for frontend compatibility
        if (!empty($data['bookings'])) {
            $data['bookings'] = array_map(function($booking) {
                return [
                    'id' => $booking['id'],
                    'customerName' => $booking['customerName'],
                    'email' => $booking['customerEmail'] ?? '',
                    'phone' => $booking['customerPhone'] ?? '',
                    'bookingDate' => $booking['createdAt'],
                    'checkInDate' => $booking['checkInDate'],
                    'checkOutDate' => $booking['checkOutDate'],
                    'totalAmount' => $booking['totalAmount'],
                    'source' => $booking['source'],
                    'status' => $booking['status'],
                    'hotelNames' => $booking['hotelNames'],
                    'roomTypes' => $booking['roomTypes'],
                    'totalRooms' => $booking['totalRooms']
                ];
            }, $data['bookings']);
        }
        
        self::successResponse($data);
    }

    /**
     * Get booking statistics
     */
    private function getBookingStats() {
        $stats = $this->bookingsModel->getBookingStats();
        self::successResponse($stats);
    }

    /**
     * Search bookings
     */
    private function searchBookings() {
        $query = $_GET['query'] ?? '';
        if (empty($query)) {
            self::errorResponse('Query parameter required');
            return;
        }

        $results = $this->bookingsModel->searchBookings($query);
        
        // Transform field names
        if (!empty($results)) {
            $results = array_map(function($booking) {
                return [
                    'id' => $booking['id'],
                    'customerName' => $booking['customerName'],
                    'email' => $booking['customerEmail'] ?? '',
                    'phone' => $booking['customerPhone'] ?? '',
                    'bookingDate' => $booking['createdAt'],
                    'checkInDate' => $booking['checkInDate'],
                    'checkOutDate' => $booking['checkOutDate'],
                    'totalAmount' => $booking['totalAmount'],
                    'source' => $booking['source'],
                    'status' => $booking['status'],
                    'hotelNames' => $booking['hotelNames'],
                    'roomTypes' => $booking['roomTypes'],
                    'totalRooms' => $booking['totalRooms']
                ];
            }, $results);
        }
        
        self::successResponse(['bookings' => $results]);
    }

    /**
     * Filter bookings
     */
    private function filterBookings() {
        $status = $_POST['status'] ?? null;
        $source = $_POST['source'] ?? null;
        $fromDate = $_POST['fromDate'] ?? null;
        $toDate = $_POST['toDate'] ?? null;
        $page = intval($_POST['page'] ?? 1);
        $limit = intval($_POST['limit'] ?? 10);

        $data = $this->bookingsModel->filterBookings($status, $source, $fromDate, $toDate, $page, $limit);
        
        // Transform field names
        if (!empty($data['bookings'])) {
            $data['bookings'] = array_map(function($booking) {
                return [
                    'id' => $booking['id'],
                    'customerName' => $booking['customerName'],
                    'email' => $booking['customerEmail'] ?? '',
                    'phone' => $booking['customerPhone'] ?? '',
                    'bookingDate' => $booking['createdAt'],
                    'checkInDate' => $booking['checkInDate'],
                    'checkOutDate' => $booking['checkOutDate'],
                    'totalAmount' => $booking['totalAmount'],
                    'source' => $booking['source'],
                    'status' => $booking['status'],
                    'hotelNames' => $booking['hotelNames'],
                    'roomTypes' => $booking['roomTypes'],
                    'totalRooms' => $booking['totalRooms']
                ];
            }, $data['bookings']);
        }
        
        self::successResponse($data);
    }

    /**
     * Get booking detail
     */
    private function getBookingDetail() {
        $bookingId = intval($_GET['bookingId'] ?? 0);
        if ($bookingId <= 0) {
            self::errorResponse('Invalid booking ID');
            return;
        }

        $detail = $this->bookingsModel->getBookingDetail($bookingId);
        if (empty($detail)) {
            self::errorResponse('Booking not found');
            return;
        }

        // Transform field names
        $detail = [
            'id' => $detail['id'],
            'customerName' => $detail['customerName'],
            'email' => $detail['customerEmail'] ?? '',
            'phone' => $detail['customerPhone'] ?? '',
            'address' => $detail['customerAddress'] ?? '',
            'bookingDate' => $detail['createdAt'],
            'totalAmount' => $detail['totalAmount'],
            'platformFee' => $detail['platformFee'] ?? 0,
            'partnerRevenue' => $detail['partnerRevenue'] ?? 0,
            'source' => $detail['source'],
            'status' => $detail['status'],
            'details' => $detail['details'] ?? []
        ];

        self::successResponse($detail);
    }

    /**
     * Update booking status
     */
    private function updateBookingStatus() {
        $bookingId = intval($_POST['bookingId'] ?? 0);
        $status = $_POST['status'] ?? '';

        if ($bookingId <= 0 || empty($status)) {
            self::errorResponse('Invalid booking ID or status');
            return;
        }

        $result = $this->bookingsModel->updateBookingStatus($bookingId, $status);
        if ($result['success']) {
            self::successResponse($result);
        } else {
            self::errorResponse($result['message']);
        }
    }

    /**
     * Send success response
     */
    private static function successResponse($data) {
        echo json_encode([
            'success' => true,
            'data' => $data
        ]);
        exit;
    }

    /**
     * Send error response
     */
    private static function errorResponse($message) {
        echo json_encode([
            'success' => false,
            'error' => $message
        ]);
        exit;
    }
}

// Handle the API request
BookingsApi::handleRequest();
