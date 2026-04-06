<?php

class PartnerController extends Controller1 {

    protected $partnerHotels = [];
    protected $activeHotelId;
    
    public function __construct() {
        if (!isset($_SESSION['user']['id'])) {
            header('Location: ' . URLROOT . '/login');
            exit;
        }

        $partnerId = $_SESSION['user']['id'];
        $this->activeHotelId = $_SESSION['active_hotel_id'] ?? null;
        if ($_SESSION['user']['role'] !== 'staff') {
            $this->partnerHotels = $this->service('PortfolioService')->getHotelsByPartner($partnerId);
        }
    }

    public function manage($id) {
        $found = false;
        foreach ($this->partnerHotels as $hotel) {
            if ($hotel['id'] == $id) {
                $_SESSION['active_hotel_id'] = $id;
                $_SESSION['active_hotel_name'] = $hotel['hotelName'];
                $found = true;
                break;
            }
        }

        if (!$found && $_SESSION['user']['role'] !== 'staff') {
            die("Bạn không có quyền quản lý khách sạn này.");
        }

        header('Location: ' . URLROOT . '/dashboard');
        exit;
    }


    public function index() {
        $partnerId = $_SESSION['user']['id'];
        $portfolioService = $this->service('PortfolioService');
        $data = $portfolioService->getDashboardData($partnerId);
        $data['partnerHotels']= $this->partnerHotels;
        $data['hideSidebar'] = true;
        $this->viewPartner('globalportfoliodashboard', $data);
    }

    public function addHotel() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $portfolioService = $this->service('PortfolioService');
            $partnerId = $_SESSION['user']['id'];
            
            // Lấy dữ liệu cơ bản + dữ liệu ảnh JSON
            $data = $_POST;
            $imageData = isset($_POST['image_data']) ? json_decode($_POST['image_data'], true) : [];

            // Gọi service xử lý tạo khách sạn và lưu ảnh
            $result = $portfolioService->createNewProperty($data, $partnerId, $imageData);

            if ($result) {
                $_SESSION['flash_message'] = [
                    'type'  => 'success',
                    'title' => 'Thành công!',
                    'text'  => 'Khách sạn mới đã được đăng ký kèm danh sách hình ảnh.'
                ];
            } else {
                $_SESSION['flash_message'] = [
                    'type'  => 'error',
                    'title' => 'Thất bại!',
                    'text'  => 'Có lỗi xảy ra, vui lòng kiểm tra lại thông tin.'
                ];
            }
            
            header('Location: ' . URLROOT . '/partner');
            exit;
        }
    }

    public function editHotel($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $portfolioService = $this->service('PortfolioService');
            $partnerId = $_SESSION['user']['id'];
            
            // Kiểm tra quyền sở hữu trước khi sửa
            $hotel = $portfolioService->getHotelForEdit($id, $partnerId);

            if (!$hotel) {
                $_SESSION['flash_message'] = [
                    'type'  => 'error',
                    'title' => 'Từ chối!',
                    'text'  => 'Bạn không có quyền chỉnh sửa khách sạn này.'
                ];
            } else {
                $data = $_POST;
                $imageData = isset($_POST['image_data']) ? json_decode($_POST['image_data'], true) : [];

                // Truyền ID và dữ liệu ảnh xuống service
                $result = $portfolioService->updateHotelInfo($id, $data, $imageData);

                if ($result) {
                    $_SESSION['flash_message'] = [
                        'type'  => 'success',
                        'title' => 'Cập nhật thành công',
                        'text'  => 'Thông tin và hình ảnh khách sạn đã được lưu lại.'
                    ];
                } else {
                    $_SESSION['flash_message'] = [
                        'type'  => 'info',
                        'title' => 'Thông báo',
                        'text'  => 'Không có thay đổi nào được thực hiện.'
                    ];
                }
            }

            header('Location: ' . URLROOT . '/partner');
            exit;
        }
    }

    public function getWardsAjax($cityId) {
        $wards = $this->model('HotelModel')->getWardsByCity($cityId);
        echo json_encode($wards);
    }

    public function requestStop($hotelId) {
        $service = $this->service('PortfolioService');
        $result = $service->requestToStop($hotelId);

        $_SESSION['flash_message'] = [
            'type' => $result['success'] ? 'success' : 'error',
            'title' => $result['success'] ? 'Đã gửi yêu cầu' : 'Từ chối',
            'text' => $result['message']
        ];

        header('Location: ' . URLROOT . '/partner');
        exit;
    }

    public function updateProfileAjax() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $portfolioService = $this->service('PortfolioService');
            $userId = $_SESSION['user']['id'];
            $fileName = null;

            // Xử lý upload ảnh vật lý tại Controller
            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
                $targetDir = "public/images/avatars/";
                $extension = pathinfo($_FILES["avatar"]["name"], PATHINFO_EXTENSION);
                $fileName = "user_" . $userId . "_" . time() . "." . $extension;
                
                if (!move_uploaded_file($_FILES["avatar"]["tmp_name"], $targetDir . $fileName)) {
                    echo json_encode(['success' => false, 'message' => 'Lỗi upload file.']);
                    exit;
                }
            }

            // Chuyển dữ liệu sang Service xử lý logic và DB
            $result = $portfolioService->updatePartnerProfile($userId, $_POST, $fileName);

            header('Content-Type: application/json');
            echo json_encode($result);
            exit;
        }
    }

    public function logout(){
        session_destroy();
        header('Location: ' . URLROOT . '/auth/login');
    }
}