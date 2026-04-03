<?php

class PartnerController extends Controller {

    protected $partnerHotels = [];
    protected $activeHotelId;
    
    public function __construct() {
        // Kiểm tra quyền truy cập Partner tại đây
        // if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Partner') {
        //     header('Location: ' . URLROOT . '/login');
        //     exit;
        // }

        // if ($_SESSION['user_role'] == 'Staff') {
        //     header('Location: ' . URLROOT . '/manage/'.$_SESSION['active_hotel_id']);
        //     exit;
        // }
        // else{
            $this->partnerHotels = $this->service('PortfolioService')->getHotelsByPartner(2);
            $this->activeHotelId = $_SESSION['active_hotel_id'] ?? null;
        // }
    }

    public function manage($id) {
        $_SESSION['active_hotel_id'] = $id;
        foreach ($this->partnerHotels as $hotel) {
            if ($hotel['id'] == $id) {
                $_SESSION['active_hotel_name'] = $hotel['hotelName'];
                break;
            }
        }
        session_write_close();
        header('Location: ' . URLROOT . '/dashboard');
        exit;
    }

    public function index() {
        $partnerId = 2;
        $portfolioService = $this->service('PortfolioService');
        $data = $portfolioService->getDashboardData($partnerId);
        $data['partnerHotels']= $this->partnerHotels;
        $data['hideSidebar'] = true;
        $this->viewPartner('globalportfoliodashboard', $data);
    }

    public function addHotel() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $portfolioService = $this->service('PortfolioService');
            
            // Lấy ID người dùng từ Session
            $partnerId = 2;//$_SESSION['user_id'];
            
            $result = $portfolioService->createNewProperty($_POST, $partnerId);

            if ($result) {
                $_SESSION['flash_message'] = [
                    'type'  => 'success',
                    'title' => 'Thành công!',
                    'text'  => 'Khách sạn mới đã được đăng ký vào hệ thống.'
                ];
            } else {
                $_SESSION['flash_message'] = [
                    'type'  => 'error',
                    'title' => 'Thất bại!',
                    'text'  => 'Có lỗi xảy ra khi tạo khách sạn, vui lòng thử lại.'
                ];
            }
            
            header('Location: ' . URLROOT . '/partner');
            exit;
        }
    }

    public function editHotel($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $portfolioService = $this->service('PortfolioService');
            
            $partnerId = 2;//$_SESSION['user_id'];
            $hotel = $portfolioService->getHotelForEdit($id, $partnerId);

            if (!$hotel) {
                $_SESSION['flash_message'] = [
                    'type'  => 'error',
                    'title' => 'Từ chối!',
                    'text'  => 'Bạn không có quyền chỉnh sửa khách sạn này.'
                ];
            } else {
                $result = $portfolioService->updateHotelInfo($id, $_POST);

                if ($result) {
                    $_SESSION['flash_message'] = [
                        'type'  => 'success',
                        'title' => 'Cập nhật thành công',
                        'text'  => 'Thông tin khách sạn đã được lưu lại.'
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
            $userId = 2;//$_SESSION['user_id'];
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
}