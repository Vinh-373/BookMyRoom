<?php
require_once '../app/controllers/PartnerController.php';
class InventoryController extends PartnerController {
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
        $hotelId= $this->activeHotelId;
        $startDate = $_GET['start_date'] ?? date('Y-m-d');
        $viewDays  = (int)($_GET['view_days'] ?? 14);
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
            $date = $_POST['date'];
            $prices = $_POST['prices']; 
            $statuses = $_POST['status'];

            $service = $this->service('InventoryService');

            foreach ($prices as $configId => $price) {
                $status = $statuses[$configId];
                // Gọi Service xử lý thay vì gọi trực tiếp Model
                $service->updateDailyInventory($configId, $date, $price, $status);
            }

            // Redirect kèm thông báo thành công (giả định bạn có flash message)
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }
    }

    public function processBulkUpdate() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'startDate'     => $_POST['startDate'],
                'endDate'       => $_POST['endDate'],
                'weekdays'      => $_POST['weekdays'] ?? [],
                'roomConfigIds' => $_POST['roomConfigIds'] ?? [],
                'bulkPrice'     => $_POST['bulkPrice'],
                'bulkStatus'    => $_POST['bulkStatus']
            ];

            $service = $this->service('InventoryService');
            if ($service->processBulkUpdate($data)) {
                // Chuyển hướng về trang inventory kèm thông báo
                header('Location: ' . URLROOT . '/partner/inventory?msg=success');
            }
        }
    }
}