<?php

namespace Controllers\admin;

use Controller;
use Models\myModels;
require_once "./app/models/myModels.php";
class Settings extends Controller
{

    public function index()
    {
        require_once './app/models/myModels.php';

        $model = new class extends myModels {
            protected $table = "users";
        };

        $admin = $model->findOne([
            'id' => 1
        ]);

        $this->view('layout/admin/admin', [
            'viewFile' => './app/views/admin/settings.php',
            'admin' => $admin
        ]);
    }


    /* ==============================
        Cập nhật thông tin hệ thống
    ============================== */
    public function update_system()
    {
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents("php://input"), true);

        if (!$input) {
            echo json_encode(['success' => false]);
            exit;
        }

        require_once './app/models/myModels.php';

        $model = new class extends myModels {
            protected $table = "users";
        };

        $result = json_decode(
            $model->update(
                [
                    'fullName' => $input['name'],
                    'email' => $input['email'],
                    'phone' => $input['phone']
                ],
                [
                    'id' => 1
                ]
            ),
            true
        );

        echo json_encode([
            'success' => in_array($result['type'], ['success','warning'])
        ]);

        exit;
    }


    /* ==============================
        Cập nhật payment settings
    ============================== */
    public function update_payment()
    {
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents("php://input"), true);

        if (!$input) {
            echo json_encode(['success' => false]);
            exit;
        }

        // Ví dụ lưu xuống bảng settings
        require_once './app/models/myModels.php';

        $model = new class extends myModels {
            protected $table = "settings";
        };

        $model->update(
            [
                'platform_fee' => $input['platformFee'],
                'momo' => $input['momo'],
                'vnpay' => $input['vnpay'],
                'visa' => $input['visa']
            ],
            [
                'id' => 1
            ]
        );

        echo json_encode([
            'success' => true
        ]);

        exit;
    }


    /* ==============================
        Đổi mật khẩu admin
    ============================== */
    public function change_password()
    {
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents("php://input"), true);

        if (!$input) {
            echo json_encode(['success' => false]);
            exit;
        }

        require_once './app/models/myModels.php';

        $model = new class extends myModels {
            protected $table = "users";
        };

        $admin = $model->findOne(['id' => 1]);

        if ($admin['password'] != $input['currentPassword']) {
            echo json_encode([
                'success' => false,
                'message' => 'Sai mật khẩu'
            ]);
            exit;
        }

        $result = json_decode(
            $model->update(
                [
                    'password' => $input['newPassword']
                ],
                [
                    'id' => 1
                ]
            ),
            true
        );

        echo json_encode([
            'success' => in_array($result['type'], ['success','warning'])
        ]);

        exit;
    }

}