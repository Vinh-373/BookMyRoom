<?php

namespace Controllers\admin;

use Controller;

class Customers extends Controller
{
    public function index()
    {
        require_once './app/models/customersModel.php';
        require_once './app/models/myModels.php';
        $customersModel = new \customersModel();

        // Lấy users có role CUSTOMER
        $customers = $customersModel->join_multi(
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
            where: [
                ['roles.name', '=', 'CUSTOMER' or 'roles.id', '=', '3']
            ],
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
}
