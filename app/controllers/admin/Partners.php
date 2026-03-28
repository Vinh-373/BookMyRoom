<?php

namespace Controllers\admin;

use Controller;

class Partners extends Controller
{
    public function index()
    {
        require_once './app/models/partnersModel.php';
        $partnersModel = new \partnersModel();

        $partners = $partnersModel->join_multi(
            joins: [
                [
                    'table' => 'users',
                    'type'  => 'LEFT',
                    'on'    => 'partners.userId = users.id'
                ]
            ],
            select: '
            users.fullName,
            users.email,
            users.password,
            users.phone,
            users.status,
            users.address,
            users.gender,
            users.birthDate,
            users.avatarUrl,
            users.cityId,
            users.wardId,
            users.createdAt,
            users.deletedAt,
            partners.companyName,
            partners.taxCode,
            partners.businessLicense,
            partners.userId
        ',
            where: [],
            orderBy: 'users.createdAt DESC'
        );

        $this->view('layout/admin/admin', [
            'viewFile' => './app/views/admin/partners.php',
            'partners' => $partners
        ]);
    }

    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            die("Method not allowed");
        }

        $input = $_POST;

        // Validate dữ liệu bắt buộc
        $required_fields = ['fullName', 'email', 'password', 'phone', 'companyName', 'taxCode', 'businessLicense'];
        foreach ($required_fields as $field) {
            if (empty($input[$field])) {
                $_SESSION['error'] = "Thiếu trường: $field";
                header("Location: /BookMyRoom/admin/partners/add");
                exit;
            }
        }

        if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "Email không hợp lệ";
            header("Location: /BookMyRoom/admin/partners/add");
            exit;
        }

        if (!preg_match('/^[0-9]{10}$/', $input['phone'])) {
            $_SESSION['error'] = "Số điện thoại không hợp lệ";
            header("Location: /BookMyRoom/admin/partners/add");
            exit;
        }

        if (!json_decode($input['businessLicense'])) {
            $_SESSION['error'] = "Giấy phép kinh doanh phải là JSON hợp lệ";
            header("Location: /BookMyRoom/admin/partners/add");
            exit;
        }

        try {
            require_once './app/models/partnersModel.php';
            require_once './app/models/myModels.php';

            // Tạo user mới
            $userModel = new class extends \myModels {
                protected $table = "users";
            };

            // Kiểm tra email đã tồn tại chưa
            $conn = $userModel->conn;
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
            $stmt->bind_param("s", $input['email']);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $_SESSION['error'] = "Email đã tồn tại";
                header("Location: /BookMyRoom/admin/partners/add");
                exit;
            }

            // Insert user
            $userData = [
                'fullName'   => $input['fullName'],
                'email'      => $input['email'],
                'password'   => password_hash($input['password'], PASSWORD_DEFAULT),
                'phone'      => $input['phone'],
                'status'     => $input['status'] ?? 'PENDING',
                'address'    => $input['address'] ?? null,
                'gender'     => $input['gender'] ?? null,
                'birthDate'  => $input['birthDate'] ?? null,
                'avatarUrl'  => $input['avatarUrl'] ?? null,
                'cityId'     => $input['cityId'] ?? null,
                'wardId'     => $input['wardId'] ?? null,
                'createdAt'  => date('Y-m-d H:i:s')
            ];

            $userId = $userModel->insert('users', $userData);
            if (!$userId) {
                $_SESSION['error'] = "Không thể tạo tài khoản user";
                header("Location: /BookMyRoom/admin/partners/add");
                exit;
            }

            // Thêm partner
            $partnersModel = new \partnersModel();
            $partnerData = [
                'userId'         => $userId,
                'companyName'    => $input['companyName'],
                'taxCode'        => $input['taxCode'],
                'businessLicense' => $input['businessLicense']
            ];
            $partnerId = $partnersModel->insert('partners', $partnerData);
            if (!$partnerId) {
                $_SESSION['error'] = "Không thể thêm thông tin đối tác";
                header("Location: /BookMyRoom/admin/partners/add");
                exit;
            }

            // Thêm role cho user
            $roleId = $input['roleId'] ?? 2;
            $userRoleModel = new class extends \myModels {
                protected $table = "user_roles";
            };
            $userRoleData = [
                'userId' => $userId,
                'roleId' => $roleId
            ];
            $userRoleId = $userRoleModel->insert('user_roles', $userRoleData);
            if (!$userRoleId) {
                $_SESSION['error'] = "Không thể gán role cho user";
                header("Location: /BookMyRoom/admin/partners/add");
                exit;
            }

            // Thành công → redirect về danh sách
            $_SESSION['success'] = "Thêm đối tác thành công";
            header("Location: /BookMyRoom/admin/partners");
            exit;
        } catch (\Exception $e) {
            $_SESSION['error'] = "Lỗi hệ thống: " . $e->getMessage();
            header("Location: /BookMyRoom/admin/partners/add");
            exit;
        }
    }
    public function search()
    {
        require_once './app/models/partnersModel.php';
        $partnersModel = new \partnersModel();

        $search = $_GET['q'] ?? '';
        $status = $_GET['status'] ?? '';

        // Join với bảng users để lấy đủ thông tin
        $partners = $partnersModel->join_multi(
            joins: [
                ['table' => 'users', 'type' => 'LEFT', 'on' => 'partners.userId = users.id']
            ],
            select: '
            partners.*;
            users.fullName,
            users.email,
            users.password,
            users.phone,
            users.status,
            users.address,
            users.gender,
            users.birthDate,
            users.avatarUrl,
            users.cityId,
            users.wardId,
            users.createdAt

        ',
            orderBy: 'users.createdAt DESC'
        );

        // Lọc theo search và status
        if (!empty($search) || !empty($status)) {
            $partners = array_filter($partners, function ($partner) use ($search, $status) {
                $matchesSearch = empty($search) ||
                    stripos($partner['fullName'], $search) !== false ||
                    stripos($partner['email'], $search) !== false ||
                    stripos($partner['phone'], $search) !== false   ||
                    stripos($partner['companyName'], $search) !== false;


                $matchesStatus = empty($status) || $partner['status'] === $status;

                return $matchesSearch && $matchesStatus;
            });
        }

        echo json_encode(['success' => true, 'partners' => array_values($partners)]);
        exit;
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            exit;
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);

        if (!$input || !isset($input['userId'])) {
            echo json_encode(['success' => false, 'message' => 'Invalid data']);
            exit;
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
            if (isset($input['fullName']))   $userData['fullName'] = $input['fullName'];
            if (isset($input['email']))      $userData['email'] = $input['email'];
            if (isset($input['password']))   $userData['password'] = password_hash($input['password'], PASSWORD_DEFAULT);
            if (isset($input['phone']))      $userData['phone'] = $input['phone'];
            if (isset($input['status']))     $userData['status'] = $input['status'];
            if (isset($input['address']))    $userData['address'] = $input['address'];
            if (isset($input['gender']))     $userData['gender'] = $input['gender'];
            if (isset($input['birthDate']))  $userData['birthDate'] = $input['birthDate'];
            if (isset($input['avatarUrl']))  $userData['avatarUrl'] = $input['avatarUrl'];
            if (isset($input['cityId']))     $userData['cityId'] = $input['cityId'];
            if (isset($input['wardId']))     $userData['wardId'] = $input['wardId'];
            if (isset($input['companyName'])) $userData['companyName'] = $input['companyName'];
            if (isset($input['taxCode']))    $userData['taxCode'] = $input['taxCode'];
            if (isset($input['businessLicense'])) $userData['businessLicense'] = $input['businessLicense'];

            if (!empty($userData)) {
                $userResult = json_decode($userModel->update($userData, ['id' => $input['userId']]), true);
                if ($userResult['type'] !== 'success') {
                    echo json_encode(['success' => false, 'message' => 'Không thể cập nhật thông tin user']);
                    exit;
                    return;
                }
            }

            // Update partner info
            $partnerData = [];
            if (isset($input['companyName']))     $partnerData['companyName'] = $input['companyName'];
            if (isset($input['taxCode']))         $partnerData['taxCode'] = $input['taxCode'];
            if (isset($input['businessLicense'])) $partnerData['businessLicense'] = $input['businessLicense'];

            if (!empty($partnerData)) {
                $partnerResult = json_decode($partnersModel->update($partnerData, ['userId' => $input['userId']]), true);
                if ($partnerResult['type'] !== 'success') {
                    echo json_encode(['success' => false, 'message' => 'Không thể cập nhật thông tin đối tác']);
                    exit;
                    return;
                }
            }

            echo json_encode(['success' => true, 'message' => 'Cập nhật thành công']);
            exit;
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
            exit;
        }
    }

    public function approve()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            exit;
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);

        if (!$input || !isset($input['userId'])) {
            echo json_encode(['success' => false, 'message' => 'Invalid data']);
            exit;
            return;
        }

        try {
            require_once './app/models/myModels.php';

            $userModel = new class extends \myModels {
                protected $table = "users";
            };

            // Cập nhật trạng thái sang ACTIVE
            $result = json_decode(
                $userModel->update(['status' => 'ACTIVE'], ['id' => $input['userId']]),
                true
            );

            if ($result['type'] === 'success') {
                echo json_encode(['success' => true, 'message' => 'Duyệt đối tác thành công']);
                exit;
            } else {
                echo json_encode(['success' => false, 'message' => 'Không thể duyệt đối tác']);
                exit;
            }
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
            exit;
        }
    }





public function toggleStatus()
{
    header('Content-Type: application/json; charset=utf-8');

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        exit;
        return;
    }

    $input = json_decode(file_get_contents('php://input'), true);

    if (!$input || !isset($input['userId']) || !isset($input['status'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid data']);
        exit;
        return;
    }

    try {
        require_once './app/models/myModels.php';

        $userModel = new class extends \myModels {
            protected $table = "users";
        };

        $result = $userModel->update(['status' => $input['status']], ['id' => $input['userId']]);

        // Nếu update() trả về JSON string, decode trước
        $decoded = json_decode($result, true);

        if ($decoded && $decoded['type'] === 'success') {
            echo json_encode([
                'success' => true,
                'message' => 'Cập nhật trạng thái thành công',
                'newStatus' => $input['status']
            ]);
            exit;
        } else {
            echo json_encode(['success' => false, 'message' => 'Không thể cập nhật trạng thái']);
            exit;
        }
    } catch (\Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
        exit;
    }
}

}