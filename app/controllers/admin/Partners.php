<?php

namespace Controllers\admin;

use Controller;
use Models\PartnersModel;
require_once "./app/models/partnersModel.php";
use Models\MyModels;
require_once "./app/models/myModels.php";
class Partners extends Controller
{
    public function index()
    {
      
        $partnersModel = new PartnersModel();

        $partners = $partnersModel->join_multi(
            joins: [
                [
                    'table' => 'users',
                    'type'  => 'LEFT',
                    'on'    => 'partners.userId = users.id'
                ]
            ],
            select: '
            partners.*,
            users.fullName,
            users.email,
            users.phone,
            users.status,
            users.address,
            users.gender,
            users.birthDate,
            users.avatarUrl,
            users.cityId,
            users.wardId,
            users.createdAt,
            users.deletedAt

        ',
            where: [],
            orderBy: 'partners.userId ASC'
        );

        // 2️⃣ Lấy cities và wards để mapping tên
        $myModelCities = new class extends myModels {
            protected $table = "cities";
        };
        $cities = $myModelCities->findAll();

        $myModelWards = new class extends myModels {
            protected $table = "wards";
        };
        $wards = $myModelWards->findAll();

        // 3️⃣ Map cityName và wardName cho mỗi partner
        // tạo map trước
        $cityMap = [];
        foreach ($cities as $c) {
            $cityMap[$c['id']] = $c['name'];
        }

        $wardMap = [];
        foreach ($wards as $w) {
            $wardMap[$w['id']] = $w['name'];
        }

        // map vào partners
        foreach ($partners as &$partner) {
            $partner['cityName'] = $cityMap[$partner['cityId']] ?? '';
            $partner['wardName'] = $wardMap[$partner['wardId']] ?? '';
        }


        // 4️⃣ Truyền dữ liệu vào view
        $this->view('layout/admin/admin', [
            'viewFile' => './app/views/admin/partners.php',
            'partners' => $partners,
            'cities'   => $cities,
            'wards'    => $wards
        ]);
    }




    /////////////////////////////////////////////////////////////////////////
    public function add_partner()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            die("Method not allowed");
        }

        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) {
            echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
            exit;
        }
        // Validate dữ liệu bắt buộc
        $required_fields = ['fullName', 'email', 'password', 'phone', 'companyName', 'taxCode', 'businessLicense'];
        foreach ($required_fields as $field) {
            if (empty($input[$field])) {
                echo json_encode([
                    'success' => false,
                    'message' => "Thiếu trường: $field"
                ]);
                exit;
            }
        }

        if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
            echo json_encode([
                'success' => false,
                'message' => 'Email không hợp lệ'
            ]);
            exit;
        }

        if (!preg_match('/^[0-9]{10}$/', $input['phone'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Số điện thoại không hợp lệ'
            ]);
            exit;
        }
        require_once './app/models/myModels.php';

        try {
            require_once './app/models/myModels.php';

            $tempModel = new class extends myModels {
                protected $table = "partners";
            };

            $conn = $tempModel->conn;

            $stmt = $conn->prepare("SELECT userId FROM partners WHERE companyName = ? LIMIT 1");

            if (!$stmt) {
                throw new \Exception($conn->error);
            }

            $stmt->bind_param("s", $input['companyName']);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Tên công ty đã tồn tại'
                ]);
                exit;
            }
        } catch (\Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Lỗi check company: ' . $e->getMessage()
            ]);
            exit;
        }


        json_decode($input['businessLicense']);
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo json_encode([
                'success' => false,
                'message' => 'Giấy phép kinh doanh phải là JSON hợp lệ'
            ]);
            exit;
        }

        try {
            require_once './app/models/partnersModel.php';
            require_once './app/models/myModels.php';

            // Tạo user mới
            $userModel = new class extends myModels {
                protected $table = "users";
            };

            // Kiểm tra email đã tồn tại chưa
            $conn = $userModel->conn;
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
            $stmt->bind_param("s", $input['email']);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Email đã tồn tại'
                ]);
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

            $userResultRaw = $userModel->insert('users', $userData);
            $userResult = json_decode($userResultRaw, true);

            if (!$userResult || !isset($userResult['data'])) {
                echo json_encode([
                    'success' => false,
                    'message' => "Không thể tạo tài khoản user",
                    'debug' => $userResultRaw
                ]);
                exit;
            }



            $userRoleModel = new class extends myModels {
                protected $table = "user_roles";
            };

            // Chỉ insert trực tiếp
            $userRoleData = [
                'userId' => $userId,
                'roleId' => $roleId ?? 2 // mặc định 2 nếu không truyền
            ];

            $userRoleRaw = $userRoleModel->insert('user_roles', $userRoleData);
            $userRoleResult = json_decode($userRoleRaw, true);

            if (!$userRoleResult || $userRoleResult['type'] !== 'success') {
                echo json_encode([
                    'success' => false,
                    'message' => "Không thể thêm role cho user",
                    'debug' => $userRoleRaw
                ]);
                exit;
            }




            $userId = $userResult['data'];

            // Thêm partner
            $partnersModel = new partnersModel();
            $partnerData = [
                'userId'         => $userId,
                'companyName'    => $input['companyName'],
                'taxCode'        => $input['taxCode'],
                'businessLicense' => $input['businessLicense']
            ];
            $partnerResultRaw = $partnersModel->insert('partners', $partnerData);
            $partnerResult = json_decode($partnerResultRaw, true);

            if (!$partnerResult || $partnerResult['type'] !== 'success') {
                echo json_encode([
                    'success' => false,
                    'message' => "Không thể thêm thông tin đối tác",
                    'debug' => $partnerResultRaw
                ]);
                exit;
            }

            // Thêm role cho user
            $roleId = $input['roleId'] ?? 2;
            $userRoleModel = new class extends myModels {
                protected $table = "user_roles";
            };
            $userRoleData = [
                'userId' => $userId,
                'roleId' => $roleId
            ];
            $userRoleRaw = $userRoleModel->insert('user_roles', $userRoleData);
            $userRoleResult = json_decode($userRoleRaw, true);

            if (!$userRoleResult || $userRoleResult['type'] !== 'success') {
                echo json_encode([
                    'success' => false,
                    'message' => "Không thể gán role cho user",
                    'debug' => $userRoleRaw
                ]);
                exit;
            }

            // Thành công → redirect về danh sách
            echo json_encode([
                'success' => true,
                'message' => "Thêm đối tác thành công"
            ]);
            exit;
        } catch (\Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => "Lỗi hệ thống: " . $e->getMessage()
            ]);
            exit;
        }
    }

    /////////////////////////////////////////////////////////////////////////
    public function search_partner()
    {
        require_once './app/models/partnersModel.php';
        $partnersModel = new partnersModel();

        $search = $_GET['q'] ?? '';
        $status = $_GET['status'] ?? '';

        // Join với bảng users để lấy đủ thông tin
        $partners = $partnersModel->join_multi(
            joins: [
                ['table' => 'users', 'type' => 'LEFT', 'on' => 'partners.userId = users.id']
            ],
            select: '
            partners.*,
            users.fullName,
            users.email,
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





    /////////////////////////////////////////////////////////////////////////
    public function update_partner()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            exit;
        }

        $input = json_decode(file_get_contents('php://input'), true);

        if (!$input || !isset($input['userId'])) {
            echo json_encode(['success' => false, 'message' => 'Invalid data']);
            exit;
        }

        try {
            require_once './app/models/myModels.php';

            $userModel = new class extends myModels {
                protected $table = "users";
            };

            $partnersModel = new class extends myModels {
                protected $table = "partners";
            };

            // ================= USER =================
            $userData = [];

            if (isset($input['fullName']))   $userData['fullName'] = $input['fullName'];
            if (isset($input['email'])) {
                $stmt = $userModel->conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
                $stmt->bind_param("si", $input['email'], $input['userId']);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Email đã tồn tại'
                    ]);
                    exit;
                }

                $userData['email'] = $input['email'];
            }
            if (!empty($input['password']))  $userData['password'] = password_hash($input['password'], PASSWORD_DEFAULT);
            if (isset($input['phone']))      $userData['phone'] = $input['phone'];
            if (isset($input['status']))     $userData['status'] = $input['status'];
            if (isset($input['address']))    $userData['address'] = $input['address'];
            if (isset($input['gender']))     $userData['gender'] = $input['gender'];
            if (isset($input['birthDate']))  $userData['birthDate'] = $input['birthDate'];
            if (isset($input['avatarUrl']))  $userData['avatarUrl'] = $input['avatarUrl'];

            // Kiểm tra rỗng cityId / wardId
            $userData['cityId'] = (isset($input['cityId']) && $input['cityId'] !== "") ? $input['cityId'] : null;
            $userData['wardId'] = (isset($input['wardId']) && $input['wardId'] !== "") ? $input['wardId'] : null;

            if (!empty($userData)) {
                $userResult = json_decode($userModel->update($userData, ['id' => $input['userId']]), true);
                if (!in_array($userResult['type'], ['success', 'warning'])) {
                    echo json_encode(['success' => false, 'message' => 'Không thể cập nhật user']);
                    exit;
                }
            }

            // ================= PARTNER =================
            $partnerData = [];
            if (isset($input['companyName']))     $partnerData['companyName'] = $input['companyName'];
            if (isset($input['taxCode']))         $partnerData['taxCode'] = $input['taxCode'];
            if (isset($input['businessLicense'])) $partnerData['businessLicense'] = $input['businessLicense'];

            if (!empty($partnerData)) {
                $existing = $partnersModel->findOne(['userId' => $input['userId']]);
                if ($existing) {
                    $partnerResult = json_decode(
                        $partnersModel->update($partnerData, ['userId' => $input['userId']]),
                        true
                    );
                } else {
                    $partnerData['userId'] = $input['userId'];
                    $partnerResult = json_decode(
                        $partnersModel->insert('partners', $partnerData),
                        true
                    );
                }
                if (!$partnerResult || !isset($partnerResult['type']) || !in_array($partnerResult['type'], ['success', 'warning'])) {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Không thể cập nhật đối tác',
                        'debug' => $partnerResult
                    ]);
                    exit;
                }
            }

            // ================= LẤY DỮ LIỆU ĐỂ TRẢ VỀ =================
            $updatedUser = $userModel->findOne(['id' => $input['userId']]);

            // Lấy tên city / ward
            // Giả sử trong myModels có property $conn (mysqli)


            $cityName = '';
            if (!empty($updatedUser['cityId'])) {
                $stmt = $userModel->conn->prepare("SELECT name FROM cities WHERE id = ?");
                $stmt->bind_param("i", $updatedUser['cityId']);
                $stmt->execute();
                $result = $stmt->get_result();
                $cityName = $result->fetch_assoc()['name'] ?? '';
            }

            $wardName = '';
            if (!empty($updatedUser['wardId'])) {
                $stmt = $userModel->conn->prepare("SELECT name FROM wards WHERE id = ?");
                $stmt->bind_param("i", $updatedUser['wardId']);
                $stmt->execute();
                $result = $stmt->get_result();
                $wardName = $result->fetch_assoc()['name'] ?? '';
            }

            $updatedUser['cityName'] = $cityName;
            $updatedUser['wardName'] = $wardName;

            // Thêm dữ liệu partner
            $partner = $partnersModel->findOne(['userId' => $input['userId']]);
            $updatedUser['companyName'] = $partner['companyName'] ?? '';
            $updatedUser['taxCode'] = $partner['taxCode'] ?? '';
            $updatedUser['businessLicense'] = $partner['businessLicense'] ?? '';

            // Trả về JSON
            echo json_encode([
                'success' => true,
                'message' => 'Cập nhật thành công',
                'updatedUser' => $updatedUser
            ]);
            exit;
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
            exit;
        }
    }

    //////////////////////////////////////////////////////////////////
    public function approve_partner()
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

            $userModel = new class extends myModels {
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


    /////////////////////////////////////////////////////


    public function toggleStatus_partner()
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

            $userModel = new class extends myModels {
                protected $table = "users";
            };

            $result = $userModel->update(['status' => $input['status']], ['id' => $input['userId']]);

            // Nếu update() trả về JSON string, decode trước
            $decoded = json_decode($result, true);

            if ($decoded && in_array($decoded['type'], ['success', 'warning'])) {
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
