<?php
namespace Models;

require_once "./app/models/MyModels.php";

class partnersModel extends myModels {
    protected $table = "partners";
    // Các phương thức cụ thể cho bảng partners có thể được thêm vào đây nếu cần
    function getCountPartnersPending(): int
    {
        $sql = "SELECT COUNT(*) AS total_partners FROM {$this->table} JOIN users ON {$this->table}.userId = users.id WHERE users.status = 'PENDING'";
        $result = $this->conn->query($sql); // mysqli query thuần

        if ($result) {
            $row = $result->fetch_assoc();
            return isset($row['total_partners']) ? (int)$row['total_partners'] : 0;
        }

        // Nếu query lỗi, trả về 0
        return 0;
    }
}
