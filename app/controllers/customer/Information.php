<?php
namespace Controllers\customer;
use Controller;

require_once __DIR__ . '/../../services/informationService.php';
class Information extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = new \InformationService();
    }
    public function index()
    {
        $information = $this->service->getInformationUser(11); // Thay 11 bằng userId thực tế từ session hoặc tham số
        $viewFile = './app/views/customer/informationPage.php';
        $this->view('layout/customer/client', [
            'viewFile' => $viewFile,
            'information' => $information
        ]);
    }

    public function getWardByCityId()
    {
        $cityId = $_GET['cityId']; // Lấy cityId từ query string
        $wards = $this->service->getWardByCityId($cityId);
        echo json_encode($wards, JSON_UNESCAPED_UNICODE); // Trả về dữ liệu dưới dạng JSON
    }

    public function getCity()
    {
        $cities = $this->service->getCity();
        echo json_encode($cities, JSON_UNESCAPED_UNICODE);
    }

    public function getAllPhoneNumber()
    {
        $phoneNumbers = $this->service->getAllPhoneNumber();
        echo json_encode($phoneNumbers, JSON_UNESCAPED_UNICODE);
    }

    public function getAllEmail()
    {
        $emails = $this->service->getAllEmail();
        echo json_encode($emails, JSON_UNESCAPED_UNICODE);
    }

    public function updateFullName()
    {
        session_start();
        header('Content-Type: application/json');

        $fullName = $_POST['fullName'] ?? '';
        // $userId = $_SESSION['userId'] ?? null;
        $userId = 11; // Thay 11 bằng userId thực tế từ session hoặc tham số

        if (!$userId) {
            echo json_encode(["success" => false, "message" => "Chưa đăng nhập"]);
            return;
        }

        $result = $this->service->updateFullName($fullName, $userId);

        echo json_encode([
            "success" => $result,
            "message" => $result ? "Cập nhật thành công" : "Cập nhật thất bại"
        ]);
    }

    public function updateBirthDate()
    {
        session_start();
        header('Content-Type: application/json');

        $birthDate = $_POST['birthDate'] ?? '';
        // $userId = $_SESSION['userId'] ?? null;
        $userId = 11; // Thay 11 bằng userId thực tế từ session hoặc tham số

        if (!$userId) {
            echo json_encode(["success" => false, "message" => "Chưa đăng nhập"]);
            return;
        }

        $result = $this->service->updateBirthDate($birthDate, $userId);

        echo json_encode([
            "success" => $result,
            "message" => $result ? "Cập nhật thành công" : "Cập nhật thất bại"
        ]);
    }

    public function updateGender()
    {
        session_start();
        header('Content-Type: application/json');

        $gender = $_POST['gender'] ?? '';
        // $userId = $_SESSION['userId'] ?? null;
        $userId = 11; // Thay 11 bằng userId thực tế từ session hoặc tham số

        if (!$userId) {
            echo json_encode(["success" => false, "message" => "Chưa đăng nhập"]);
            return;
        }

        $result = $this->service->updateGender($gender, $userId);

        echo json_encode([
            "success" => $result,
            "message" => $result ? "Cập nhật thành công" : "Cập nhật thất bại"
        ]);
    }

    public function updateEmail()
    {
        session_start();
        header('Content-Type: application/json');

        $email = $_POST['email'] ?? '';
        $userId = $_SESSION['userId'] ?? 11;

        // Lấy email hiện tại
        $currentEmail = $this->service->getEmailByUserId($userId);

        // TH1: không đổi email
        if ($email === $currentEmail) {
            echo json_encode([
                "success" => true,
                "message" => "Cập nhật thành công"
            ]);
            return;
        }

        // TH2: check trùng
        if ($this->service->isEmailExist($email)) {
            echo json_encode([
                "success" => false,
                "message" => "Email đã tồn tại"
            ]);
            return;
        }

        // Update
        $result = $this->service->updateEmail( $email, $userId);

        echo json_encode([
            "success" => $result,
            "message" => $result ? "Cập nhật thành công" : "Cập nhật thất bại"
        ]);
    }

    public function updatePhoneNumber()
    {
        session_start();
        header('Content-Type: application/json');

        $phone = $_POST['phone'] ?? '';
        $userId = $_SESSION['userId'] ?? 11;

        // Lấy số điện thoại hiện tại
        $currentPhone = $this->service->getPhoneByUserId($userId);

        // TH1: không đổi số điện thoại
        if ($phone === $currentPhone) {
            echo json_encode([
                "success" => true,
                "message" => "Cập nhật thành công"
            ]);
            return;
        }

        // TH2: check trùng
        if ($this->service->isPhoneExist($phone)) {
            echo json_encode([
                "success" => false,
                "message" => "Số điện thoại đã tồn tại"
            ]);
            return;
        }

        // Update
        $result = $this->service->updatePhoneNumber( $phone, $userId);

        echo json_encode([
            "success" => $result,
            "message" => $result ? "Cập nhật thành công" : "Cập nhật thất bại"
        ]);
    }

    public function updateAddress()
    {
        session_start();
        header('Content-Type: application/json');

        $address = $_POST['address'] ?? '';
        $cityId = $_POST['cityId'] ?? '';
        $wardId = $_POST['wardId'] ?? '';
        // $userId = $_SESSION['userId'] ?? null;
        $userId = 11; // Thay 11 bằng userId thực tế từ session hoặc tham số

        if (!$userId) {
            echo json_encode(["success" => false, "message" => "Chưa đăng nhập"]);
            return;
        }

        $result = $this->service->updateAddress($address, $wardId, $cityId, $userId);

        echo json_encode([
            "success" => $result,
            "message" => $result ? "Cập nhật thành công" : "Cập nhật thất bại"
        ]);
    }
}
?>