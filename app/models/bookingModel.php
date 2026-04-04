<?php
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/myModels.php';

class bookingModel extends myModels
{
    protected $table = "bookings";
    // Các phương thức cụ thể cho bảng bookings có thể được thêm vào đây nếu cần

    /**
     * Lấy tổng platform_fee từ bảng bookings
     * Không dùng select_array hay executeQuery
     */
    public function getTotalPlatformFee(): float
    {
        $sql = "SELECT SUM(platformFee) AS total_platform_fee FROM {$this->table}";
        $result = $this->conn->query($sql); // mysqli query thuần

        if ($result) {
            $row = $result->fetch_assoc();
            return isset($row['total_platform_fee']) ? (float)$row['total_platform_fee'] : 0.0;
        }

        // Trả về 0 nếu query thất bại
        return 0.0;
    }
    public function countAllOrderActive(): int
    {
        $sql = "SELECT COUNT(*) AS total_active FROM {$this->table} WHERE status NOT IN ('COMPLETED', 'CANCELED')";
        $result = $this->conn->query($sql);

        if ($result) {
            $row = $result->fetch_assoc();
            return isset($row['total_active']) ? (int)$row['total_active'] : 0;
        }

        return 0; // Nếu query thất bại
    }
    public function getMonthlyRevenue(int $year = null): array
    {
        $sql = "SELECT YEAR(createdAt) AS year, MONTH(createdAt) AS month, SUM(platformFee) AS revenue
            FROM {$this->table}";

        // Nếu có input năm, thêm điều kiện WHERE
        if ($year !== null) {
            $year = (int)$year; // bảo vệ input
            $sql .= " WHERE YEAR(createdAt) = $year";
        }

        $sql .= " GROUP BY YEAR(createdAt), MONTH(createdAt)
              ORDER BY YEAR(createdAt), MONTH(createdAt)";

        $result = $this->conn->query($sql);
        $monthlyRevenue = [];

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $monthlyRevenue[] = [
                    'year' => (int)$row['year'],
                    'month' => (int)$row['month'],
                    'revenue' => (float)$row['revenue']
                ];
            }
        }

        return $monthlyRevenue;
    }
    public function get5LatestBookings(int $limit = 5): array
    {
        $sql = "SELECT *, users.id as userId,payments.createdAt as paymentCreatedAt FROM {$this->table} JOIN users ON {$this->table}.userId = users.id JOIN payments ON {$this->table}.id = payments.bookingId ORDER BY {$this->table}.createdAt DESC LIMIT $limit";
        $result = $this->conn->query($sql);
        $latestBookings = [];

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $latestBookings[] = $row;
            }
        }

        return $latestBookings;
    }
}
