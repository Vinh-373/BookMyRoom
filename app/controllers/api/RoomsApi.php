<?php
// Set JSON header
header('Content-Type: application/json; charset=utf-8');

// Load models if not already loaded
if (!class_exists('Models\roomsModel')) {
    require_once __DIR__ . '/../../models/roomsModel.php';
}

class RoomsApi {
    private $roomModel;

    public function __construct() {
        try {
            $this->roomModel = new \Models\roomsModel();
        } catch (\Exception $e) {
            self::errorResponse('Model initialization error: ' . $e->getMessage(), 500);
            exit;
        }
    }

    /**
     * Handle API requests
     */
    public static function handleRequest() {
        $api = new self();
        $action = $_REQUEST['action'] ?? null;

        try {
            switch ($action) {
                case 'getRooms':
                    $api->getRooms();
                    break;
                case 'getRoomStats':
                    $api->getRoomStats();
                    break;
                case 'searchRooms':
                    $api->searchRooms();
                    break;
                case 'filterRooms':
                    $api->filterRooms();
                    break;
                case 'getRoomDetail':
                    $api->getRoomDetail();
                    break;
                case 'updateRoomStatus':
                    $api->updateRoomStatus();
                    break;
                case 'getHotelsForFilter':
                    $api->getHotelsForFilter();
                    break;
                case 'getRoomTypesForFilter':
                    $api->getRoomTypesForFilter();
                    break;
                default:
                    self::errorResponse('Invalid action', 400);
            }
        } catch (\Throwable $e) {
            self::errorResponse('Error: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get paginated rooms list
     */
    private function getRooms() {
        $page = intval($_GET['page'] ?? 1);
        $limit = intval($_GET['limit'] ?? 10);

        $data = $this->roomModel->getRooms($page, $limit);
        self::successResponse($data);
    }

    /**
     * Get room statistics
     */
    private function getRoomStats() {
        $stats = $this->roomModel->getRoomStats();
        self::successResponse($stats);
    }

    /**
     * Search rooms by hotel or room number
     */
    private function searchRooms() {
        $query = $_GET['query'] ?? '';

        if (empty($query)) {
            self::errorResponse('Missing search query', 400);
            return;
        }

        $results = $this->roomModel->searchRooms($query);
        self::successResponse(['rooms' => $results]);
    }

    /**
     * Filter rooms by criteria
     */
    private function filterRooms() {
        $hotelId = !empty($_POST['hotelId']) ? intval($_POST['hotelId']) : null;
        $roomTypeId = !empty($_POST['roomTypeId']) ? intval($_POST['roomTypeId']) : null;
        $status = $_POST['status'] ?? null;
        $page = intval($_POST['page'] ?? 1);
        $limit = intval($_POST['limit'] ?? 10);

        $data = $this->roomModel->filterRooms($hotelId, $roomTypeId, $status, $page, $limit);
        self::successResponse($data);
    }

    /**
     * Get room detail
     */
    private function getRoomDetail() {
        $roomId = intval($_GET['roomId'] ?? 0);

        if (!$roomId) {
            self::errorResponse('Missing roomId', 400);
            return;
        }

        $detail = $this->roomModel->getRoomDetail($roomId);

        if (empty($detail)) {
            self::errorResponse('Room not found', 404);
            return;
        }

        self::successResponse($detail);
    }

    /**
     * Update room status
     */
    private function updateRoomStatus() {
        $roomId = intval($_POST['roomId'] ?? 0);
        $status = $_POST['status'] ?? '';

        if (!$roomId || !$status) {
            self::errorResponse('Missing roomId or status', 400);
            return;
        }

        $result = $this->roomModel->updateRoomStatus($roomId, $status);

        if (!$result['success']) {
            self::errorResponse($result['error'] ?? 'Failed to update room', 400);
            return;
        }

        self::successResponse(['message' => 'Room status updated successfully', 'roomId' => $roomId]);
    }

    /**
     * Get hotels for filter dropdown
     */
    private function getHotelsForFilter() {
        $hotels = $this->roomModel->getHotelsForFilter();
        self::successResponse(['hotels' => $hotels]);
    }

    /**
     * Get room types for filter dropdown
     */
    private function getRoomTypesForFilter() {
        $types = $this->roomModel->getRoomTypesForFilter();
        self::successResponse(['types' => $types]);
    }

    /**
     * Send success response
     */
    private static function successResponse($data, $code = 200) {
        http_response_code($code);
        echo json_encode([
            'success' => true,
            'data' => $data
        ]);
        exit;
    }

    /**
     * Send error response
     */
    private static function errorResponse($message, $code = 400) {
        http_response_code($code);
        echo json_encode([
            'success' => false,
            'error' => $message
        ]);
        exit;
    }
}
