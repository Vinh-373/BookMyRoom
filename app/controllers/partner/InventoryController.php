<?php
require_once '../app/controllers/PartnerController.php';

class InventoryController extends PartnerController {
    public function __construct() {
        parent::__construct();
        if (!isset($_SESSION['active_hotel_id'])) {
            header('Location: ' . URLROOT . '/partner');
            exit;
        }
    }

    public function index() {
        $hotelId = $this->activeHotelId;
        $startDate = $_GET['start_date'] ?? date('Y-m-d');
        $viewDays = (int)($_GET['view_days'] ?? 14);
        $roomTypeId = $_GET['roomTypeId'] ?? null;

        $service = $this->service('InventoryService');
        $data = $service->getInventoryTimeline($hotelId, $startDate, $roomTypeId, $viewDays);

        $roomModel = $this->model('RoomModel');
        $data['roomTypes'] = $roomModel->getUniqueRoomTypes($hotelId);
        $data['title'] = "Lịch kho phòng";
        $data['partnerHotels'] = $this->partnerHotels;
        $data['activeHotelId'] = $this->activeHotelId;
        $this->viewPartner('inventory', $data);
    }

    public function updateInventory() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $service = $this->service('InventoryService');
            $date = $_POST['date'];
            foreach (($_POST['prices'] ?? []) as $configId => $price) {
                $status = $_POST['status'][$configId] ?? 'open';
                $service->updateDailyInventory($configId, $date, $price, $status);
            }
            $_SESSION['flash_message'] = ['type' => 'success', 'title' => 'Thành công!', 'text' => 'Đã cập nhật dữ liệu ngày ' . $date];
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }
    }

    public function processBulkUpdate() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $service = $this->service('InventoryService');
            if ($service->processBulkUpdate($_POST)) {
                $_SESSION['flash_message'] = ['type' => 'success', 'title' => 'Thành công!', 'text' => 'Cập nhật hàng loạt hoàn tất.'];
                header('Location: ' . URLROOT . '/inventory');
                exit;
            }
        }
    }
}