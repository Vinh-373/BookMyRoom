<?php

namespace Controllers\admin;

use Controller;
use Models\MyModels;
require_once "./app/models/myModels.php";
use Models\vouchersModel;
require_once "./app/models/vouchersModel.php";
class Vouchers extends Controller
{
    public function index()
    {
        if (empty($_SESSION["admin_id"]) || empty($_SESSION["admin_name"])) {
    // Chuyển hướng về trang auth (đăng nhập)
    header("Location: /BookMyRoom/admin/auth");
    exit(); // Luôn phải có exit để dừng thực thi code phía dưới
}

        $vouchersModel = new vouchersModel();

        $vouchers = $vouchersModel->join_multi(
            joins: [],
            select: "
                vouchers.*
            ",
            orderBy: "vouchers.id DESC"
        );

        $this->view('layout/admin/admin', [
            'viewFile' => './app/views/admin/vouchers.php',
            'vouchers' => $vouchers
        ]);
    }

    public function create_voucher()
    {
        header('Content-Type: application/json; charset=utf-8');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            exit;
        }

        $input = json_decode(file_get_contents("php://input"), true);

        if (!$input || !isset($input['code'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Thiếu dữ liệu'
            ]);
            exit;
        }

        try {
            require_once './app/models/myModels.php';

            $voucherModel = new class extends myModels {
                protected $table = "vouchers";
            };

            $resultRaw = $voucherModel->insert('vouchers', [
                'code' => $input['code'],
                'quantity' => (int)$input['quantity'],
                'type' => $input['type'],
                'amount' => (int)$input['amount'],
                '`condition`' => (int)$input['condition'], // bỏ backtick luôn
                'startDate' => $input['startDate'],
                'endDate' => $input['endDate']
            ]);

            $result = json_decode($resultRaw, true);

            if ($result && in_array($result['type'], ['success', 'warning'])) {

                // lấy lại record mới (id vừa insert)
                $id = $result['insert_id'];

                $voucher = $voucherModel->findOne(['id' => $id]);

                echo json_encode([
                    'success' => true,
                    'voucher' => $voucher
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Không thể thêm voucher'
                ]);
            }
        } catch (\Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Lỗi hệ thống: ' . $e->getMessage()
            ]);
        }

        exit;
    }

    // ===== UPDATE =====
    public function update_voucher()
    {
        header('Content-Type: application/json; charset=utf-8');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            exit;
        }

        $input = json_decode(file_get_contents("php://input"), true);

        if (!$input || !isset($input['id'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Thiếu dữ liệu'
            ]);
            exit;
        }

        try {
            require_once './app/models/myModels.php';

            $voucherModel = new class extends myModels {
                protected $table = "vouchers";
            };

            $resultRaw = $voucherModel->update(
                'vouchers',
                [
                    'code' => $input['code'],
                    'quantity' => (int)$input['quantity'],
                    'type' => $input['type'],
                    'amount' => (int)$input['amount'],
                    '`condition`' => (int)$input['condition'],
                    'startDate' => $input['startDate'],
                    'endDate' => $input['endDate']
                ],
                ['id' => $input['id']]
            );

            $result = json_decode($resultRaw, true);

            if ($result && in_array($result['type'], ['success', 'warning'])) {

                $voucher = $voucherModel->findOne(['id' => $input['id']]);

                echo json_encode([
                    'success' => true,
                    'voucher' => $voucher
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Không thể cập nhật'
                ]);
            }
        } catch (\Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Lỗi hệ thống: ' . $e->getMessage()
            ]);
        }

        exit;
    }

    // ===== DELETE =====
    public function delete_voucher()
    {
        header('Content-Type: application/json; charset=utf-8');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            exit;
        }

        $input = json_decode(file_get_contents("php://input"), true);

        if (!$input || !isset($input['id'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Thiếu ID'
            ]);
            exit;
        }

        try {
            require_once './app/models/myModels.php';

            $voucherModel = new class extends myModels {
                protected $table = "vouchers";
            };

            $result = json_decode(
                $voucherModel->delete(['id' => $input['id']]),
                true
            );

            if ($result && in_array($result['type'], ['success', 'warning'])) {
                echo json_encode([
                    'success' => true
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Không thể xóa'
                ]);
            }
        } catch (\Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Lỗi hệ thống: ' . $e->getMessage()
            ]);
        }

        exit;
    }
}
