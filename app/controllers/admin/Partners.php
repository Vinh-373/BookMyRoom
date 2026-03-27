<?php
namespace Controllers\admin;
use Controller;

class Partners extends Controller {
    public function index() {
        // Load the partners model
        require_once './app/models/partnersModel.php';
        $partnersModel = new \partnersModel();

        // Get partners with user info using join
        $partners = $partnersModel->join_multi(
            joins: [
                [
                    'table' => 'users',
                    'type'  => 'LEFT',
                    'on'    => 'partners.userId = users.id'
                ]
            ],
            select: 'partners.*, users.fullName, users.email, users.phone, users.status, users.createdAt',
            where: [],
            orderBy: 'users.createdAt DESC'
        );

        $this->view('layout/admin/admin', [
            'viewFile' => './app/views/admin/partners.php',
            'partners' => $partners
        ]);
    }

    public function add() {
        // Chỉ xử lý POST request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        // Lấy dữ liệu JSON từ request
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input) {
            echo json_encode(['success' => false, 'message' => 'Invalid JSON data']);
            return;
        }

        // Validate dữ liệu
        $required_fields = ['fullName', 'email', 'phone', 'companyName', 'taxCode', 'businessLicense'];
        foreach ($required_fields as $field) {
            if (empty($input[$field])) {
                echo json_encode(['success' => false, 'message' => "Thiếu trường: $field"]);
                return;
            }
        }

        // Validate email
        if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'Email không hợp lệ']);
            return;
        }

        // Validate JSON cho businessLicense
        if (!json_decode($input['businessLicense'])) {
            echo json_encode(['success' => false, 'message' => 'Giấy phép kinh doanh phải là JSON hợp lệ']);
            return;
        }

        try {
            // Load models
            require_once './app/models/partnersModel.php';
            require_once './app/models/myModels.php';
            
            // Tạo user mới trước
            $userModel = new class extends \myModels {
                protected $table = "users";
            };

            $userData = [
                'fullName' => $input['fullName'],
                'email' => $input['email'],
                'phone' => $input['phone'],
                'status' => 'PENDING', // Mặc định là PENDING, admin sẽ duyệt sau
                'password' => password_hash('123456', PASSWORD_DEFAULT), // Mật khẩu mặc định
                'createdAt' => date('Y-m-d H:i:s')
            ];

            // Thêm user
            $userResult = json_decode($userModel->insert('users', $userData), true);
            
            if ($userResult['type'] !== 'success') {
                echo json_encode(['success' => false, 'message' => 'Không thể tạo tài khoản user']);
                return;
            }

            $userId = $userResult['data'];

            // Thêm partner
            $partnersModel = new \partnersModel();
            $partnerData = [
                'userId' => $userId,
                'companyName' => $input['companyName'],
                'taxCode' => $input['taxCode'],
                'businessLicense' => $input['businessLicense']
            ];

            $partnerResult = json_decode($partnersModel->insert('partners', $partnerData), true);

            if ($partnerResult['type'] === 'success') {
                echo json_encode(['success' => true, 'message' => 'Thêm đối tác thành công']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Không thể thêm thông tin đối tác']);
            }

        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
        }
    }

    public function search() {
        // Load the partners model
        require_once './app/models/partnersModel.php';
        $partnersModel = new \partnersModel();

        $search = $_GET['q'] ?? '';
        $status = $_GET['status'] ?? '';

        // Always get partners, filter in PHP if needed
        $partners = $partnersModel->join_multi(
            joins: [['table' => 'users', 'type' => 'LEFT', 'on' => 'partners.userId = users.id']],
            select: 'partners.*, users.fullName, users.email, users.phone, users.status, users.createdAt',
            orderBy: 'users.createdAt DESC'
        );

        // Filter in PHP
        if (!empty($search) || !empty($status)) {
            $partners = array_filter($partners, function($partner) use ($search, $status) {
                $matchesSearch = empty($search) || 
                    stripos($partner['fullName'], $search) !== false ||
                    stripos($partner['email'], $search) !== false ||
                    stripos($partner['companyName'], $search) !== false;
                
                $matchesStatus = empty($status) || $partner['status'] === $status;
                
                return $matchesSearch && $matchesStatus;
            });
        }

        echo json_encode(['success' => true, 'partners' => array_values($partners)]);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input || !isset($input['userId'])) {
            echo json_encode(['success' => false, 'message' => 'Invalid data']);
            return;
        }

        try {
            require_once './app/models/myModels.php';
            
            $userModel = new class extends \myModels {
                protected $table = "users";
            };

            $partnersModel = new class extends \myModels {
                protected $table = "partners";
            };

            // Update user info
            $userData = [];
            if (isset($input['fullName'])) $userData['fullName'] = $input['fullName'];
            if (isset($input['email'])) $userData['email'] = $input['email'];
            if (isset($input['phone'])) $userData['phone'] = $input['phone'];
            if (isset($input['status'])) $userData['status'] = $input['status'];

            if (!empty($userData)) {
                $userResult = json_decode($userModel->update($userData, ['id' => $input['userId']]), true);
                if ($userResult['type'] !== 'success') {
                    echo json_encode(['success' => false, 'message' => 'Không thể cập nhật thông tin user']);
                    return;
                }
            }

            // Update partner info
            $partnerData = [];
            if (isset($input['companyName'])) $partnerData['companyName'] = $input['companyName'];
            if (isset($input['taxCode'])) $partnerData['taxCode'] = $input['taxCode'];
            if (isset($input['businessLicense'])) $partnerData['businessLicense'] = $input['businessLicense'];

            if (!empty($partnerData)) {
                $partnerResult = json_decode($partnersModel->update($partnerData, ['userId' => $input['userId']]), true);
                if ($partnerResult['type'] !== 'success') {
                    echo json_encode(['success' => false, 'message' => 'Không thể cập nhật thông tin đối tác']);
                    return;
                }
            }

            echo json_encode(['success' => true, 'message' => 'Cập nhật thành công']);

        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
        }
    }

    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input || !isset($input['userId'])) {
            echo json_encode(['success' => false, 'message' => 'Invalid data']);
            return;
        }

        try {
            require_once './app/models/myModels.php';
            
            $partnersModel = new class extends \myModels {
                protected $table = "partners";
            };

            $userModel = new class extends \myModels {
                protected $table = "users";
            };

            // Delete partner first (foreign key constraint)
            $partnerResult = json_decode($partnersModel->update(['deletedAt' => date('Y-m-d H:i:s')], ['userId' => $input['userId']]), true);
            
            // Soft delete user
            $userResult = json_decode($userModel->update(['deletedAt' => date('Y-m-d H:i:s')], ['id' => $input['userId']]), true);

            if ($userResult['type'] === 'success') {
                echo json_encode(['success' => true, 'message' => 'Xóa đối tác thành công']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Không thể xóa đối tác']);
            }

        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
        }
    }

    public function approve() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input || !isset($input['userId'])) {
            echo json_encode(['success' => false, 'message' => 'Invalid data']);
            return;
        }

        try {
            require_once './app/models/myModels.php';
            
            $userModel = new class extends \myModels {
                protected $table = "users";
            };

            $result = json_decode($userModel->update(['status' => 'ACTIVE'], ['id' => $input['userId']]), true);

            if ($result['type'] === 'success') {
                echo json_encode(['success' => true, 'message' => 'Duyệt đối tác thành công']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Không thể duyệt đối tác']);
            }

        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
        }
    }
}