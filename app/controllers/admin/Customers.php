<?php

namespace Controllers\admin;

use Controller;
use Models\customersModel;
use Models\MyModels;
require_once "./app/models/customersModel.php";
require_once "./app/models/myModels.php";
class Customers extends Controller
{
    public function index()
    {
        if (empty($_SESSION["admin_id"]) || empty($_SESSION["admin_name"])) {
    // Chuyển hướng về trang auth (đăng nhập)
    header("Location: /BookMyRoom/admin/auth");
    exit(); // Luôn phải có exit để dừng thực thi code phía dưới
}
        $customersModel = new customersModel();

        // Lấy users có role CUSTOMER
        $customers = $customersModel->join_multi(
            joins: [
                

            ],
            select: '
                users.*
            ',
            where: ['users.role' => 'CUSTOMER'],
            orderBy: 'users.id ASC'
        );

        

        // 2️⃣ Lấy cities và wards để mapping tên
        $myModelCities = new class extends MyModels {
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

        // map vào customers
        foreach ($customers as &$customer) {
            $customer['cityName'] = $cityMap[$customer['cityId']] ?? '';
            $customer['wardName'] = $wardMap[$customer['wardId']] ?? '';
        }


        // 4️⃣ Truyền dữ liệu vào view
        $this->view('layout/admin/admin', [
            'viewFile' => './app/views/admin/customers.php',
            'customers' => $customers,
            'cities'   => $cities,
            'wards'    => $wards
        ]);
    }




    /////////////////////////////////////////////////////////////////////////
    public function add_customer()
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
        $required_fields = ['fullName', 'email', 'password', 'phone'];
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
            require_once './app/models/customersModel.php';
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

            // ✅ LẤY userId TRƯỚC
            $userId = $userResult['data'];

            $stmt = $conn->prepare("SELECT id FROM roles WHERE name = 'CUSTOMER' LIMIT 1");
            $stmt->execute();
            $result = $stmt->get_result();
            $role = $result->fetch_assoc();

            $roleId = $role['id'];

            // ✅ INSERT ROLE (CHỈ 1 LẦN)
            $userRoleModel = new class extends myModels {
                protected $table = "user_roles";
            };

            // Chỉ insert trực tiếp
            $userRoleData = [
                'userId' => $userId,
                'roleId' => $roleId
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
            // Thành công → redirect về danh sách
            echo json_encode([
                'success' => true,
                'message' => "Thêm nhân viên thành công"
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
    public function search_customer()
    {
        require_once './app/models/customersModel.php';
        $customersModel = new customersModel();

        $search = $_GET['q'] ?? '';
        $status = $_GET['status'] ?? '';

        // Join với bảng users để lấy đủ thông tin
        $customers = $customersModel->join_multi(
            joins: [
                ['table' => 'users', 'type' => 'LEFT', 'on' => 'customers.id = users.id']
            ],
            select: '
            customers.*,
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
            orderBy: 'users.id DESC'
        );

        // Lọc theo search và status
        if (!empty($search) || !empty($status)) {
            $customers = array_filter($customers, function ($staff) use ($search, $status) {
                $matchesSearch = empty($search) ||
                    stripos($staff['fullName'], $search) !== false ||
                    stripos($staff['email'], $search) !== false ||
                    stripos($staff['phone'], $search) !== false;


                $matchesStatus = empty($status) || $staff['status'] === $status;

                return $matchesSearch && $matchesStatus;
            });
        }

        echo json_encode(['success' => true, 'customers' => array_values($customers)]);
        exit;
    }





    /////////////////////////////////////////////////////////////////////////
    public function update_customer()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            exit;
        }

        $input = json_decode(file_get_contents('php://input'), true);

        if (!$input || !isset($input['id'])) {
            echo json_encode(['success' => false, 'message' => 'Invalid data']);
            exit;
        }

        try {
            require_once './app/models/myModels.php';

            $userModel = new class extends myModels {
                protected $table = "users";
            };

            $customersModel = new class extends myModels {
                protected $table = "customers";
            };

            // ================= USER =================
            $userData = [];

            if (isset($input['fullName']))   $userData['fullName'] = $input['fullName'];
            if (isset($input['email'])) {
                $stmt = $userModel->conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
                $stmt->bind_param("si", $input['email'], $input['id']);
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
                $userResult = json_decode($userModel->update($userData, ['id' => $input['id']]), true);
                if (!in_array($userResult['type'], ['success', 'warning'])) {
                    echo json_encode(['success' => false, 'message' => 'Không thể cập nhật user']);
                    exit;
                }
            }

            // ================= staff =================
            $staffData = [];

            if (!empty($staffData)) {
                $existing = $customersModel->findOne(['id' => $input['id']]);
                if ($existing) {
                    $staffResult = json_decode(
                        $customersModel->update($staffData, ['id' => $input['id']]),
                        true
                    );
                } else {
                    $staffData['id'] = $input['id'];
                    $staffResult = json_decode(
                        $customersModel->insert('customers', $staffData),
                        true
                    );
                }
                if (!$staffResult || !isset($staffResult['type']) || !in_array($staffResult['type'], ['success', 'warning'])) {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Không thể cập nhật đối tác',
                        'debug' => $staffResult
                    ]);
                    exit;
                }
            }

            // ================= LẤY DỮ LIỆU ĐỂ TRẢ VỀ =================
            $updatedUser = $userModel->findOne(['id' => $input['id']]);

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
    public function approve_customer()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            exit;
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);

        if (!$input || !isset($input['id'])) {
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
                $userModel->update(['status' => 'ACTIVE'], ['id' => $input['id']]),
                true
            );

            if ($result['type'] === 'success') {
                echo json_encode(['success' => true, 'message' => 'Duyệt nhân viên thành công']);
                exit;
            } else {
                echo json_encode(['success' => false, 'message' => 'Không thể duyệt nhân viên']);
                exit;
            }
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
            exit;
        }
    }


    /////////////////////////////////////////////////////


    public function toggleStatus_customer()
    {
        header('Content-Type: application/json; charset=utf-8');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            exit;
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);

        if (!$input || !isset($input['id']) || !isset($input['status'])) {
            echo json_encode(['success' => false, 'message' => 'Invalid data']);
            exit;
            return;
        }

        try {
            require_once './app/models/myModels.php';

            $userModel = new class extends myModels {
                protected $table = "users";
            };

            $result = $userModel->update(['status' => $input['status']], ['id' => $input['id']]);

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
