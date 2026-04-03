<?php
namespace Models;

require_once __DIR__ . '/../core/Database.php';

class RevenueModel {
    private $conn;

    public function __construct() {
        $db = new \Database();
        $this->conn = $db->conn;
    }

    /**
     * Get revenue by day
     * @param string $date Specific date (Y-m-d)
     * @return array Revenue data
     */
    public function getRevenueByDay(string $date = null): array {
        try {
            $date = $date ?? date('Y-m-d');

            $sql = "SELECT 
                        DATE(b.createdAt) as date,
                        COUNT(b.id) as bookingCount,
                        SUM(b.totalAmount) as totalRevenue,
                        SUM(CASE WHEN b.status = 'COMPLETED' THEN b.totalAmount ELSE 0 END) as completedRevenue,
                        SUM(CASE WHEN b.status = 'PENDING' THEN b.totalAmount ELSE 0 END) as pendingRevenue,
                        SUM(CASE WHEN b.status = 'CANCELLED' THEN 0 ELSE 0 END) as cancelledRevenue
                    FROM bookings b
                    WHERE DATE(b.createdAt) = ?
                    GROUP BY DATE(b.createdAt)";

            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('s', $date);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            return $row ?: [
                'date' => $date,
                'bookingCount' => 0,
                'totalRevenue' => 0,
                'completedRevenue' => 0,
                'pendingRevenue' => 0,
                'cancelledRevenue' => 0
            ];
        } catch (\Exception $e) {
            error_log("Error in getRevenueByDay: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get revenue by month
     * @param string $month Month in Y-m format
     * @return array Revenue data
     */
    public function getRevenueByMonth(string $month = null): array {
        try {
            $month = $month ?? date('Y-m');

            $sql = "SELECT 
                        DATE_FORMAT(b.createdAt, '%Y-%m') as month,
                        COUNT(b.id) as bookingCount,
                        SUM(b.totalAmount) as totalRevenue,
                        SUM(CASE WHEN b.status = 'COMPLETED' THEN b.totalAmount ELSE 0 END) as completedRevenue,
                        SUM(CASE WHEN b.status = 'PENDING' THEN b.totalAmount ELSE 0 END) as pendingRevenue,
                        COUNT(DISTINCT b.userId) as uniqueCustomers
                    FROM bookings b
                    WHERE DATE_FORMAT(b.createdAt, '%Y-%m') = ?
                    GROUP BY DATE_FORMAT(b.createdAt, '%Y-%m')";

            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('s', $month);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            return $row ?: [
                'month' => $month,
                'bookingCount' => 0,
                'totalRevenue' => 0,
                'completedRevenue' => 0,
                'pendingRevenue' => 0,
                'uniqueCustomers' => 0
            ];
        } catch (\Exception $e) {
            error_log("Error in getRevenueByMonth: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get revenue by year
     * @param string $year Year in Y format
     * @return array Revenue data
     */
    public function getRevenueByYear(string $year = null): array {
        try {
            $year = $year ?? date('Y');

            $sql = "SELECT 
                        YEAR(b.createdAt) as year,
                        COUNT(b.id) as bookingCount,
                        SUM(b.totalAmount) as totalRevenue,
                        SUM(CASE WHEN b.status = 'COMPLETED' THEN b.totalAmount ELSE 0 END) as completedRevenue,
                        SUM(CASE WHEN b.status = 'PENDING' THEN b.totalAmount ELSE 0 END) as pendingRevenue,
                        COUNT(DISTINCT b.userId) as uniqueCustomers,
                        COUNT(DISTINCT DATE(b.createdAt)) as activeDays
                    FROM bookings b
                    WHERE YEAR(b.createdAt) = ?
                    GROUP BY YEAR(b.createdAt)";

            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('s', $year);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            return $row ?: [
                'year' => $year,
                'bookingCount' => 0,
                'totalRevenue' => 0,
                'completedRevenue' => 0,
                'pendingRevenue' => 0,
                'uniqueCustomers' => 0,
                'activeDays' => 0
            ];
        } catch (\Exception $e) {
            error_log("Error in getRevenueByYear: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get daily revenue for a month (for chart)
     * @param string $month Month in Y-m format
     * @return array Array of daily revenue
     */
    public function getDailyRevenueForMonth(string $month = null): array {
        try {
            $month = $month ?? date('Y-m');

            $sql = "SELECT 
                        DATE(b.createdAt) as date,
                        COUNT(b.id) as bookingCount,
                        SUM(b.totalAmount) as revenue
                    FROM bookings b
                    WHERE DATE_FORMAT(b.createdAt, '%Y-%m') = ?
                    GROUP BY DATE(b.createdAt)
                    ORDER BY DATE(b.createdAt) ASC";

            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('s', $month);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result->fetch_all(\MYSQLI_ASSOC);
        } catch (\Exception $e) {
            error_log("Error in getDailyRevenueForMonth: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get monthly revenue for a year (for chart)
     * @param string $year Year in Y format
     * @return array Array of monthly revenue
     */
    public function getMonthlyRevenueForYear(string $year = null): array {
        try {
            $year = $year ?? date('Y');

            $sql = "SELECT 
                        DATE_FORMAT(b.createdAt, '%Y-%m') as month,
                        COUNT(b.id) as bookingCount,
                        SUM(b.totalAmount) as revenue
                    FROM bookings b
                    WHERE YEAR(b.createdAt) = ?
                    GROUP BY DATE_FORMAT(b.createdAt, '%Y-%m')
                    ORDER BY DATE_FORMAT(b.createdAt, '%Y-%m') ASC";

            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('s', $year);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result->fetch_all(\MYSQLI_ASSOC);
        } catch (\Exception $e) {
            error_log("Error in getMonthlyRevenueForYear: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get revenue by source (WEBSITE, BOOKING_DOT_COM, etc.)
     * @param string $period 'day', 'month', or 'year'
     * @param string $date Date/month/year value
     * @return array Revenue by source
     */
    public function getRevenueBySource(string $period = 'month', string $date = null): array {
        try {
            $date = $date ?? ($period === 'day' ? date('Y-m-d') : ($period === 'month' ? date('Y-m') : date('Y')));

            $dateCondition = '';
            if ($period === 'day') {
                $dateCondition = "DATE(b.createdAt) = ?";
            } else if ($period === 'month') {
                $dateCondition = "DATE_FORMAT(b.createdAt, '%Y-%m') = ?";
            } else {
                $dateCondition = "YEAR(b.createdAt) = ?";
            }

            $sql = "SELECT 
                        b.source,
                        COUNT(b.id) as bookingCount,
                        SUM(b.totalAmount) as revenue,
                        AVG(b.totalAmount) as avgBooking
                    FROM bookings b
                    WHERE {$dateCondition}
                    GROUP BY b.source
                    ORDER BY revenue DESC";

            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('s', $date);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result->fetch_all(\MYSQLI_ASSOC);
        } catch (\Exception $e) {
            error_log("Error in getRevenueBySource: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get top hotels by revenue
     * @param string $period 'day', 'month', or 'year'
     * @param string $date Date/month/year value
     * @param int $limit Number of top hotels
     * @return array Top hotels
     */
    public function getTopHotels(string $period = 'month', string $date = null, int $limit = 5): array {
        try {
            $date = $date ?? ($period === 'day' ? date('Y-m-d') : ($period === 'month' ? date('Y-m') : date('Y')));

            $dateCondition = '';
            if ($period === 'day') {
                $dateCondition = "DATE(b.createdAt) = ?";
            } else if ($period === 'month') {
                $dateCondition = "DATE_FORMAT(b.createdAt, '%Y-%m') = ?";
            } else {
                $dateCondition = "YEAR(b.createdAt) = ?";
            }

            $sql = "SELECT 
                        h.id,
                        h.hotelName,
                        COUNT(b.id) as bookingCount,
                        SUM(b.totalAmount) as revenue,
                        AVG(b.totalAmount) as avgBooking
                    FROM bookings b
                    LEFT JOIN bookingdetails bd ON b.id = bd.bookingId
                    LEFT JOIN roomconfigurations rc ON bd.roomConfigId = rc.id
                    LEFT JOIN hotels h ON rc.hotelId = h.id
                    WHERE {$dateCondition}
                    GROUP BY h.id, h.hotelName
                    ORDER BY revenue DESC
                    LIMIT ?";

            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('si', $date, $limit);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result->fetch_all(\MYSQLI_ASSOC);
        } catch (\Exception $e) {
            error_log("Error in getTopHotels: " . $e->getMessage());
            return [];
        }
    }
}
