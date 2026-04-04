<?php
namespace Models;

require_once "./app/models/MyModels.php";
class hotelsModel extends myModels {
    protected $table = "hotels";

    /**
     * Lấy tổng số khách sạn trong bảng hotels
     * @return int Tổng số khách sạn
     */
    public function getCountHotels(): int
    {
        $sql = "SELECT COUNT(*) AS total_hotels FROM {$this->table} WHERE status = 'ACTIVE'";
        $result = $this->conn->query($sql); // mysqli query thuần

        if ($result) {
            $row = $result->fetch_assoc();
            return isset($row['total_hotels']) ? (int)$row['total_hotels'] : 0;
        }

        // Nếu query lỗi, trả về 0
        return 0;
    }
}