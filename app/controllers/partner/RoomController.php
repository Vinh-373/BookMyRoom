<?php
require_once '../app/controllers/PartnerController.php';
class RoomController extends PartnerController {
    public function __construct() {
        parent::__construct();
        
        if (!isset($_SESSION['active_hotel_id'])) {
            header('Location: ' . URLROOT . '/index');
            exit;
        }
    }
    public function index() {
        $hotelId= $this->activeHotelId;

        $filters = [
            'roomTypeId' => $_GET['roomTypeId'] ?? null,
        ];

        $data = $this->service('RoomService')->getRoomPageData($hotelId, $filters);
        $data['partnerHotels'] = $this->partnerHotels;
        $data['activeHotelId'] = $this->activeHotelId;
        $this->viewPartner('rooms', $data);
    }

    public function addRoom() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'hotelId'    => $_SESSION['active_hotel_id'],
                'roomTypeId' => $_POST['roomTypeId'],
                'basePrice'  => $_POST['basePrice'],
                'area'       => $_POST['area'],
                'maxPeople'  => $_POST['maxPeople']
            ];

            if ($this->service('RoomService')->handleAddRoom($data)) {
                $_SESSION['flash_message'] = [
                    'type' => 'success',
                    'title' => 'Thành công!',
                    'text' => 'Đã thêm loại phòng mới vào khách sạn.'
                ];
            }
            header('Location: ' . URLROOT . '/rooms');
            exit();
        }
    }

    public function updateRoom() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $data = [
                'basePrice' => $_POST['basePrice'],
                'maxPeople' => $_POST['maxPeople'],
                'area'      => $_POST['area']
            ];

            $result = $this->service('RoomService')->handleUpdateRoom($id, $data);
            
            if ($result) {
                // Thiết lập thông báo thành công vào Session
                $_SESSION['flash_message'] = [
                    'type' => 'success',
                    'title' => 'Cập nhật thành công!',
                    'text' => 'Thông tin cấu hình phòng đã được lưu.'
                ];
            } else {
                $_SESSION['flash_message'] = [
                    'type' => 'error',
                    'title' => 'Lỗi!',
                    'text' => 'Không thể cập nhật dữ liệu.'
                ];
            }
            header('Location: ' . URLROOT . '/rooms');
            exit();
        }
    }

    public function deleteRoom($id) {
        $result = $this->service('RoomService')->handleDeleteRoom($id);
        
        if ($result) {
            $_SESSION['flash_message'] = [
                'type' => 'success',
                'title' => 'Đã xóa!',
                'text' => 'Cấu hình phòng đã được loại bỏ.'
            ];
        }
        header('Location: ' . URLROOT . '/rooms');
        exit();
    }

    public function addPhysicalRoom() {
        $hotelId    = $_SESSION['active_hotel_id'];
        $configId   = $_GET['configId'];
        $roomNumber = $_GET['roomNumber'];
        $floor      = $_GET['floor'];

        $data = [
            'configId' => $configId,
            'number'   => $roomNumber,
            'floor'    => $floor
        ];

        $result = $this->service('RoomService')->addPhysicalRoom($hotelId, $data);

        if ($result === "exists_in_hotel") {
            $_SESSION['flash_message'] = [
                'type' => 'error',
                'title' => 'Trùng số phòng!',
                'text' => "Số phòng $roomNumber đã tồn tại trong khách sạn này ở một loại phòng khác."
            ];
        } elseif ($result) {
            $_SESSION['flash_message'] = [
                'type' => 'success',
                'title' => 'Thành công!',
                'text' => "Đã thêm phòng $roomNumber vào tầng $floor."
            ];
        } else {
            $_SESSION['flash_message'] = [
                'type' => 'error',
                'title' => 'Lỗi hệ thống!',
                'text' => "Không thể thêm phòng vào lúc này."
            ];
        }

        header('Location: ' . URLROOT . '/rooms');
        exit();
    }

    public function deletePhysicalRoom($id) {
        $result = $this->service('RoomService')->deletePhysicalRoom($id);
        
        if ($result) {
            $_SESSION['flash_message'] = [
                'type' => 'success',
                'title' => 'Đã xóa!',
                'text' => 'Phòng này đã được loại bỏ.'
            ];
        }
        header('Location: ' . URLROOT . '/rooms');
        exit();
    }
}