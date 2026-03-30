<?php

namespace Controllers\admin;

use Controller;

class Staffs extends Controller
{
    public function index()
    {
        require_once './app/models/staffsModel.php';
        require_once './app/models/myModels.php';
        $staffsModel = new \staffsModel();

        // Lấy users có role STAFF
        $staffs = $staffsModel->join_multi(
            joins: [
                [
                    'table' => 'user_roles',
                    'type'  => 'INNER',
                    'on'    => 'users.id = user_roles.userId'
                ],
                [
                    'table' => 'roles',
                    'type'  => 'INNER',
                    'on'    => 'user_roles.roleId = roles.id'
                ]
            ],
            select: '
                users.id,
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
            where: ['roles.name' => 'STAFF'],
            orderBy: 'users.id ASC'
        );

        // 2️⃣ Lấy cities và wards để mapping tên
        $myModelCities = new class extends \myModels {
            protected $table = "cities";
        };
        $cities = $myModelCities->findAll();

        $myModelWards = new class extends \myModels {
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

        // map vào staffs
        foreach ($staffs as &$staff) {
            $staff['cityName'] = $cityMap[$staff['cityId']] ?? '';
            $staff['wardName'] = $wardMap[$staff['wardId']] ?? '';
        }


        // 4️⃣ Truyền dữ liệu vào view
        $this->view('layout/admin/admin', [
            'viewFile' => './app/views/admin/staffs.php',
            'staffs' => $staffs,
            'cities'   => $cities,
            'wards'    => $wards
        ]);
    }
}
